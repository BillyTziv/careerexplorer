<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
                'status' => 'error',
            ]);
        }

        return Inertia::render('Tasks/Show', [
            'task' => $task,
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

        return $query->paginate(15)->withQueryString();
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
        return Volunteer::where('deleted', false)->get()->map(function ($volunteer) {
            return [
                'id' => $volunteer->id,
                'name' => "{$volunteer->firstname} {$volunteer->lastname}",
            ];
        });
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
}
