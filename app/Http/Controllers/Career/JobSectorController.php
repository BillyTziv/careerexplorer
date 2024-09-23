<?php

namespace App\Http\Controllers\Career;

use Inertia\Inertia;
use Inertia\Response;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


/* MOdels */
use App\Models\UserManagement\User;
use App\Models\UserManagement\Role;
use App\Models\UserManagement\Permission;
use App\Models\Career\RiasecCode;
use App\Models\Career\CareerValue;
use App\Models\Career\Career;
use App\Models\Career\JobSector;

class JobSectorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobSectors = JobSector::all();

        return Inertia::render('JobSectors/Index', [
            'jobSectors' => $jobSectors
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('JobSectors/CreateEdit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);

        JobSector::create([
            'title' => $request->title,
            'description' => $request->description,
            'icon' => $request->icon,
        ]);

        return redirect()->route('job-sectors.index')->with('success', 'Ο κλάδος δημιουργήθηκε επιτυχώς.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $jobSector = JobSector::findOrFail($id);

        return Inertia::render('JobSectors/Show', [
            'jobSector' => $jobSector
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * This method is responsible for retrieving a specific JobSector record 
     * from the database based on the provided ID and passing it to the Inertia 
     * frontend for editing.
     *
     * @param string $id The ID of the JobSector to edit.
     *
     * @return \Inertia\Response Renders the 'JobSectors/CreateEdit' Inertia page 
     * with the JobSector data passed as props.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If no JobSector 
     * is found with the given ID, an exception will be thrown and a 404 page will 
     * be shown (handled by default by Laravel).
     */
    public function edit(string $id)
    {
        $jobSector = JobSector::findorFail($id);

        return Inertia::render('JobSectors/CreateEdit', [
            'jobSector' => $jobSector
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
        ];
        
        $messages = [
            'required' => 'Το πεδίο είναι υποχρεωτικό.',
        ];

        // Validate the incoming request
        $validatedData = $request->validate($rules, $messages);

        // Find the JobSector by ID or fail with a 404 response
        $jobSector = JobSector::findOrFail($id);

        // Update the JobSector with validated data
        $jobSector->update($validatedData);

        // Redirect back with success message
        return redirect()
            ->route('job-sectors.index')
            ->with(
                'success', 'Ο κλάδος ενημερώθηκε επιτυχώς.'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jobSector = JobSector::findOrFail($id);
        $jobSector->delete();

        return redirect()->route('job-sectors.index')->with('success', 'Job Sector deleted successfully.');
    }
}
