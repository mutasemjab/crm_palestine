<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Booking;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskFinancialReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::query();
        $admins = Admin::get();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('admin')) {
            $query->where('admin_id', $request->admin);
        }

        $tasks = $query->with(['jobOrderType', 'admin', 'feedback', 'financial'])->get();

        return view('reports.tasks', compact('tasks','admins'));
    }


}
