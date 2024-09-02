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

/* Services */
use App\Services\HookService;
use App\Services\EmailService;

class StatisticsController extends Controller
{
    public function index() {     
        $volunteers = DB::table('volunteers')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count( DISTINCT id ) as total_number'))
            ->where('created_at', '>=', Carbon::now()->subMonth(1))
            ->where('deleted', false)
            ->groupBy('date')
            ->get();

        // $testSubmissions = DB::table('answers')
        //     ->select(DB::raw('DATE(created_at) as date'), DB::raw('count( DISTINCT user_id ) as total_number'))
        //     ->where('created_at', '>=', Carbon::now()->subMonth(1))
        //     ->groupBy('date')
        //     ->get();

        // $careerRequests = DB::table('career_requests')
        //     ->select(DB::raw('DATE(created_at) as date'), DB::raw('count( DISTINCT id ) as total_number'))
        //     ->where('created_at', '>=', Carbon::now()->subMonth(1))
        //     ->groupBy('date')
        //     ->get();

        // $sessionRequests = DB::table('session_requests')
        //     ->select(DB::raw('DATE(created_at) as date'), DB::raw('count( DISTINCT id ) as total_number'))
        //     ->where('created_at', '>=', Carbon::now()->subMonth(1))
        //     ->where('deleted', false)
        //     ->groupBy('date')
        //     ->get();

        $statisticsData = array(
            "volunteers" => Volunteer::where('deleted', false)->count(),
            // "careers" => Career::count(),
            // "testSubmissions" => Test::count(),
            "sessionRequests" => SessionRequest::count(),
            // "careerRequests" => CareerRequest::count(),
        );

        return Inertia::render('Statistics', [
            'volunteers' => $volunteers,
            // 'testSubmissions' => $testSubmissions,
            // 'careerRequests' => $careerRequests,
            'statistics' => $statisticsData,
            // 'sessionRequests' => $sessionRequests
        ]);
    }
}
