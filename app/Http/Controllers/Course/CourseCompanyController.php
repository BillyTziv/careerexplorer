<?php

namespace App\Http\Controllers\Course;

use Inertia\Inertia;
use Inertia\Response;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;

/* Models */
use App\Models\UserManagement\User;
use App\Models\UserManagement\Role;
use App\Models\UserManagement\Permission;

use App\Models\Career\Interest;
use App\Models\Career\Skill;
use App\Models\Career\RiasecCode;
use App\Models\Course\Company;

class CourseCompanyController extends Controller
{
    private function getCourseCompanies() {
        $query = Company::query();

        if( request('search') ) {
            $query->where('name', 'LIKE', '%'.request('search').'%');
        }

        $courseCompanies = $query->paginate(10);

        return $courseCompanies;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Courses/Companies/Index', [
            'response' => [],
            'courseCompanies' => self::getCourseCompanies(),
            'filters' => [
                'search' => request('search') ? request('search') : ''
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Courses/Companies/CreateEdit', [
            'response' => [],
            'courseCompany' => null
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         // Store the new skill.
         $courseCompany = new Company();
         $courseCompany->name = trim($request->name);
         $courseCompany->description = trim($request->description);
         $courseCompany->save();
    
         return redirect()
              ->route('course-companies.index')
              ->with([
                  'message'=> 'Ο φορέας δημιθουργήθηκε με επιτυχία!',
                  'status' => 'success'
              ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $courseCompany = Company::select('id', 'name')->find($id);

        return Inertia::render('Courses/Companies/CreateEdit', [
            'response' => [],
            'courseCompany' => $courseCompany
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $courseCompany = Company::find( $request->id );

        if( is_null($courseCompany) ) {
            return redirect()->route( 'course-companies.index' )
                ->with(['message'=> 'Oups, something went wrong. Career skill was not updated.']);
        }
        
        $courseCompany->name = trim($request->name);
        $courseCompany->save();

        return redirect()
            ->route('course-companies.index')
            ->with([
                'message'=> 'Ο φορέας ενημερώθηκε με επιτυχία!',
                'status' => 'success'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $courseCompany = Company::find( $id );
        $courseCompany->delete();

        return redirect()
            ->route('course-companies.index')
            ->with([
                'message'=> 'Ο φορέας διαγράφηκε με επιτυχία!',
                'status' => 'success'
            ]);
    }
}
