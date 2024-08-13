<?php

namespace App\Http\Controllers\Career;

use Inertia\Inertia;
use Inertia\Response;

use App\Http\Controllers\Controller;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

/* MOdels */
use App\Models\UserManagement\User;
use App\Models\UserManagement\Role;
use App\Models\UserManagement\Permission;

use App\Models\CareerRequest;
use App\Models\Career;


class CareerRequestController extends Controller
{
    private function getCareerRequests() {
        $query = CareerRequest::query();

        if( request('search') ) {
            $query->where('email', 'LIKE', '%'.request('search').'%');
            $query->orWhere('keyword', 'LIKE', '%'.request('search').'%');
        }

        // Add keyword frequency
        $query->select('career_requests.keyword as date', DB::raw('count(career_requests.keyword) as total_number'))
            ->groupBy('career_requests.keyword')
            ->orderBy('total_number', 'desc');
        
        // Get all non deleted users with their roles.
        $users = $query->paginate(15);
        return $users;
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

    public function index() {

        return Inertia::render('CareerRequest/Index', [
            'response' => [],
            'careerRequests' => self::getCareerRequests(),
            'topSearchedKeywords' => self::getTopSearchedKeywords(),
            'filters' => [
                'search' => request('search') ? request('search') : '',
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function store(Request $request) {
        $careerRequest = new CareerRequest();
        $careerRequest->email = $request->email;
        $careerRequest->status = 1;                     // 0: Pending 1: Completed  
        $careerRequest->keyword = $request->keyword;
        $careerRequest->save();
      
        return redirect()
            ->route('career-sessions.list')
            ->with('message', [
                'status' => 'success',
                'message' => 'Η αίτηση επαγγέλματος καταχωρήθηκε με επιτυχία!'
            ]);
    }

    public function destroy($id) {
        $careerRequest = CareerRequest::find( $id );
        $careerRequest->delete();

        return redirect()
            ->route('career-requests.index')
            ->with([
                'status' => 'success',
                'message' => 'Η αίτηση επαγγέλματος διαγράφηκε με επιτυχία!'
            ]);
    }

    public function complete( Request $request ) {
        $application = CareerRequest::find( $request->id );
        $application->status = 2;
        $application->save();

        return redirect()
            ->route('career-requests.index')
            ->with([
                'message'=> 'Η αίτηση επαγγέλματος ολοκληρώθηκε με επιτυχία!',
                'status' => 'success'
            ]);
    }
}
