<?php

namespace App\Http\Controllers\Statistics;
use Inertia\Inertia;
use Inertia\Response;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

/* Models */
use App\Models\SessionRequest\SessionRequest;
use App\Models\UserManagement\User;
use App\Models\Volunteer\Volunteer;
use App\Models\Career\Career;
use App\Models\Test\Test;
use App\Models\Test\Submission;
use App\Models\Career\CareerRequest;

/* Services */
use App\Services\HookService;
use App\Services\EmailService;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        // Set default values if not specified
        if (!$startDate || !$endDate) {
            $endDate = Carbon::now()->format('Y-m-d');
            $startDate = Carbon::now()->subYear()->startOfMonth()->format('Y-m-d');
        } else {
            $startDate = Carbon::parse($startDate)->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::parse($endDate)->endOfMonth()->format('Y-m-d');
        }
    
        // Calculate the start and end dates as Carbon instances
        $startDate = Carbon::parse($startDate)->startOfMonth();
        $endDate = Carbon::parse($endDate)->endOfMonth();
    
        // Generate a list of months between start and end dates
        $months = $this->generateMonthsList($startDate, $endDate);
    
        // Fetch and prepare data for active volunteers
        $volunteerData = $this->fetchAndPrepareData('volunteers', $startDate, $endDate, $months, ['deleted' => false]);
    
        // Fetch and prepare data for session requests
        $sessionRequestData = $this->fetchAndPrepareData('session_requests', $startDate, $endDate, $months);
    
        $volunteerRoleData = $this->fetchAndPrepareVolunteerRoleData();

        // Prepare summary data
        $summaryData = $this->prepareSummaryData();
    
        return Inertia::render('Analytics/Analytics', [
            'summary' => $summaryData,
            'volunteers' => $volunteerData,
            'sessionRequests' => $sessionRequestData,
            'volunteerRoles' => $volunteerRoleData,
            'careerSearches' => $this->getCareerRequests(),
            'topKeywords' => $this->getTopSearchedKeywords(),
        ]);
    }
    
    private function getCareerRequests() {
        $query = CareerRequest::query();

        if (request('search')) {
            $query->where('email', 'LIKE', '%' . request('search') . '%')
                  ->orWhere('keyword', 'LIKE', '%' . request('search') . '%');
        }
    
        // Add keyword frequency
        $query->select('career_requests.keyword as date', DB::raw('count(career_requests.keyword) as total_number'))
              ->groupBy('career_requests.keyword')
              ->orderBy('total_number', 'desc');
    
        // Paginate the results, 10 items per page
        $paginatedResults = $query->paginate(10);
    
        // Prepare labels and data arrays
        $labels = $paginatedResults->pluck('date');
        $data = $paginatedResults->pluck('total_number');
    
        return [
            'labels' => $labels,
            'data' => $data,
            'pagination' => [
                'current_page' => $paginatedResults->currentPage(),
                'last_page' => $paginatedResults->lastPage(),
                'total' => $paginatedResults->total(),
                'per_page' => $paginatedResults->perPage(),
            ]
        ];
    }

    private function getTopSearchedKeywords() {
        $query = CareerRequest::query();
    
        // Select only the 'keyword' column
        $keywords = $query->pluck('keyword');
    
        // Normalize keywords (convert to lowercase)
        $normalizedKeywords = $keywords->map(function ($item) {
            return strtolower($item);
        });
           
        // Count word frequencies
        $wordFrequencies = array_count_values($normalizedKeywords->toArray());
    
        // Sort by frequency
        arsort($wordFrequencies);
    
        // Get top keywords (you can adjust the number as needed)
        $topKeywords = array_slice($wordFrequencies, 0, 100);
    
        return $topKeywords;
    }

    private function generateMonthsList($startDate, $endDate)
    {
        $months = [];
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $months[] = $currentDate->format('Y-m');
            $currentDate->addMonth();
        }
        return $months;
    }
    
    private function fetchAndPrepareVolunteerRoleData() {
        $activeStatusId = DB::table('volunteer_statuses')
            ->where('is_active', 1)
            ->value('id');

        $query = DB::table('volunteers')
            ->join('volunteer_roles', 'volunteers.role', '=', 'volunteer_roles.id')
            ->select('volunteer_roles.name', DB::raw('COUNT(*) as total'))
            ->where('volunteers.status', $activeStatusId)
            ->groupBy('volunteer_roles.name')
            ->get();

        $data = [];
        $labels = [];
        foreach ($query as $row) {
            $data[] = $row->total;  // Append the total count to the data array
            $labels[] = $row->name;
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    private function fetchAndPrepareData($table, $startDate, $endDate, $months, $conditions = [])
    {
        $query = DB::table($table)
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('COUNT(*) as total_number'))
            ->whereBetween('created_at', [$startDate, $endDate]);
    
        foreach ($conditions as $column => $value) {
            $query->where($column, $value);
        }
    
        $data = $query->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
            ->orderBy('month', 'asc')
            ->pluck('total_number', 'month');
    
        $dataArray = array_map(function($month) use ($data) {
            return $data->get($month, 0);
        }, $months);
    
        $labels = array_map(function($month) {
            return Carbon::createFromFormat('Y-m', $month)->format('M');
        }, $months);
    
        return [
            'labels' => $labels,
            'data' => $dataArray,
        ];
    }
    
    private function prepareSummaryData()
    {
        return [
            "volunteerApplications" => Volunteer::where('deleted', false)->count(),
            "careers" => Career::where('deleted', false)->count(),
            "testSubmissions" => Submission::count(),
            "sessionRequests" => SessionRequest::count()
        ];
    }
}
