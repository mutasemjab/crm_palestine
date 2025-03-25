<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Banner;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function eachEmployeeNotification()
    {
        // Get the authenticated admin
        $admin = auth()->user();

        // Check if the admin is a super admin
        if ($admin->is_super == 1) {
            // Show all notifications for super admin
            $notifications = Notification::get();
        } else {
            // Show notifications where admin_id matches the logged-in admin
            $notifications = Notification::where('admin_id', $admin->id)
            ->orWhere('admin_id', null)
            ->get();
        
        }

        // Return the view with notifications data
        return view('admin.notifications.employee', compact('notifications'));
    }

    public function create()
    {
        $employees = Admin::get();
        return view('admin.notifications.create',compact('employees'));

    }

    public function send(Request $request){

        $this->validate($request,[
            'title'=>'required',
            'body'=>'required'
        ]);

            // Save the notification
            $noti = new Notification([
                'title' => $request->title,
                'body' => $request->body,
                'admin_id' => $request->admin_id ?? null,
            ]);


        if($noti->save()){
            return redirect()->back()->with('success', 'Notification sent');
        }else{
            return redirect()->back()->with('error', 'Notification was not sent');
        }
    }
}
