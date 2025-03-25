<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Type;

class TypeController extends Controller
{

    public function search(Request $request)
    {
        $search = $request->input('search');
    
        // Search users by mobile or name
        $users = Type::where('name', 'like', "%{$search}%")
                    ->get();
    
        // Return JSON response for Select2
        return response()->json($users);
    }

    public function index(Request $request)
    {
        $query = Type::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where(\DB::raw('CONCAT_WS(" ", `name`)'), 'like', '%' . $request->search . '%');
            });
        }

        $data = $query->paginate(PAGINATION_COUNT);

        $searchQuery = $request->search;

        return view('admin.types.index', compact('data', 'searchQuery'));
    }


    public function create()
    {
        if (auth()->user()->can('type-add')) {
            return view('admin.types.create');
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }


    public function store(Request $request)
    {
        try {
            $type = new Type();
            $type->name = $request->get('name');
           
            if ($type->save()) {
            
                return redirect()->route('types.index')->with(['success' => 'type created']);
            } else {
                return redirect()->back()->with(['error' => 'Something wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                ->withInput();
        }
    }


    


    public function edit($id)
    {
        if (auth()->user()->can('type-edit')) {
            $data = Type::findorFail($id);
       
            return view('admin.types.edit', compact('data'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function update(Request $request, $id)
    {
        $type = Type::findorFail($id);
        try {

            $type->name = $request->get('name');
           

            if ($type->save()) {
        
                return redirect()->route('types.index')->with(['success' => 'type update']);
            } else {
                return redirect()->back()->with(['error' => 'Something wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $type = Type::findOrFail($id);
        $type->delete();

        return redirect()->route('types.index')->with(['success' => 'type Delete']);
    }

}
