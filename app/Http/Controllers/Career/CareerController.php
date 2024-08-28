<?php

namespace App\Http\Controllers\Career;

use stdClass;

use Inertia\Inertia;
use Inertia\Response;

use App\Http\Controllers\Controller;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/* MOdels */
use App\Models\UserManagement\User;
use App\Models\UserManagement\Role;
use App\Models\UserManagement\Permission;

use App\Models\Career\Career;
use App\Models\Career\CareerRequest;
use App\Models\Career\Course;
use App\Models\Career\RiasecCode;
use App\Models\Career\Interest;
use App\Models\Career\Skill;
use App\Models\Career\University;

class CareerController extends Controller
{
    private function syncSkills(Career $career, $skills)
    {
        if (isset( $skills )) {
            $career->skills()->sync( $skills );
        } else {
            $career->skills()->detach();
        }
    }

    private function syncInterests(Career $career, $interests)
    {
        if (isset( $interests )) {
            $career->interests()->sync( $interests );
        } else {
            $career->interests()->detach();
        }
    }

    private function syncCourses(Career $career, $courses)
    {
        if (isset( $courses )) {
           $career->courses()->sync( $courses );
        } else {
            $career->courses()->detach();
        }
    }

    private function syncResponsibilities(Career $career, $responsibilities)
    {
        if (isset($responsibilities)) {
            // First, detach all existing responsibilities to remove the ones not present in the new request.
            $career->responsibilities()->delete();

            // Now, loop through the given responsibilities and create new ones.
            foreach ($responsibilities as $resp) {
                $career->responsibilities()->create([
                    'text' => $resp['text']
                    // If you have more fields, they can be added here.
                ]);
            }
        } else {
            // If no responsibilities are provided in the request, delete all existing responsibilities.
            $career->responsibilities()->delete();
        }
    }

    private function syncHollandCodes(Career $career, $hollandCodes)
    {
        if (isset($hollandCodes)) {
            $hollandCodeIds = array_map(function ($item) {
                // Assuming that 'value' is a boolean indicating whether to include the code
                return isset($item['value']) && $item['value'] ? $item['id'] : null;
            }, $hollandCodes);

            // Remove null values from the array
            $hollandCodeIds = array_filter($hollandCodeIds, function($value) {
                return !is_null($value);
            });

            // Sync the holland codes in the pivot table.
            $career->riasecCodes()->sync($hollandCodeIds);
        } else {
            // If no holland codes are provided, detach all existing holland codes.
            $career->riasecCodes()->detach();
        }
    }

    private function getCareers()
    {
        $query = Career::query();

        // Datatable search parameter.
        if( request('search') ) {
            $query->where('title', 'LIKE', '%'.request('search').'%');
        }

        $query->with(['riasecCodes' => function($query) {
            $query->pluck('career_riasec_code.riasec_code_id');
        }]);

        if( empty( Auth::user() ) ) {
            $careers = $query->where('deleted', false)->where('status', 1)->paginate(19);
        }else {
            $careers = $query->where('deleted', false)->orderby('status')->paginate(19);
        }

        return $careers;
    }

    public function index()
    { 
        return Inertia::render('Careers/Index', [
            'response' => [],
            'filters' => [
                'search' => request('search') ? request('search') : '',
            ],
            'careers' => self::getCareers(),
            'search' => request('search') ? request('search') : '',
        ]);
    }

    /**
     * Retrieves a 
     *  of careers based on search criteria.
     * 
     * @param Request $request The request object containing search parameters.
     * @return \Inertia\Response The rendered view of career listings.
     *
     * - search         String Search keyword or ''
     * - order          String 'desc', 'asc'
     * - hollandCode    String 'RIASEC'
     * 
     * eg. /careers/list?search=x&code=RIA&order=desc
     */
    public function list( Request $request )
    {
        // Set active career status.
        $activeCareerStatus = 2;

        // Get the requested order (default: 'desc').
        $order = $request->query('order', 'desc');

        // Get the search keyword (default: empty string).
        $searchKeyword = $request->query('search', '');

        // Get the Holland code filter (default: null).
        $hollandCode = $request->query('code');
        $hollandCodeArray = [];

        // Convert Holland code string to an array, if not empty.
        if( !empty( $hollandCode ) ) {
            $hollandCodeArray = str_split( $hollandCode );
        }

        // Prepare query that will fetch all filtered available careers. 
        $careers = Career::query()
            ->when($searchKeyword, function ($query, $search) {
                $query->where('title', 'LIKE', "%{$search}%");
            })
            ->where('deleted', false)
            ->where('status', $activeCareerStatus)
            ->orderBy('created_at', $order)
            ->with('riasecCodes');

        // Apply Holland code filter, if provided.
        if( !empty($hollandCodeArray) ) {
            $careers->whereHas('riasecCodes', function ($query) use ($hollandCodeArray) {
                $query->whereIn('symbol', $hollandCodeArray);
            });
        }

        // Select return field and add pagination.
        $careers = $careers->paginate(7)
            ->through(function ($career) {
                return [
                    'id' => $career->id,
                    'title' => $career->title,
                    'description' => $career->description,
                    'riasec_codes' => $career->riasecCodes->pluck('symbol')
                ];
            })
            ->withQueryString();

        // Log search requests with no results for further analysis.
        if( $careers->isEmpty() ) {
            $pendingCareerRequestStatus = 1;
            $inboundEmail = 'reply@careerexplorer.gr';

            // Create a new CareerRequest entry to keep track of the unsuccessful search.
            $careerRequest = new CareerRequest([
                'keyword' => $searchKeyword,
                'email' => $inboundEmail,
                'status' => $pendingCareerRequestStatus
            ]);

            $careerRequest->save();
        }

        return Inertia::render('Careers/List', [
            'careers' => $careers,
            'filters' => [
                'search' => $searchKeyword,
                'page' => $careers->currentPage(),
                'order' => $order,
                'hollandCode' => $hollandCode
            ]
        ]);
    }

    public function create()
    {
        return Inertia::render('Careers/CreateEdit', [
            'response' => [],
            'riasecCodesDropdown' => RiasecCode::all(),
            'career' => new stdClass(),
            
            'interestDropdownOptions' => Interest::all(),
            'skillDropdownOptions' => Skill::all(),
            'courseDropdownOptions' => Course::all(),
        ]);
    }

    public function store( Request $request )
    {
        $career = new Career();

        $career->title = $request->title;
        $career->keywords = $request->keywords;
        $career->status = 1;
        $career->category = 1;
        $career->link = $request->link;
        $career->description = $request->description;

        $career->save();

        if( isset($request->connections['skills']) ) {
            $this->syncSkills($career, $request->connections['skills']);
        }

        if( isset($request->connections['interests']) ) {
            $this->syncInterests($career, $request->connections['interests']);
        }

        if( isset($request->connections['courses']) ) {
            $this->syncCourses($career, $request->connections['courses']);
        }

        if( isset($request->responsibilities) ) {
            $this->syncResponsibilities($career, $request->responsibilities);
        }
        
        if( isset($request->hollandCodes) ) {
            $this->syncHollandCodes($career, $request->hollandCodes);
        }

        return redirect()
            ->route('careers.create2')
            ->with([
                'message'=> 'Το επάγγελμα δημιουργήθηκε.',
                'status' => 'success'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        return Inertia::render('Careers/Show', [
            'response' => [],
            'job_description' => Career::find( $id )
        ]);
    }

    public function single( $id )
    {
        $career = Career::with(['riasecCodes', 'interests', 'skills', 'responsibilities', 'courses'])->find($id);

        return Inertia::render('Careers/Show', [
            'career' => $career
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( $id )
    {
        $career = Career::with([
            'riasecCodes',
            'responsibilities',
            'interests', 
            'skills', 
            'courses'
        ])->find($id);

        $career->hollandCodes = $career->riasecCodes->pluck('id')->toArray();
        $career->responsibilities = $career->responsibilities->pluck('id')->toArray();
        $career->skills = $career->skills->pluck('id')->toArray();
        $career->interests = $career->interests->pluck('id')->toArray();
        $career->courses = $career->courses->pluck('id')->toArray();

        $riasecCodesDropdown = RiasecCode::all()->map(function ($riasecCode) use ($career) {
            $riasecCode->value = in_array($riasecCode->id, $career->hollandCodes);
            return $riasecCode;
        });

        return Inertia::render('Careers/CreateEdit', [
            'response' => [],
            'career' => $career,
            'riasecCodesDropdown' => $riasecCodesDropdown,
            
            'interestDropdownOptions' => Interest::all(),
            'skillDropdownOptions' => Skill::all(),
            'courseDropdownOptions' => Course::all(),
            'universityDropdownOptions' => University::all()
        ]);
    }

    public function update( Request $request )
    {
        $career = Career::find( $request->id );

        if( is_null($career) ) {
            return redirect()->route( 'users' )
                ->with(['message'=> 'Oups, something went wrong. Career was not updated [CODE 003].']);
        }
 
        $career->title = $request->title;
        $career->keywords = $request->keywords;
        $career->status = $request->status;
        $career->category = 1;
        $career->link = $request->link;
        $career->description = $request->description;

        $career->save();

        if( isset($request->connections['skills']) ) {
            $this->syncSkills($career, $request->connections['skills']);
        }

        if( isset($request->connections['interests']) ) {
            $this->syncInterests($career, $request->connections['interests']);
        }

        if( isset($request->connections['courses']) ) {
            $this->syncCourses($career, $request->connections['courses']);
        }

        if( isset($request->responsibilities) ) {
            $this->syncResponsibilities($career, $request->responsibilities);
        }
        
        if( isset($request->hollandCodes) ) {
            $this->syncHollandCodes($career, $request->hollandCodes);
        }

        return redirect()->back()
            ->with([
                'message'=> 'Επιτυχής ενημέρωση επαγγέλματος!',
                'status' => 'success'
            ]);
    }

    public function careerRequest( Request $request )
    {        
        $requestCareer = new CareerRequest();
        $requestCareer->email = $request->email;           // User email that requested the job role.
        $requestCareer->search = $request->search;         // What the user wrote in the search bar.
        $requestCareer->status = 1;                        // Set status to pending (0: Pending 1: Completed)
        $requestCareer->save();

        return redirect()
            ->route('careers.list')
            ->with([
                'message'=> 'Το αίτημά σου καταχωρήθηκε με επιτυχία!',
                'status' => 'success'
            ]);
    }

    public function destroy( $id )
    {
        $career = Career::find( $id );
        $career->deleted = true;
        $career->save();

        return redirect()
            ->route('careers.index')
            ->with([
                'message'=> 'Career successfully deleted!',
                'status' => 'success'
            ]);
    }

    public function showCareerSettings()
    {
        return Inertia::render('Careers/Settings', [
            'response' => []
        ]);
    }
}
