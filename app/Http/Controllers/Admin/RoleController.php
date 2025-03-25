<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Gate;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Gate::allows('role-table')) {
            abort(403, 'Unauthorized');
        }

        $data = Role::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('guard_name', 'LIKE', "%{$search}%");
            })
            ->paginate(10);

        return view('admin.roles.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = Permission::where('guard_name', 'admin')->get();
        return view('admin.roles.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'admin',
            ]);

            $role->syncPermissions($request->permissions);

            DB::commit();
            return redirect()->route('admin.role.index')->with('success', trans('messages.success'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => trans('messages.error')])->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $permissions = Permission::where('guard_name', 'admin')->get();
        $role = Role::findOrFail($id);
        $role_permissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('permissions', 'role_permissions', 'role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => "required|unique:roles,name,{$id}",
            'permissions' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::findOrFail($id);
            $role->update([
                'name' => $request->name,
                'guard_name' => 'admin',
            ]);

            $role->syncPermissions($request->permissions);

            DB::commit();
            return redirect()->route('admin.role.index')->with('success', trans('messages.success'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => trans('messages.error')])->withInput();
        }
    }

    public function destroy($id)
    {
        if (auth()->user()->can('role-delete')) {
            DB::beginTransaction();
            try {
                // Find the role
                $role = Role::findOrFail($id);

                // Delete entries from model_has_roles
                DB::table('model_has_roles')
                    ->where('role_id', $role->id)
                    ->delete();

                // Delete the role
                $role->delete();

                DB::commit();
                return redirect()->route('admin.role.index')
                    ->with('success', 'Role deleted successfully');
            } catch (Exception $e) {
                DB::rollBack();
                Log::error('Error deleting role', ['message' => $e->getMessage()]);
                return redirect()->route('admin.role.index')
                    ->with('error', 'Something went wrong while deleting the role');
            }
        } else {
            return redirect()->back()
                ->with('error', 'Access Denied');
        }
    }
}
