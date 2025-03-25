<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobOrderType;

class JobOrderTypeController extends Controller
{

    public function search(Request $request)
    {
        $search = $request->input('search');

        // Search users by mobile or name
        $users = JobOrderType::where('name', 'like', "%{$search}%")
                    ->get();

        // Return JSON response for Select2
        return response()->json($users);
    }

    public function index(Request $request)
    {
        $query = JobOrderType::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where(\DB::raw('CONCAT_WS(" ", `name`)'), 'like', '%' . $request->search . '%');
            });
        }

        $data = $query->paginate(PAGINATION_COUNT);

        $searchQuery = $request->search;

        return view('admin.jobOrderTypes.index', compact('data', 'searchQuery'));
    }


    public function create()
    {
        if (auth()->user()->can('jobOrderType-add')) {
            return view('admin.jobOrderTypes.create');
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }


    public function store(Request $request)
    {
        try {
            $jobOrderType = new JobOrderType();
            $jobOrderType->name = $request->get('name');

            // Assign values to the new columns from the request
            $jobOrderType->price_of_mwaseer = $request->get('price_of_mwaseer');
            $jobOrderType->price_of_trankat = $request->get('price_of_trankat');
            $jobOrderType->price_of_brabesh = $request->get('price_of_brabesh');
            $jobOrderType->price_of_tadkek = $request->get('price_of_tadkek');
            $jobOrderType->price_of_tadkek_msar_close = $request->get('price_of_tadkek_msar_close');
            $jobOrderType->price_of_tarkeeb_router = $request->get('price_of_tarkeeb_router');
            $jobOrderType->price_of_mada_tv = $request->get('price_of_mada_tv');
            $jobOrderType->price_of_le7am_sh3raat = $request->get('price_of_le7am_sh3raat');


            $jobOrderType->hawa2e = $request->get('hawa2e');
            $jobOrderType->hawa2e_rabt_after_120m = $request->get('hawa2e_rabt_after_120m');
            $jobOrderType->hawa2e_mwaseer_after_5m = $request->get('hawa2e_mwaseer_after_5m');
            $jobOrderType->mawaseer = $request->get('mawaseer');
            $jobOrderType->mawaseer_rabt_after_120m = $request->get('mawaseer_rabt_after_120m');
            $jobOrderType->mawaseer_mwaseer_after_5m = $request->get('mawaseer_mwaseer_after_5m');
            $jobOrderType->tadkeek = $request->get('tadkeek');
            $jobOrderType->tadkeek_rabt_after_120m = $request->get('tadkeek_rabt_after_120m');
            $jobOrderType->tadkeek_mwaseer_after_5m = $request->get('tadkeek_mwaseer_after_5m');
            $jobOrderType->tadkek_msar_close = $request->get('tadkek_msar_close');
            $jobOrderType->tadkek_msar_close_rabt_after_120m = $request->get('tadkek_msar_close_rabt_after_120m');
            $jobOrderType->tadkek_msar_close_mwaseer_after_5m = $request->get('tadkek_msar_close_mwaseer_after_5m');
            $jobOrderType->tathmena = $request->get('tathmena');
            $jobOrderType->tathmena_rabt_after_120m = $request->get('tathmena_rabt_after_120m');
            $jobOrderType->tathmena_mwaseer_after_5m = $request->get('tathmena_mwaseer_after_5m');

            $jobOrderType->price_from_engineer = $request->get('price_from_engineer');
            $jobOrderType->price_of_1m_per_length = $request->get('price_of_1m_per_length');
            $jobOrderType->price_of_tarkeb_marwaha = $request->get('price_of_tarkeb_marwaha');
            $jobOrderType->price_of_one_shara = $request->get('price_of_one_shara');
            $jobOrderType->price_of_8_12_24 = $request->get('price_of_8_12_24');
            $jobOrderType->price_of_48_72_96_144 = $request->get('price_of_48_72_96_144');

            if ($jobOrderType->save()) {
                return redirect()->route('jobOrderTypes.index')->with(['success' => 'Job Order Type created successfully']);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                ->withInput();
        }
    }








    public function edit($id)
    {
        if (auth()->user()->can('jobOrderType-edit')) {
            $data = JobOrderType::findorFail($id);

            return view('admin.jobOrderTypes.edit', compact('data'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function update(Request $request, $id)
    {
        $jobOrderType = JobOrderType::findOrFail($id);
        try {
            $jobOrderType->name = $request->get('name');

            // Assign updated values to the columns
            $jobOrderType->price_of_mwaseer = $request->get('price_of_mwaseer');
            $jobOrderType->price_of_trankat = $request->get('price_of_trankat');
            $jobOrderType->price_of_brabesh = $request->get('price_of_brabesh');
            $jobOrderType->price_of_tadkek = $request->get('price_of_tadkek');
            $jobOrderType->price_of_tadkek_msar_close = $request->get('price_of_tadkek_msar_close');
            $jobOrderType->price_of_tarkeeb_router = $request->get('price_of_tarkeeb_router');
            $jobOrderType->price_of_mada_tv = $request->get('price_of_mada_tv');
            $jobOrderType->price_of_le7am_sh3raat = $request->get('price_of_le7am_sh3raat');

            $jobOrderType->hawa2e = $request->get('hawa2e');
            $jobOrderType->hawa2e_rabt_after_120m = $request->get('hawa2e_rabt_after_120m');
            $jobOrderType->hawa2e_mwaseer_after_5m = $request->get('hawa2e_mwaseer_after_5m');
            $jobOrderType->mawaseer = $request->get('mawaseer');
            $jobOrderType->mawaseer_rabt_after_120m = $request->get('mawaseer_rabt_after_120m');
            $jobOrderType->mawaseer_mwaseer_after_5m = $request->get('mawaseer_mwaseer_after_5m');
            $jobOrderType->tadkeek = $request->get('tadkeek');
            $jobOrderType->tadkeek_rabt_after_120m = $request->get('tadkeek_rabt_after_120m');
            $jobOrderType->tadkeek_mwaseer_after_5m = $request->get('tadkeek_mwaseer_after_5m');
            $jobOrderType->tadkek_msar_close = $request->get('tadkek_msar_close');
            $jobOrderType->tadkek_msar_close_rabt_after_120m = $request->get('tadkek_msar_close_rabt_after_120m');
            $jobOrderType->tadkek_msar_close_mwaseer_after_5m = $request->get('tadkek_msar_close_mwaseer_after_5m');
            $jobOrderType->tathmena = $request->get('tathmena');
            $jobOrderType->tathmena_rabt_after_120m = $request->get('tathmena_rabt_after_120m');
            $jobOrderType->tathmena_mwaseer_after_5m = $request->get('tathmena_mwaseer_after_5m');
            
            $jobOrderType->price_from_engineer = $request->get('price_from_engineer');
            $jobOrderType->price_of_1m_per_length = $request->get('price_of_1m_per_length');
            $jobOrderType->price_of_tarkeb_marwaha = $request->get('price_of_tarkeb_marwaha');
            $jobOrderType->price_of_one_shara = $request->get('price_of_one_shara');
            $jobOrderType->price_of_8_12_24 = $request->get('price_of_8_12_24');
            $jobOrderType->price_of_48_72_96_144 = $request->get('price_of_48_72_96_144');

            if ($jobOrderType->save()) {
                return redirect()->route('jobOrderTypes.index')->with(['success' => 'Job Order Type updated successfully']);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $jobOrderType = JobOrderType::findOrFail($id);
        $jobOrderType->delete();

        return redirect()->route('jobOrderTypes.index')->with(['success' => 'Job Order Type Delete']);
    }

}
