<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;


class UnitController extends Controller
{

    public function index()
    {

        $data = Unit::paginate(PAGINATION_COUNT);

        return view('admin.units.index', ['data' => $data]);
    }

    public function create()
    {
        if (auth()->user()->can('unit-add')) {

            return view('admin.units.create');
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }



    public function store(Request $request)
    {

        try {
            $unit = new Unit();

            $unit->name = $request->get('name');

            if ($unit->save()) {

                return redirect()->route('units.index')->with(['success' => 'Unit created']);
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
        if (auth()->user()->can('unit-edit')) {
            $data = Unit::findorFail($id);
            return view('admin.units.edit', compact('data'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::findorFail($id);
        try {

            $unit->name = $request->get('name');
            if ($unit->save()) {

                return redirect()->route('units.index')->with(['success' => 'Unit update']);
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
        try {
            $unit = Unit::findOrFail($id);
            // Delete the category
            if ($unit->delete()) {
                return redirect()->back()->with(['success' => 'unit deleted successfully']);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'Something went wrong: ' . $ex->getMessage()]);
        }
    }

}

