<?php

namespace App\Http\Controllers\Volunteer;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate\EmailTemplate;
use App\Models\Volunteer\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class TaskStatusController extends Controller
{
    /**
     * Display a listing of the task statuses.
     */
    public function index(): Response
    {
        $taskStatuses = $this->getTaskStatuses();

        return Inertia::render('Tasks/Statuses/Index', [
            'taskStatuses' => $taskStatuses,
            'taskStatusDropdownOptions' => $taskStatuses->where('deleted_at', null),
        ]);
    }

    /**
     * Fetch task statuses for JSON responses.
     */
    public function getStatuses()
    {
        $statuses = TaskStatus::whereNull('deleted_at')->get();

        return response()->json($statuses);
    }

    /**
     * Show the form for creating a new task status.
     */
    public function create(): Response
    {
        return Inertia::render('Tasks/Statuses/CreateEdit', [
            'taskStatus' => [],
            'emailTemplates' => $this->getEmailTemplates(),
        ]);
    }

    /**
     * Store a newly created task status in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->validateTaskStatus($request);

        try {
            DB::beginTransaction();

            // If the new status is set as default, unset the current default
            if ($validatedData['is_default']) {
                TaskStatus::where('is_default', true)->update(['is_default' => false]);
            }

            // Create the new task status
            TaskStatus::create([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'hex_color' => $validatedData['hex_color'],
                'is_default' => $validatedData['is_default'],
                'email_template_id' => $validatedData['email_template_id'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('volunteer.task-statuses.index')->with([
                'message' => 'Η κατάσταση εργασίας δημιουργήθηκε με επιτυχία!',
                'status' => 'success',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with([
                'message' => 'Ουπς, κάτι πήγε στραβά. Παρακαλούμε προσπαθήστε ξανά.',
                'status' => 'error',
            ]);
        }
    }

    /**
     * Show the form for editing the specified task status.
     */
    public function edit($id): Response
    {
        try {
            $taskStatus = TaskStatus::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with([
                'message' => 'Ουπς, η κατάσταση εργασίας δεν βρέθηκε.',
                'status' => 'error',
            ]);
        }

        return Inertia::render('Tasks/Statuses/CreateEdit', [
            'taskStatus' => $taskStatus,
            'emailTemplates' => $this->getEmailTemplates(),
        ]);
    }

    /**
     * Update the specified task status in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $this->validateTaskStatus($request, $id);

        try {
            DB::beginTransaction();

            $taskStatus = TaskStatus::findOrFail($id);

            // If the updated status is set as default, unset the current default
            if ($validatedData['is_default']) {
                TaskStatus::where('is_default', true)->where('id', '!=', $id)->update(['is_default' => false]);
            }

            // Update the task status
            $taskStatus->update([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'hex_color' => $validatedData['hex_color'],
                'is_default' => $validatedData['is_default'],
                'email_template_id' => $validatedData['email_template_id'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('volunteer.task-statuses.index')->with([
                'message' => 'Η κατάσταση εργασίας ενημερώθηκε με επιτυχία.',
                'status' => 'success',
            ]);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return redirect()->back()->with([
                'message' => 'Ουπς, η κατάσταση εργασίας δεν βρέθηκε.',
                'status' => 'error',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with([
                'message' => 'Ουπς, κάτι πήγε στραβά. Παρακαλούμε προσπαθήστε ξανά.',
                'status' => 'error',
            ]);
        }
    }

    /**
     * Remove the specified task status from storage (soft delete).
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $taskStatus = TaskStatus::findOrFail($id);
            $taskStatus->delete();

            DB::commit();

            return redirect()->route('volunteer.task-statuses.index')->with([
                'message' => 'Η κατάσταση εργασίας διαγράφηκε με επιτυχία!',
                'status' => 'success',
            ]);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return redirect()->back()->with([
                'message' => 'Ουπς, η κατάσταση εργασίας δεν βρέθηκε.',
                'status' => 'error',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with([
                'message' => 'Ουπς, κάτι πήγε στραβά. Παρακαλούμε προσπαθήστε ξανά.',
                'status' => 'error',
            ]);
        }
    }

    /**
     * Validate the task status data.
     *
     * @param Request $request
     * @param int|null $id
     * @return array
     */
    private function validateTaskStatus(Request $request, $id = null): array
    {
        $rules = [
            'name' => 'required|string|max:255|unique:task_statuses,name' . ($id ? ",$id" : ''),
            'description' => 'nullable|string|max:1000',
            'hex_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'is_default' => 'required|boolean',
            'email_template_id' => 'nullable|exists:email_templates,id',
        ];

        $messages = [
            'name.required' => 'Το όνομα είναι υποχρεωτικό.',
            'name.unique' => 'Το όνομα πρέπει να είναι μοναδικό.',
            'description.max' => 'Η περιγραφή δεν πρέπει να υπερβαίνει τα 1000 χαρακτήρες.',
            'hex_color.required' => 'Το χρώμα είναι υποχρεωτικό.',
            'hex_color.regex' => 'Το χρώμα πρέπει να είναι σε μορφή hexadecimal (π.χ., #FFFFFF).',
            'is_default.required' => 'Η προεπιλεγμένη κατάσταση είναι υποχρεωτική.',
            'is_default.boolean' => 'Η προεπιλεγμένη κατάσταση πρέπει να είναι αληθής ή ψευδής.',
            'email_template_id.exists' => 'Το επιλεγμένο πρότυπο email δεν υπάρχει.',
        ];

        return $request->validate($rules, $messages);
    }

    /**
     * Retrieve all task statuses excluding soft-deleted ones.
     */
    private function getTaskStatuses()
    {
        return TaskStatus::whereNull('deleted_at')->get();
    }

    /**
     * Retrieve email templates for dropdown selections.
     */
    private function getEmailTemplates()
    {
        return EmailTemplate::select('id', 'name')->get()->map(function ($item) {
            return ['id' => $item->id, 'label' => $item->name];
        });
    }
}
