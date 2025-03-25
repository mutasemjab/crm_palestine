<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Country;
use App\Models\JobOrderType;
use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth()->user();

        // Fetch filters from the request
        $countryId = $request->input('country');
        $employeeId = $request->input('employee');
        $jobOrderTypeId = $request->input('jobOrderType');
        $jobOrderStatus = $request->input('jobOrderStatus'); // New filter
        $search = $request->input('search'); // Search filter
        // Fetch all job order types and countries for dropdowns
        $jobOrderTypes = JobOrderType::all();
        $countries = Country::all();
        $employees = Admin::all();
        $statuses = ['opened', 'بحاجة لكشف مهندس', 'تأجيل بنفس اليوم', '، تأجيل ليوم اخر', 'بحاجة عرض سعر', 'الغاء المعاملة', 'بحاجة لاعادة تخطيط', 'delivered', 'rejected']; // Define statuses
        // Get the current date
        $today = Carbon::today();



        if ($admin->is_super || $admin->is_constructor == 0) {
            // Fetch all tasks for super admin with optional filters
            $tasks = Task::with('creator', 'feedback', 'jobOrderType', 'admin')
                ->when($countryId, function ($query, $countryId) {
                    $query->whereHas('admin', function ($subQuery) use ($countryId) {
                        $subQuery->where('country_id', $countryId);
                    });
                })
                ->when($employeeId, function ($query, $employeeId) {
                    $query->where('admin_id', $employeeId);
                })
                ->when($jobOrderTypeId, function ($query, $jobOrderTypeId) {
                    $query->where('job_order_type_id', $jobOrderTypeId);
                })
                ->when($jobOrderStatus, function ($query, $jobOrderStatus) {
                    $query->where('job_order_status', $jobOrderStatus);
                })
                ->when($search, function ($query, $search) {
                    $query->where(function ($subQuery) use ($search) {
                        $subQuery->where('id', 'LIKE', "%{$search}%")
                            ->orWhere('customer_name', 'LIKE', "%{$search}%")
                            ->orWhere('job_order_status', 'LIKE', "%{$search}%")
                            ->orWhereHas('jobOrderType', function ($q) use ($search) {
                                $q->where('name', 'LIKE', "%{$search}%");
                            });
                    });
                })
                ->where(function ($query) use ($today) {
                    $query->where('date_of_task', $today) // Today's tasks
                        ->orWhere(function ($subQuery) {
                            $subQuery->where('job_order_status', '<>', 'completed') // Not completed
                                ->whereDoesntHave('feedback'); // No feedback
                        });
                })
                ->get();
            return view('admin.dashboard', compact('tasks', 'jobOrderTypes', 'admin', 'countries', 'employees', 'statuses'));
        } else {

            $fromDate = $request->input('from_date', $today->toDateString());
            $toDate = $request->input('to_date', $today->toDateString());
            // Query tasks that fall between 'from_date' and 'to_date'
            $tasks = Task::whereBetween('date_of_task', [$fromDate, $toDate])->get();
            // Fetch tasks assigned to the logged-in admin with optional filtering
            $tasks = Task::with('creator', 'feedback', 'jobOrderType')
                ->where('admin_id', $admin->id)
                ->whereBetween('date_of_task', [$fromDate, $toDate])
                ->when($jobOrderTypeId, function ($query, $jobOrderTypeId) {
                    $query->where('job_order_type_id', $jobOrderTypeId);
                })
                ->get();

            return view('admin.dashboard_employee', compact('tasks', 'jobOrderTypes', 'admin', 'countries', 'employees', 'statuses', 'fromDate', 'toDate'));
        }
    }





    public function addNote(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        // Update task note
        $task->note = $request->input('note');
        $task->save();

        return redirect()->back()->with('success', __('messages.Note added successfully'));
    }
}
