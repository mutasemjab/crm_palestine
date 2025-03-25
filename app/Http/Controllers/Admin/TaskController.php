<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use App\Models\Admin;
use App\Models\JobOrderType;
use App\Models\CustomerServiceBundle;
use App\Models\ServiceType;
use App\Models\Service;
use App\Models\Lld;
use App\Models\Type;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TasksImport;
use App\Exports\FeedbackExport;
class TaskController extends Controller
{

    public function taskCompleted()
    {
        $tasks = Task::where('job_order_status', 'completed')->get();
        return view('admin.tasks.completed',compact('tasks'));
    }

    public function exportFeedback($id)
    {
        $task = Task::findOrFail($id);

        // Check if feedback exists
        if (!$task->feedback || !$task->feedback->data) {
            return back()->with('error', 'No feedback data available for this task');
        }

        // Generate filename based on task details
        $filename = 'task_' . $id . '_feedback_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new FeedbackExport($id), $filename);
    }

    public function taskApproval()
    {
        $statuses = ['بحاجة لكشف مهندس', 'بحاجة عرض سعر', 'بحاجة لاعادة تخطيط'];
        $tasks = Task::whereIn('job_order_status', $statuses)->where('return_to_contractor',1)->get();
        return view('admin.tasks.approval', compact('tasks'));
    }

    public function returnToContractor(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->return_to_contractor = null; // Set the value to null
        $task->save();

        return redirect()->back()->with('success', 'Task updated successfully.');
    }

    public function note_of_task_that_need_approve(Request $request, $id)
    {
        $request->validate([
            'note_of_task_that_need_approve' => 'required|string|max:500',
            'price_offer_from_engineer' => 'nullable',
        ]);

        $task = Task::findOrFail($id);
        $task->note_of_task_that_need_approve = $request->note_of_task_that_need_approve;
        $task->price_offer_from_engineer = $request->price_offer_from_engineer;
        $task->save();

        return redirect()->back()->with('success', 'Note added successfully.');
    }





    public function taskInDay()
    {
        $tasks = Task::where('job_order_status', 'تأجيل ليوم اخر')->get();
        return view('admin.tasks.inDay',compact('tasks'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_key' => 'required|string',
            'status_value' => 'required|string',
            'note_of_reject' => 'nullable|string|max:1000', // Validate rejection note
        ]);

        $task = Task::findOrFail($id);

        // Update status
        $task->{$request->status_key} = $request->status_value;

        // If status is rejected, update the rejection note
        if ($request->status_value === 'rejected') {
            $task->note_of_reject = $request->input('note_of_reject');
            if ($request->has('photo_of_reject')) {
                $the_file_path = uploadImage('assets/admin/uploads', $request->photo_of_reject);
                $task->photo_of_reject = $the_file_path;
             }

        }

        // Check if the selected status requires `return_to_contractor` to be updated
        $statusesRequiringReturnToContractor = ['بحاجة لكشف مهندس', 'بحاجة عرض سعر', 'بحاجة لاعادة تخطيط'];
        if (in_array($request->status_value, $statusesRequiringReturnToContractor)) {
            $task->return_to_contractor = 1;
        }

        if ($task->save()) {
            return response()->json(['success' => true, 'message' => __('Status updated successfully')]);
        }

        return response()->json(['success' => false, 'message' => __('Failed to update status')], 500);
    }



    public function updateTime(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->time = $request->input('time');
        $task->save();

        return response()->json(['message' => 'Time added successfully']);
    }

    public function updateDateTime(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->date_time = $request->input('dateTime');
        $task->save();

        return response()->json(['message' => 'Date and time added successfully']);
    }


    public function updateJobOrderType(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $request->validate([
            'job_order_type_id' => 'required|exists:job_order_types,id',
        ]);

        $task->job_order_type_id = $request->job_order_type_id;
        $task->save();

        return response()->json(['success' => true, 'message' => __('Job order type updated successfully.')]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new TasksImport, $request->file('file'));

        return back()->with('success', 'Tasks imported successfully.');
    }

    /**
     * Display a listing of tasks.
     */
    public function index()
    {
        $tasks = Task::paginate(PAGINATION_COUNT);
        $admins = Admin::all(); // Fetch all admins for the dropdown
        return view('admin.tasks.index', compact('tasks', 'admins'));
    }

    public function updateAdmin(Request $request, $id)
    {
        $request->validate([
            'admin_id' => 'required|exists:admins,id', // Validate the selected admin ID
        ]);

        $task = Task::findOrFail($id);
        $task->admin_id = $request->admin_id;
        $task->save();

        return redirect()->route('tasks.index')->with('success', __('messages.Admin updated successfully.'));
    }


    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        $admins = Admin::all();
        $jobOrderTypes = JobOrderType::all();
        $types = Type::all();

        return view('admin.tasks.create', compact(
            'admins',
            'jobOrderTypes',
            'types'
        ));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'building' => 'nullable|string',
            'district' => 'nullable|string',
            'area' => 'nullable|string',
            'assigned_time' => 'nullable',
            'customer_name' => 'nullable|string',
            'customer_username' => 'nullable|string',
            'customer_mobile' => 'nullable|string',
            'customer_address' => 'nullable|string',
            'splitting' => 'nullable|string',
            'sspl_no_planned' => 'nullable|integer',
            'rspl_no' => 'nullable|integer',
            'through' => 'nullable|string',
            'core_color' => 'nullable|string',
            'note' => 'nullable|string',
            'job_order_status' => 'required|in:none,opened,pending,in_progress,completed',
            'contractor_status' => 'required|in:none,opened,pending,in_progress,completed',
            'customer_service_status' => 'required|in:installation,availability',
            'postal_code_status' => 'required|in:active,planned',
            'admin_id' => 'nullable|exists:admins,id',
            'created_by' => 'nullable|exists:admins,id',
            'job_order_type_id' => 'required|exists:job_order_types,id',
            'type_id' => 'required|exists:types,id',
            'date_of_task' => 'required|date',
        ]);

        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    /**
     * Display the specified task.
     */
    public function show($id)
    {
        $task = Task::findOrFail($id);
        return view('admin.tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit($id)
    {
        $task = Task::findOrFail($id);

        $admins = Admin::all();
        $jobOrderTypes = JobOrderType::all();
        $types = Type::all();

        return view('admin.tasks.edit', compact(
            'task',
            'admins',
            'jobOrderTypes',
            'types'
        ));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'building' => 'nullable|string',
            'district' => 'nullable|string',
            'area' => 'nullable|string',
            'assigned_time' => 'nullable',
            'customer_name' => 'nullable|string',
            'customer_username' => 'nullable|string',
            'customer_mobile' => 'nullable|string',
            'customer_address' => 'nullable|string',
            'splitting' => 'nullable|string',
            'sspl_no_planned' => 'nullable|integer',
            'rspl_no' => 'nullable|integer',
            'through' => 'nullable|string',
            'core_color' => 'nullable|string',
            'note' => 'nullable|string',
            'job_order_status' => 'required|in:none,opened,pending,in_progress,completed',
            'contractor_status' => 'required|in:none,opened,pending,in_progress,completed',
            'customer_service_status' => 'required|in:installation,availability',
            'postal_code_status' => 'required|in:active,planned',
            'admin_id' => 'required|exists:admins,id',
            'created_by' => 'nullable|exists:admins,id',
            'job_order_type_id' => 'required|exists:job_order_types,id',
            'type_id' => 'required|exists:types,id',
            'date_of_task' => 'required|date',
        ]);

        $task = Task::findOrFail($id);
        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }
}
