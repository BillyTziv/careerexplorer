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
            'tagDropdownOptions' => $this->getTagDropdownOptions(),
            'categoryDropdownOptions' => $this->getCategoryOptions(),
            'priorityDropdownOptions' => $this->getPriorityOptions(),
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
        // Validate the task data
        $validatedData = $this->validateTask($request);

        // find the team id the volunteer belongs to
        try {
            $volunteer = Volunteer::findOrFail($validatedData['volunteer_id']);
            $teamId = $volunteer->team_id;
        } catch (ModelNotFoundException $e) {
            dd($e->getMessage());

            return redirect()->back()->with([
                'message' => 'Volunteer not found.',
                'status' => 'error',
            ]);
        }

        try {
            DB::beginTransaction();

            // convert the due_date to the correct format
            //$dueDate = Carbon::createFromFormat('d/m/Y', $request->input('due_date'))->format('Y-m-d');

            $task = Task::create([
                'task_name' => $validatedData['task_name'],
                'description' => $validatedData['description'] ?? null,
                'due_date' => $request->input('due_date') ?? null,
                'estimated_time' => $validatedData['estimated_time'] ?? null,
                'points' => $validatedData['points'] ?? 0,
                'priority' => $validatedData['priority'] ?? 0,
                'category_id' => $validatedData['category_id'] ?? 1,
                'status_id' => $validatedData['status_id'],
                'volunteer_id' => $validatedData['volunteer_id'],
                'team_id' => Volunteer::findOrFail($validatedData['volunteer_id'])->team_id ?? 1,
            ]);

            DB::commit();

            return redirect()->route('tasks.index')->with([
                'message' => 'Task created successfully!',
                'status' => 'success',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            dd($e->getMessage());

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
            'taskStatusDropdownOptions' => $this->getTaskStatusOptions()
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

    public function updateStatus(Request $request, $taskId) {
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
        $task = Task::findOrFail($taskId);
        $oldStatus = $task->status_id;
        $task->status_id = $request->newStatusValue;
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

            //dd($validatedData['due_date']);

            // Convert due_date from DD/MM/YYYY to YYYY-MM-DD
            //$dueDate = Carbon::createFromFormat('d/m/Y', $validatedData['due_date'])->format('Y-m-d');

            $task->update([
                'volunteer_id' => $validatedData['volunteer_id'],
                'task_name' => $validatedData['task_name'],
                'description' => $validatedData['description'],
                'estimated_time' => $validatedData['estimated_time'],
                'points' => $validatedData['points'],
                'priority' => $validatedData['priority'] ?? 1,
                'due_date' => $validatedData['due_date'],
                'category_id' => $validatedData['category_id'] ?? 1,
                'status_id' => $validatedData['status_id'] ?? 1,
            ]);

            DB::commit();

            return redirect()->route('tasks.index')->with([
                'message' => 'Task updated successfully!',
                'status' => 'success',
            ]);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            dd($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Task not found.',
                'status' => 'error',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            dd($e->getMessage());
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
            'due_date' => 'nullable',                                           // Validate due_date as a date (nullable)
            'estimated_time' => 'nullable|integer|min:0',
            'points' => 'nullable|integer|min:0',                               // Validate points as an integer (nullable)
            'priority' => 'required|in:0,1,2',                               // Validate  as existing in the priorities table
            'status_id' => 'required|exists:task_statuses,id',
        ];

        $messages = [
            'volunteer_id.required' => 'Η ανάθεση σε εθελοντή είναι υποχρεωτική.',
            'volunteer_id.exists' => 'Ο εθελοντής δεν υπάρχει.',
            'task_name.required' => 'Ο τίτλος της εργασίας είναι υποχρεωτικός.',
            'task_name.max' => 'The task name may not be greater than 255 characters.',
            'description.max' => 'The description may not be greater than 1000 characters.',
            'due_date.date' => 'The due date must be a valid date.',
            'estimated_time.integer' => 'The estimated time must be an integer.',
            'estimated_time.min' => 'The estimated time must be at least 0.',
            'points.integer' => 'The points must be an integer.',
            'points.min' => 'The points must be at least 0.',
            'priority.required' => 'The priority field is required.',
            'priority.exists' => 'The selected priority does not exist.',
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
            $query->where('task_name', 'LIKE', "%{$search}%");
        }

        if ($status = $request->input('status')) {
            $query->where('status_id', $status);
        }

        if ($volunteer = $request->input('volunteer')) {
            $query->where('volunteer_id', $volunteer);
        }

        if($priority = $request->input('priority')) {
            $query->where('priority', $priority);
        }

        if( !Auth::user()->secured ) {

            // find associated volunteer team
            $assoc_volunteer = Volunteer::where('assigned_to', Auth::user()->id);

            if($assoc_volunteer->count() !== 0) {
                $query->where('team_id', $assoc_volunteer->team_id);
            }
        }

        $query->leftJoin('volunteers', 'volunteers.id', '=', 'tasks.volunteer_id')
            ->select(
                'tasks.id as task_id',
                'tasks.task_name',
                'volunteers.firstname as volunteer_fname',
                'volunteers.lastname as volunteer_lname',
                'tasks.status_id',
                'tasks.due_date',
                'tasks.priority',
                'tasks.estimated_time',
                'tasks.actual_time as total_time',
                'tasks.created_at',
            );

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
     * Retrieve tag options for dropdowns.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getTagDropdownOptions()
    {
        return collect([
            ['id' => 1, 'name' => 'Tag 1'],
            ['id' => 2, 'name' => 'Tag 2'],
            ['id' => 3, 'name' => 'Tag 3'],
        ]);
    }

    /**
     * Retrieve category options for dropdowns.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getCategoryOptions()
    {
        return collect([
            ['id' => 1, 'name' => 'Category 1'],
            ['id' => 2, 'name' => 'Category 2'],
            ['id' => 3, 'name' => 'Category 3'],
        ]);
    }

    /**
     * Retrieve priority options for dropdowns.
     *
     * @return \Illuminate\Support\Collection
     */

    private function getPriorityOptions()
    {
        return collect([
            ['id' => 0, 'name' => 'Χαμηλή'],
            ['id' => 1, 'name' => 'Μεσαία'],
            ['id' => 2, 'name' => 'Υψηλή'],
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
        $task->status_id = 2; // In Progress status
        $task->actual_time = $totalDuration;
        $task->save();

        // Redirect back to the specific task's show page with a success message
        return redirect()->route('tasks.show', $task->id)
            ->with('message', 'Task Log created successfully!');

    }

    public function completeTask(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $task->status_id = 3; // Done status
        $task->save();

        return redirect()->back();
    }
}
