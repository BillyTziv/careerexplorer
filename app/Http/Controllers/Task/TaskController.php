<?php

namespace App\Http\Controllers\Task;

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
use App\Models\Task\Task;
use App\Models\Task\TaskStatus;
use App\Models\EmailTemplate\EmailTemplate;
use App\Models\Task\TaskLog;

/* Services */
use App\Services\HookService;
use App\Services\EmailService;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     */
    public function index(Request $request): Response
    {
        $tasks = $this->getTasks($request);

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
            'taskStatusDropdownOptions' => $this->getTaskStatusOptions(),
            'volunteerDropdownOptions' => $this->getVolunteerOptions(),
            'filters' => $request->only(['search', 'status', 'volunteer']),
        ]);
    }

    /**
     * Show the form for creating a new task.
     */
    public function create(): Response
    {
        return Inertia::render('Tasks/CreateEdit', [
            'task' => [],
            'taskStatusDropdownOptions' => $this->getTaskStatusOptions(),
            'volunteerDropdownOptions' => $this->getVolunteerOptions(),
            'emailTemplates' => $this->getEmailTemplates(),
        ]);
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->validateTask($request);


        try {
            DB::beginTransaction();

            $task = Task::create([
                'volunteer_id' => $validatedData['volunteer_id'],
                'task_name' => $validatedData['task_name'],
                'description' => $validatedData['description'],
                'estimated_time' => $validatedData['estimated_time'],
                'status_id' => $validatedData['status_id'],
            ]);

            DB::commit();

            return redirect()->route('tasks.index')->with([
                'message' => 'Task created successfully!',
                'status' => 'success',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            // Log the error if necessary
            // Log::error($e->getMessage());

            return back()->with([
                'message' => 'Something went wrong. Please try again.',
                'status' => 'error',
            ]);
        }
    }

    /**
     * Display the specified task.
     */
    public function show($id): Response
    {
        try {
            $task = Task::with(['volunteer', 'status', 'taskLogs'])->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with([
                'message' => 'Task not found.',
                'status' => 'error'
            ]);
        }

        return Inertia::render('Tasks/Show', [
            'task' => $task,
            'taskStatusDropdownOptions' => $this->getTaskStatusOptions(),

        ]);
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit($id): Response
    {
        try {
            $task = Task::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with([
                'message' => 'Task not found.',
                'status' => 'error',
            ]);
        }

        return Inertia::render('Tasks/CreateEdit', [
            'task' => $task,
            'taskStatusDropdownOptions' => $this->getTaskStatusOptions(),
            'volunteerDropdownOptions' => $this->getVolunteerOptions(),
            'emailTemplates' => $this->getEmailTemplates(),
        ]);
    }

    public function updateStatus(Request $request, $task) {
        // Validate that the new status value is a valid status ID.
        // $request->validate([
        //     'newStatusValue' => 'required|integer|exists:volunteer_statuses,id',
        // ]);

        // Get the hook_id based on the email id.
        //if( $request->sendEmail ) {
            // try {
            //     // If exists, get the email template for the status change email.
            //     $status = VolunteerStatus::find($request->newStatusValue);
            //     $emailTemplateId = $status->email_template_id;

            //     $hookId = EmailTemplate::findOrFail($emailTemplateId)->hook_id;
            // } catch (ModelNotFoundException $e) {
            //     // Handle the error here
            //     // For example, you can return an error response
            //     return back()->with([
            //         'status' => 'error',
            //         'message' => 'Δεν υπάρχει πρότυπο email για την συγκεκριμένη κατάσταση.'
            //     ], 404);
            // }
        //}

        // Change the status of the volunteer.
        $task = Task::findOrFail($task);
        $oldStatus = $task->status;
        $task->status = $request->newStatusValue;
        // $task->disapproved_reason = $request->statusChangeReason ?? null;
        $task->save();

        // $oldStatusName = VolunteerStatus::find($oldStatus)->name;
        // $newStatusName = VolunteerStatus::find($volunteer->status)->name;
        // $statusChangeReason = $request->statusChangeReason ?? 'Δεν υπάρχει λόγος.';

        // Log the status change
        // VolunteerHistory::create([
        //     'volunteer_id' => $volunteer->id,
        //     'user_id' => Auth::id(),
        //     'action' => 'status changed',
        //     'description' => 'Η κατάσταση άλλαξε από ' . $oldStatusName . ' σε ' . $newStatusName . ' γιατί ' . $statusChangeReason,
        // ]);

        // Assuming the user is authenticated
        // if( !$request->sendEmail ) {
        //     return back()->with([
        //         'message' => 'Η κατάσταση του εθελοντή ενημερώθηκε! Δεν έγινε αποστολή email.',
        //         'status' => 'success',
        //     ]);
        // }

        // Send the email.
        //$this->hookService->trigger($hookId, $volunteer->email, $volunteer );

        return back()->with([
            'message' => 'Η κατάσταση ενημερώθηκε!',
            'status' => 'success',
        ]);
    }


    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $this->validateTask($request, $id);

        try {
            DB::beginTransaction();

            $task = Task::findOrFail($id);

            $task->update([
                'volunteer_id' => $validatedData['volunteer_id'],
                'task_name' => $validatedData['task_name'],
                'description' => $validatedData['description'],
                'estimated_time' => $validatedData['estimated_time'],
                'status_id' => $validatedData['status_id'],
            ]);

            DB::commit();

            return redirect()->route('tasks.index')->with([
                'message' => 'Task updated successfully!',
                'status' => 'success',
            ]);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return redirect()->back()->with([
                'message' => 'Task not found.',
                'status' => 'error',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            // Log the error if necessary
            // Log::error($e->getMessage());

            return back()->with([
                'message' => 'Something went wrong. Please try again.',
                'status' => 'error',
            ]);
        }
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $task = Task::findOrFail($id);

            // Optionally, handle related task logs if necessary
            // For example, you might want to delete task logs or handle them differently

            $task->delete();

            DB::commit();

            return redirect()->route('tasks.index')->with([
                'message' => 'Task deleted successfully!',
                'status' => 'success',
            ]);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return redirect()->back()->with([
                'message' => 'Task not found.',
                'status' => 'error',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            // Log the error if necessary
            // Log::error($e->getMessage());

            return back()->with([
                'message' => 'Something went wrong. Please try again.',
                'status' => 'error',
            ]);
        }
    }

    /**
     * Validate the task data.
     *
     * @param Request $request
     * @param int|null $id
     * @return array
     */
    private function validateTask(Request $request, $id = null): array
    {
        $rules = [
            'volunteer_id' => 'required|exists:volunteers,id',
            'task_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'estimated_time' => 'nullable|integer|min:0',
            'status_id' => 'required|exists:task_statuses,id',
        ];

        $messages = [
            'volunteer_id.required' => 'The volunteer field is required.',
            'volunteer_id.exists' => 'The selected volunteer does not exist.',
            'task_name.required' => 'The task name is required.',
            'task_name.max' => 'The task name may not be greater than 255 characters.',
            'description.max' => 'The description may not be greater than 1000 characters.',
            'estimated_time.integer' => 'The estimated time must be an integer.',
            'estimated_time.min' => 'The estimated time must be at least 0.',
            'status_id.required' => 'The status field is required.',
            'status_id.exists' => 'The selected status does not exist.',
        ];

        return $request->validate($rules, $messages);
    }

    /**
     * Retrieve tasks based on filters.
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getTasks(Request $request)
    {
        $query = Task::with(['volunteer', 'status']);

        if ($search = $request->input('search')) {
            $query->where('task_name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
        }

        if ($status = $request->input('status')) {
            $query->where('status_id', $status);
        }

        if ($volunteer = $request->input('volunteer')) {
            $query->where('volunteer_id', $volunteer);
        }

        return $query->get();
    }

    /**
     * Retrieve task status options for dropdowns.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getTaskStatusOptions()
    {
        return TaskStatus::whereNull('deleted_at')->get()->map(function ($status) {
            return [
                'id' => $status->id,
                'name' => $status->name,
                'hex_color' => $status->hex_color,
            ];
        });
    }

    /**
     * Retrieve volunteer options for dropdowns.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getVolunteerOptions()
    {
        return Volunteer::where('deleted', false)
        ->whereHas('status', function ($query) {
            $query->where('is_active', true);
        })
        ->get()
        ->map(fn($volunteer) => [
            'id' => $volunteer->id,
            'name' => "{$volunteer->firstname} {$volunteer->lastname}",
        ]);
    }

    /**
     * Retrieve email templates for dropdowns.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getEmailTemplates()
    {
        return EmailTemplate::select('id', 'name')->get()->map(function ($template) {
            return [
                'id' => $template->id,
                'label' => $template->name,
            ];
        });
    }

    private function validateTaskLog(Request $request)
    {
        $rules = [
            'task_id' => 'required|exists:tasks,id',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time',
            'duration' => 'required|integer|min:0',
        ];

        return Validator::make($request->all(), $rules);
    }

    public function updateTaskLog(Request $request)
    {
        //$validator = $this->validateTaskLog($request);


        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }

        // Create the TaskLog record
        $taskLog = TaskLog::create([
            'task_id' => $request->task_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration' => $request->duration,
        ]);

        // Update the associated Task's actual_time
        $task = Task::findOrFail($request->task_id);

        $totalDuration = $task->taskLogs()->sum('duration');

        $task->actual_time = $totalDuration;
        $task->save();

        // Redirect back to the specific task's show page with a success message
        return redirect()->route('tasks.show', $task->id)
            ->with('message', 'Task Log created successfully!');

    }
}
