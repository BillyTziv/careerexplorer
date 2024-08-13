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

class CareerSkillController extends Controller
{
    private function getSkills() {
        $query = Skill::query();

        if( request('search') ) {
            $query->where('name', 'LIKE', '%'.request('search').'%');
        }

        $query->with(['riasecCodes' => function($query) {
            $query->pluck('riasec_code_skill.riasec_code_id');
        }]);

        $skills = $query->paginate(10);

        return $skills;
    }

    public function index()
    {
        return Inertia::render('Careers/Skills/Index', [
            'response' => [],
            'skills' => self::getSkills(),
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

        return Inertia::render('Careers/Skills/CreateEdit', [
            'response' => [],
            'skill' => null,
            'hollandCodes' => $hollandCodes
        ]);
    }

    public function store(Request $request)
    {
        // Store the new skill.
        $skill = new Skill();

        $skill->name = trim($request->name);
        $skill->description = trim($request->description);

        $skill->save();
 
        // Store riasec_code_skill pivot table.
        $skill->riasecCodes()->attach( $request->hollandCodes );
 
        return redirect()
             ->route('career-skills.index')
             ->with([
                 'message'=> 'Το ενδιαφέρον δημιθουργήθηκε με επιτυχία!',
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

        $skill = Skill::with(['riasecCodes:id'])
            ->select('id', 'name', 'description')
            ->find($id);

        $skill->hollandCodes = $skill->riasecCodes
            ->pluck('id')
            ->toArray();

        unset($skill->riasecCodes);

        return Inertia::render('Careers/Skills/CreateEdit', [
            'response' => [],
            'skill' => $skill,
            'hollandCodes' => $hollandCodes
        ]);
    }

    public function update( Request $request )
    {
        $skill = Skill::find( $request->id );

        if( is_null($skill) ) {
            return redirect()->route( 'career-skills.index' )
                ->with(['message'=> 'Oups, something went wrong. Career skill was not updated.']);
        }
        
        $skill->name = trim($request->name);
        $skill->description = trim($request->description);
        $skill->save();

        if( isset($request->hollandCodes) ) {
            $skill->riasecCodes()->sync( $request->hollandCodes );
        }else {
            $skill->riasecCodes()->detach();
        }

        return redirect()
            ->route('career-skills.index')
            ->with([
                'message'=> 'Η ενημέρωση ολοκληρώθηκε με επιτυχία!',
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
        $skill = Skill::find( $id );
        $skill->delete();

        return redirect()
            ->route('career-skills.index')
            ->with([
                'message'=> 'Το ενδιαφέρον διαγράφηκε με επιτυχία!',
                'status' => 'success'
            ]);
    }
}
