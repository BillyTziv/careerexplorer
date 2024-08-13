<?php

namespace App\Http\Controllers\Career;

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

use App\Models\Career\Interest;
use App\Models\Career\Skill;
use App\Models\Career\RiasecCode;

class CareerInterestController extends Controller
{

    private function getCareerInterests() {
        $query = Interest::query();

        if( request('search') ) {
            $query->where('name', 'LIKE', '%'.request('search').'%');
        }

        if( request('filters') ) {
            // TODO ..
        }

        $query->with(['riasecCodes' => function($query) {
            $query->pluck('interest_riasec_code.riasec_code_id');
        }]);

        // Get all non deleted users with their roles.
        $interests = $query->paginate(10);
        return $interests;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Careers/Interests/Index', [
            'response' => [],
            'interests' => self::getCareerInterests(),
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
        $hollandCodes = RiasecCode::select('id', 'name as label', 'description')->get();

        return Inertia::render('Careers/Interests/CreateEdit', [
            'response' => [],
            'interest' => null,
            'hollandCodes' => $hollandCodes
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
        // Store the new interest.
        $interest = new Interest();
        $interest->name = trim($request->name);
        $interest->description = trim($request->description);
        $interest->save();

        // Store interest_riasec_codes pivot table.
        $interest->riasecCodes()->attach( $request->hollandCodes );

        return redirect()
            ->route('career-interests.index')
            ->with([
                'message'=> 'Το ενδιαφέρον καταχωρήθηκε με επιτυχία!',
                'status' => 'success'
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
        $hollandCodes = RiasecCode::select('id', 'name as label', 'description')->get();

        $interest = Interest::with(['riasecCodes:id'])
            ->select('id', 'name', 'description')
            ->find($id);

        $interest->hollandCodes = $interest->riasecCodes
            ->pluck('id')
            ->toArray();

        unset($interest->riasecCodes);

        // $interest = Interest::with(['riasecCodes'])->find($id);
        // $interest->hollandCodes = $interest->riasecCodes->pluck('id')->toArray();

        return Inertia::render('Careers/Interests/CreateEdit', [
            'response' => [],
            'interest' => $interest,
            'hollandCodes' => $hollandCodes
        ]);
    }

    public function update( Request $request )
    {
        $interest = Interest::find( $request->id );

        if( is_null($interest) ) {
            return redirect()->route( 'career-interests.index' )
                ->with(['message'=> 'Ουπς, κάτι φαίνεται να πήγε στραβά. Παρακαλούμε ξαναπροσπαθήστε.']);
        }
        
        $interest->name = trim($request->name);
        $interest->description = trim($request->description);
        $interest->save();

        if( isset($request->hollandCodes) ) {
            $interest->riasecCodes()->sync( $request->hollandCodes );
        }else {
            $interest->riasecCodes()->detach();
        }

        return redirect()
            ->route('career-interests.index')
            ->with([
                'message'=> 'Το ενδιαφέρον ενημερώθηκε με επιτυχία!',
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
        $interest = Interest::find( $id );
        $interest->delete();

        return redirect()
            ->route('career-interests.index')
            ->with([
                'message'=> 'Το ενδιαφέρον διαγράφηκε με επιτυχία!',
                'status' => 'success'
            ]);
    }
}
