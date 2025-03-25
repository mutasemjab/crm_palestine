<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPhoto;
use App\Models\ProductUnit;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use App\Exports\ProductsSampleExport;
use Maatwebsite\Excel\Excel as ExcelWriter;

class ProductController extends Controller
{

    // public function showImportPage()
    // {
    //     return view('admin.products.import');
    // }
    // public function storeFromExcel(Request $request)
    // {
    //     try {
    //         // Check if the request has a file
    //         if ($request->hasFile('file')) {
    //             // Import the file using the ProductsImport class
    //             Excel::import(new ProductsImport, $request->file('file'));
    //         }

    //         return redirect()->route('products.index')->with(['success' => 'Products imported successfully']);
    //     } catch (\Exception $ex) {
    //         Log::error($ex);
    //         return redirect()->back()
    //             ->with(['error' => 'An error occurred: ' . $ex->getMessage()])
    //             ->withInput();
    //     }
    // }


    // public function downloadSample()
    // {
    //     return Excel::download(new ProductsSampleExport, 'sample_products.xlsx', ExcelWriter::XLSX);
    // }

    public function search(Request $request)
    {
        $query = $request->input('term');
        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->with([
                'unit:id,name'
            ])
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'unit' => [
                        'id' => $product->unit->id,
                        'name' => $product->unit->name,
                    ],
                ];
            });

        return response()->json($products);
    }



    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%");
            });
        }

        $data = $query->paginate(PAGINATION_COUNT);

        return view('admin.products.index', compact('data'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (auth()->user()->can('product-add')) {
            $units = Unit::get();
            return view('admin.products.create', compact( 'units'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // Create a new product without saving it to the database yet
            $product = new Product();
            $product->name = $request->input('name');
            $product->status = $request->input('status');
            $product->unit_id = $request->input('unit');
            if($product->save())
            {
                return redirect()->route('products.index')->with(['success' => 'Product created']);
            }else{
                return redirect()->back()->with(['error' => 'Something wrong']);
            }
        } catch (\Exception $ex) {
            Log::error($ex);
            return redirect()->back()
                ->with(['error' => 'An error occurred: ' . $ex->getMessage()])
                ->withInput();
        }
    }

    public function edit($id)
    {
        if (auth()->user()->can('product-edit')) {
            $data = Product::findOrFail($id); // Retrieve the category by ID
            $units = Unit::all();
            return view('admin.products.edit', ['units' => $units,  'data' => $data]);
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);


            $product->name = $request->input('name');
            $product->status = $request->input('status');
            $product->unit_id = $request->input('unit');

            if($product->save())
            {
                return redirect()->route('products.index')->with(['success' => 'Product Updated']);
            }else{
                return redirect()->back()->with(['error' => 'Something wrong']);
            }
        } catch (\Exception $ex) {
            Log::error($ex);
            return redirect()->back()
                ->with(['error' => 'An error occurred: ' . $ex->getMessage()])
                ->withInput();
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
        try {

            $item_row = Product::select("id")->where('id', '=', $id)->first();

            if (!empty($item_row)) {

                $flag = Product::where('id', '=', $id)->delete();

                if ($flag) {
                    return redirect()->back()
                        ->with(['success' => '   Delete Succefully   ']);
                } else {
                    return redirect()->back()
                        ->with(['error' => '   Something Wrong']);
                }
            } else {
                return redirect()->back()
                    ->with(['error' => '   cant reach fo this data   ']);
            }
        } catch (\Exception $ex) {

            return redirect()->back()
                ->with(['error' => ' Something Wrong   ' . $ex->getMessage()]);
        }
    }
}
