<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use stdClass;
use App\Models\University;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UniversityController extends Controller
{
    private function getUniversities() {
        $query = University::query();

        if( request('search') ) {
            $query->where('name', 'LIKE', '%'.request('search').'%');
        }

        $universitiesFiltered = $query->paginate(15);

        return $universitiesFiltered;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Careers/Universities/Index', [
            'response' => [],
            'universities' => self::getUniversities(),
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
        return Inertia::render('Careers/Universities/CreateEdit', [
            'response' => [],
            'university' => new stdClass(),
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
        $university = new University();
        $university->name = trim($request->name);
        $university->save();

        return redirect()
            ->route('universities.index')   
            ->with([
                'message' => 'Το πανεπιστήμιο δημιουργήθηκε με επιτυχία!',
                'status' => 'success',
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
