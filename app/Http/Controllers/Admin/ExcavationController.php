<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Excavation;

class ExcavationController extends Controller
{



    public function index(Request $request)
    {
        $data = Excavation::paginate(PAGINATION_COUNT);


        return view('admin.excavations.index', compact('data'));
    }


    public function create()
    {
        if (auth()->user()->can('excavation-add')) {
            return view('admin.excavations.create');
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }


    public function store(Request $request)
    {
        try {
            $excavation = new Excavation();
            $excavation->name_of_worker = $request->get('name_of_worker');
            $excavation->expenses = $request->get('expenses');
            $excavation->purchases = $request->get('purchases');
            $excavation->note = $request->get('note');
            $excavation->google_drive_link = $request->get('google_drive_link');

            if ($excavation->save()) {

                return redirect()->route('excavations.index')->with(['success' => 'excavation created']);
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
        if (auth()->user()->can('excavation-edit')) {
            $data = Excavation::findorFail($id);

            return view('admin.excavations.edit', compact('data'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function update(Request $request, $id)
    {
        $excavation = Excavation::findorFail($id);
        try {

            $excavation->name_of_worker = $request->get('name_of_worker');
            $excavation->expenses = $request->get('expenses');
            $excavation->purchases = $request->get('purchases');
            $excavation->note = $request->get('note');
            $excavation->google_drive_link = $request->get('google_drive_link');

            if ($excavation->save()) {

                return redirect()->route('excavations.index')->with(['success' => 'excavation update']);
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
        $excavation = Excavation::findOrFail($id);
        $excavation->delete();

        return redirect()->route('excavations.index')->with(['success' => 'rexcavation Delete']);
    }

}
