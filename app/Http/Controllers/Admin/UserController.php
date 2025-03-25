<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Models\Country;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{

    public function search(Request $request)
    {
        $search = $request->input('search');
    
        // Search users by mobile or name
        $users = User::where('name', 'like', "%{$search}%")
                    ->get();
    
        // Return JSON response for Select2
        return response()->json($users);
    }

    public function searchBrothers(Request $request)
    {
        $query = $request->get('query');

        $brothers = User::where('name', 'LIKE', '%' . $query . '%')
            ->orWhere('phone', 'LIKE', '%' . $query . '%')
            ->get(['id', 'name', 'phone', 'family_id']);

        return response()->json($brothers);
    }

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where(\DB::raw('CONCAT_WS(" ", `name`, `email`, `phone`)'), 'like', '%' . $request->search . '%');
            });
        }

        $data = $query->paginate(PAGINATION_COUNT);

        $searchQuery = $request->search;

        return view('admin.users.index', compact('data', 'searchQuery'));
    }


    public function create()
    {
        $countries = Country::all();
        return view('admin.users.create',compact('countries'));
    }

    public function export(Request $request)
    {
        return Excel::download(new UsersExport($request->search), 'users.xlsx');
    }

    public function store(Request $request)
    {
        try {
            $user = new User();
            $user->name = $request->get('name');
            $user->phone = $request->get('phone');
            $user->address = $request->get('address');
            $user->date_of_birth = $request->get('date_of_birth');
            $user->date_of_passport_end = $request->get('date_of_passport_end');
            $user->person_or_company = $request->get('person_or_company');
            $user->company_id = $request->get('company');

            // Assign or create a family_id
            if ($request->has('family_id') && count($request->get('family_id')) > 0) {
                // If brothers are selected, use the family_id of the first selected brother
                $existingFamilyId = User::find($request->get('family_id')[0])->family_id;
                $user->family_id = $existingFamilyId ? $existingFamilyId : User::max('family_id') + 1;
            } else {
                // No brothers selected; create a new family_id
                $user->family_id = User::max('family_id') + 1;
            }


            if ($request->activate) {
                $user->activate = $request->get('activate');
            }

        

            if ($request->has('photo_of_passport')) {
                $the_file_path = uploadImage('assets/admin/uploads', $request->photo_of_passport);
                $user->photo_of_passport = $the_file_path;
            }

           
            if ($user->save()) {
                // Update selected brothers with the same family_id
                if ($request->has('family_id') && count($request->get('family_id')) > 0) {
                    User::whereIn('id', $request->get('family_id'))->update(['family_id' => $user->family_id]);
                }

                return redirect()->route('users.index')->with(['success' => 'Customer created']);
            } else {
                return redirect()->back()->with(['error' => 'Something wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                ->withInput();
        }
    }


    public function show($id)
    {
        $user = User::with('countries')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }


    public function edit($id)
    {
        if (auth()->user()->can('customer-edit')) {
            $data = User::findorFail($id);
            $currentBrothers = User::where('family_id', $data->family_id)
            ->where('id', '!=', $data->id) // Exclude the current user
            ->get();
           
        
            return view('admin.users.edit', compact('data','currentBrothers',));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findorFail($id);
        try {

            $user->name = $request->get('name');

            $user->email = $request->get('email');
            $user->phone = $request->get('phone');
            $user->address = $request->get('address');
            $user->date_of_birth = $request->get('date_of_birth');
            $user->date_of_passport_end = $request->get('date_of_passport_end');
            $user->person_or_company = $request->get('person_or_company');
            $user->company_id = $request->get('company');

            if ($request->activate) {
                $user->activate = $request->get('activate');
            }

            
            // Determine the family_id to set based on the selected brothers
            if ($request->has('family_id') && count($request->get('family_id')) > 0) {
                // Use the family_id of the first selected brother if they exist
                $existingFamilyId = User::find($request->get('family_id')[0])->family_id;
                $user->family_id = $existingFamilyId ? $existingFamilyId : User::max('family_id') + 1;
            } else {
                // If no brothers are selected, keep the user's current family_id
                $user->family_id = $user->family_id ?: User::max('family_id') + 1;
            }

           

            if ($request->has('photo_of_passport')) {
                // Delete the old photo_of_passport from the file system
                $filePath = base_path('assets/admin/uploads/' . $user->photo_of_passport);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }

                // Upload the new image
                $the_file_path = uploadImage('assets/admin/uploads', $request->photo_of_passport);
                $user->photo_of_passport = $the_file_path;
            }

            if ($user->save()) {
                // Update all selected brothers to have the same family_id as the updated student
                if ($request->has('family_id') && count($request->get('family_id')) > 0) {
                    User::whereIn('id', $request->get('family_id'))->update(['family_id' => $user->family_id]);
                }

                
                return redirect()->route('users.index')->with(['success' => 'user update']);
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
