<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function show_login_view()
    {
      return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        if (auth()->guard('admin')->attempt(['phone' => $request->input('phone'), 'password' => $request->input('password')])) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('admin.showlogin')->withErrors(['phone' => 'رقم الهاتف أو كلمة المرور غير صحيحة']);
        }
    }


    public function logout()
    {
      auth()->logout();
      return redirect()->route('admin.showlogin');
    }


    public function editlogin($id)
    {
      $data = Admin::findorFail($id);
      return view('admin.auth.edit', compact('data'));
    }



    public function updatelogin(Request $request, $id)
    {
      $admin = Admin::findorFail($id);
      try {
        $admin->username = $request->get('username');
        $admin->password = Hash::make($request->password);

        if ($admin->save()) {
          auth()->logout();
          return redirect()->route('admin.showlogin');
        } else {
          return redirect()->back()->with(['error' => 'Something wrong']);
        }
      } catch (\Exception $ex) {
        return redirect()->back()
          ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
          ->withInput();
      }
    }



}
