<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;

class CountryController extends Controller
{

    public function search(Request $request)
    {
        $search = $request->input('search');

        // Search users by mobile or name
        $users = Country::where('name', 'like', "%{$search}%")
                    ->get();

        // Return JSON response for Select2
        return response()->json($users);
    }

    public function index(Request $request)
    {
        $query = Country::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where(\DB::raw('CONCAT_WS(" ", `name`)'), 'like', '%' . $request->search . '%');
            });
        }

        $data = $query->paginate(PAGINATION_COUNT);

        $searchQuery = $request->search;

        return view('admin.countries.index', compact('data', 'searchQuery'));
    }


    public function create()
    {
        if (auth()->user()->can('country-add')) {
            return view('admin.countries.create');
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }


    public function store(Request $request)
    {
        try {
            $country = new Country();
            $country->name = $request->get('name');
            $country->whatsapp_link = $request->get('whatsapp_link');

            if ($country->save()) {

                return redirect()->route('countries.index')->with(['success' => 'Country created']);
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
        if (auth()->user()->can('country-edit')) {
            $data = Country::findorFail($id);

            return view('admin.countries.edit', compact('data'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function update(Request $request, $id)
    {
        $country = Country::findorFail($id);
        try {

            $country->name = $request->get('name');
            $country->whatsapp_link = $request->get('whatsapp_link');

            if ($country->save()) {

                return redirect()->route('countries.index')->with(['success' => 'Country update']);
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
        $country = Country::findOrFail($id);
        $country->delete();

        return redirect()->route('countries.index')->with(['success' => 'Country Delete']);
    }

}
