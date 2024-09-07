<?php

namespace App\Http\Controllers\Course;

use Inertia\Inertia;
use Inertia\Response;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;

/* MOdels */
use App\Models\UserManagement\User;
use App\Models\UserManagement\Role;
use App\Models\UserManagement\Permission;

use App\Models\Course\Course;
use App\Models\Course\Company;

/* Support */
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use stdClass;

class CourseController extends Controller
{
    private function getCompaniesForDropdown()
    {
        $companies = Company::all(['id', 'name'])->map(function ($company) {
            return ['id' => $company->id, 'label' => $company->name];
        });

        return $companies;
    }

    private function getCourses() {
        $query = Course::query()->with(['company']);

        if( request('search') ) {
            $query->where('title', 'LIKE', '%'.request('search').'%');
        }

        if(request('company')) {
            $query->where('company_id', '=', request('company'));
        }

        // Eager load the 'company' relationship and append the 'company_name'
        $courses = $query->paginate(10);

        return $courses;
    }

    public function index()
    {
        return Inertia::render('Courses/Index', [
            'response' => [],
            'courses' => self::getCourses(),
            'companyDropdownOptions' => self::getCompaniesForDropdown(),
            'filters' => [
                'search' => request('search') ? request('search') : '',
                'company' => request('company') ? request('company') : ''
            ],
            'search' => request('search') ? request('search') : ''
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Courses/CreateEdit', [
            'course' => new stdClass(),
            'response' => [],
            'companyDropdownOptions' => self::getCompaniesForDropdown()
        ]);
    }

    public function show( $id ) {
        $course =  Course::find($id);

        return Inertia::render('Courses/Show', [
            'response' => [],
            'course' => $course,
            'companyDropdownOptions' => self::getCompaniesForDropdown()
        ]);
    }

    public function edit( $id ) {
        $course = Course::select('id', 'title', 'description', 'status', 'link', 'company_id')->find($id);

        return Inertia::render('Courses/CreateEdit', [
            'response' => [],
            'course' => $course,
            'companyDropdownOptions' => self::getCompaniesForDropdown()
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'company_id' => 'required|integer',
            'description' => 'required|string|max:255',
            'link' => 'required|string|max:255'
        ];
        
        $messages = [
            'required' => 'Το πεδίο είναι υποχρεωτικό.'
        ];
        
        $validatedData = $request->validate($rules, $messages);

        $course = new Course();
        $course->title = trim($request->title);
        $course->status = 1;
        $course->description = trim($request->description);
        $course->link = trim($request->link);
        $course->feature_image = trim($request->feature_image);
        $course->company_id = $request->company_id;

        $course->save();

        return redirect()
            ->route('courses.index')
            ->with([
                'message'=> 'Το μάθημα δημιθουργήθηκε με επιτυχία!',
                'status' => 'success'
            ]);
    }

    public function update(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'company_id' => 'required|integer',
            'description' => 'required|string|max:255',
            'link' => 'required|string|max:255'
        ];
        
        $messages = [
            'required' => 'Το πεδίο είναι υποχρεωτικό.'
        ];
        
        $validatedData = $request->validate($rules, $messages);

        
        $course = Course::find( $request->id );

        if( is_null($course) ) {
            return redirect()->route( 'courses.index' )
                ->with(['message'=> 'Ουπς, κάτι πήγε στραβά. Το μάθημα δεν ενημερώθηκε.']);
        }
        
        $course->title = trim($request->title);
        $course->description = trim($request->description);
        $course->status = 1;
        $course->link = trim($request->link);
        $course->feature_image = trim($request->feature_image);
        $course->company_id = $request->company_id;

        $course->save();

        return redirect()
            ->route('courses.index')
            ->with([
                'message'=> 'Το μάθημα ενημερώθηκε με επιτυχία!',
                'status' => 'success'
            ]);
    }

    public function destroy( $id )
    {
        $course = Course::find( $id );
        $course->delete();

        return redirect()
            ->route('courses.index')
            ->with([
                'message'=> 'Το μάθημα διαγράφηκε με επιτυχία!',
                'status' => 'success'
            ]);
    }
}
