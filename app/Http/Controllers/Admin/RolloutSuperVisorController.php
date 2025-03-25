<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RolloutSuperVisor;

class RolloutSuperVisorController extends Controller
{



    public function index(Request $request)
    {
        $data = RolloutSuperVisor::paginate(PAGINATION_COUNT);


        return view('admin.rolloutSuperVisors.index', compact('data'));
    }


    public function create()
    {
        if (auth()->user()->can('rolloutSuperVisor-add')) {
            return view('admin.rolloutSuperVisors.create');
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }


    public function store(Request $request)
    {
        try {
            $rolloutSuperVisor = new RolloutSuperVisor();
            $rolloutSuperVisor->name_of_worker = $request->get('name_of_worker');
            $rolloutSuperVisor->expenses = $request->get('expenses');
            $rolloutSuperVisor->purchases = $request->get('purchases');
            $rolloutSuperVisor->note = $request->get('note');
            $rolloutSuperVisor->google_drive_link = $request->get('google_drive_link');

            if ($rolloutSuperVisor->save()) {

                return redirect()->route('rolloutSuperVisors.index')->with(['success' => 'rolloutSuperVisor created']);
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
        if (auth()->user()->can('rolloutSuperVisor-edit')) {
            $data = RolloutSuperVisor::findorFail($id);

            return view('admin.rolloutSuperVisors.edit', compact('data'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function update(Request $request, $id)
    {
        $rolloutSuperVisor = RolloutSuperVisor::findorFail($id);
        try {

            $rolloutSuperVisor->name_of_worker = $request->get('name_of_worker');
            $rolloutSuperVisor->expenses = $request->get('expenses');
            $rolloutSuperVisor->purchases = $request->get('purchases');
            $rolloutSuperVisor->note = $request->get('note');
            $rolloutSuperVisor->google_drive_link = $request->get('google_drive_link');

            if ($rolloutSuperVisor->save()) {

                return redirect()->route('rolloutSuperVisors.index')->with(['success' => 'rollout SuperVisor update']);
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
        $rolloutSuperVisor = RolloutSuperVisor::findOrFail($id);
        $rolloutSuperVisor->delete();

        return redirect()->route('rolloutSuperVisors.index')->with(['success' => 'rollout SuperVisor Delete']);
    }

}
