<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Country;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{

    public function index(Request $request)
    {
        $data = Admin::with('roles')->where('is_super', 0);
        if ($request->search != '' ||  $request->search) {
            $data->where(function ($query) use ($request) {
                $query->where('admins.name', 'LIKE', "%$request->search%")
                    ->orWhere('admins.email',  'LIKE', "%$request->search%")
                    ->orWhere('admins.mobile',  'LIKE', "%$request->search%");
            });
        }
        $data = $data->paginate(10);
        return view('admin.employee.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (auth()->user()->can('employee-add')) {
            $roles = Role::get();
            $countries = Country::get();
            return view('admin.employee.create', compact('roles','countries'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function store(Request $request)
    {

            $request->validate([
                'name' => 'required|string|max:255',
                'password' => 'required|string|min:6',
                'roles' => 'required|array',
                'roles.*' => 'exists:roles,id',
                'phone' => 'nullable|string|max:15',
                'address' => 'nullable|string|max:500',
                'activate' => 'required|in:1,2',
                'is_constructor' => 'nullable|boolean',
                'country_id' => 'nullable|exists:countries,id',
            ]);

          //  DB::beginTransaction();

                // Create the admin
                $admin = Admin::create([
                    'name' => $request->name,
                    'phone' => $request->phone ?? null,
                    'address' => $request->address ?? null,
                    'password' => Hash::make($request->password),
                    'activate' => $request->activate,
                    'is_constructor' => $request->is_constructor ??0,
                    'country_id' => $request->country_id,
                ]);

                // Assign roles to the admin
                $admin->roles()->sync($request->roles);

               // DB::commit();

                return redirect()->route('admin.employee.index')
                    ->with('success', __('messages.Employee created successfully'));


    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (auth()->user()->can('employee-delete')) {
            DB::beginTransaction();
            try {
                Admin::find($id)->delete();
                DB::table('model_has_roles')->where('model_type', 'App\Models\admin')->where('model_id', $id)->delete();
                DB::commit();
                return redirect()->route('admin.employee.index')
                    ->with('success', 'Admin deleted successfully');
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->route('admin.employee.index')
                    ->with('error', 'Something Error');
            }
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }


    public function edit($id)
    {
        if (auth()->user()->can('employee-edit')) {
            $employee = Admin::findOrFail($id); // Use findOrFail for better error handling
            $roles = Role::all();
            $adminRole = $employee->roles->pluck('id')->toArray(); // Ensure it returns an array
            $countries = Country::all(); // Include countries for the dropdown
            return view('admin.employee.edit', compact('employee', 'roles', 'adminRole', 'countries'));
        } else {
            return redirect()->back()
                ->with('error', __('messages.Access Denied'));
        }
    }


    public function update(Request $request, $id)
    {
        if (auth()->user()->can('employee-edit')) {
            $request->validate([
                'name' => 'required|string|max:255',
                'roles' => 'required|array',
                'roles.*' => 'exists:roles,id',
                'phone' => 'nullable|string|max:15',
                'address' => 'nullable|string|max:500',
                'activate' => 'required|in:1,2',
                'is_constructor' => 'nullable|boolean',
                'country_id' => 'nullable|exists:countries,id',
                'password' => 'nullable|string|min:6',
            ]);

            DB::beginTransaction();
            try {
                $admin = Admin::findOrFail($id);

                // Update admin details
                $admin->update([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'activate' => $request->activate,
                    'is_constructor' => $request->is_constructor??0,
                    'country_id' => $request->country_id,
                ]);

                // Update password only if provided
                if ($request->password) {
                    $admin->update(['password' => Hash::make($request->password)]);
                }

                // Sync roles with the admin
                $admin->roles()->sync($request->roles);

                DB::commit();

                return redirect()->route('admin.employee.index')
                    ->with('success', __('messages.Employee updated successfully'));
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error updating employee', ['message' => $e->getMessage()]);
                return redirect()->route('admin.employee.index')
                    ->with('error', __('messages.Something went wrong'));
            }
        } else {
            return redirect()->back()
                ->with('error', __('messages.Access Denied'));
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Admin::find($id)->delete();
            DB::table('model_has_roles')->where('model_type', 'App\Models\admin')->where('model_id', $id)->delete();
            DB::commit();
            return redirect()->route('admins.index')
                ->with('success', 'Admin deleted successfully');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('admins.index')
                ->with('error', 'Something Error');
        }
    }
}
