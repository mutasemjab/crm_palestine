<?php

namespace App\Http\Controllers\Admin;

use App\Exports\NoteVouchersSampleExport;
use App\Http\Controllers\Controller;
use App\Models\NoteVoucher;
use App\Models\NoteVoucherType;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\NoteVouchersImport;
use App\Exports\ProductsSampleExport;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel as ExcelWriter;
class NoteVoucherController extends Controller
{




    public function index()
    {
        $data = NoteVoucher::get();

        return view('admin.noteVouchers.index', ['data' => $data]);
    }

    public function create(Request $request)
    {
        if (auth()->user()->can('noteVoucher-add')) {

            $note_voucher_type_id = $request->query('id');
            $warehouses = Warehouse::get();
            $tasks = Task::get();
            $note_voucher_type = NoteVoucherType::findOrFail($note_voucher_type_id);

            return view('admin.noteVouchers.create',compact('note_voucher_type_id','warehouses','note_voucher_type','tasks'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }



    public function store(Request $request)
{
    $lastNoteVoucher = NoteVoucher::orderBy('id', 'desc')->first();
    $newNumber = $lastNoteVoucher ? $lastNoteVoucher->id + 1 : 1;

    // Create the note voucher
    $noteVoucher = NoteVoucher::create([
        'note_voucher_type_id' => $request['note_voucher_type_id'],
        'date_note_voucher' => $request['date_note_voucher'],
        'number' => $newNumber,
        'from_warehouse_id' => $request['fromWarehouse'],
        'to_warehouse_id' => $request['toWarehouse'] ?? null,
        'task_id' => $request['task'] ?? null,
        'admin_id' => auth()->user()->id,
        'note' => $request['note'],
    ]);

    // Save the products and update quantities
    foreach ($request['products'] as $productData) {
        $product = Product::where('name', $productData['name'])->firstOrFail();

        // Attach product to voucher
        $noteVoucher->voucherProducts()->attach($product->id, [
            'unit_id' => $productData['unit'],
            'quantity' => $productData['quantity'],
            'purchasing_price' => $productData['purchasing_price'] ?? null,
            'note' => $productData['note'],
        ]);

    }

    if ($request->input('redirect_to') == 'show') {
        return redirect()->route('noteVouchers.show', $noteVoucher->id)->with('success', 'Note Voucher created successfully!');
    } else {
        return redirect()->route('noteVouchers.index')->with('success', 'Note Voucher created successfully!');
    }
}




    public function show($id)
    {
        $noteVoucher = NoteVoucher::with([
            'fromWarehouse',
            'toWarehouse',
            'admin',
            'voucherProducts',
            'voucherProducts.units',
            'noteVoucherType' // Include the related noteVoucherType
        ])->findOrFail($id);

        return view('admin.noteVouchers.show', compact('noteVoucher'));
    }

    public function edit($id)
    {
        $noteVoucher = NoteVoucher::with('noteVoucherType','voucherProducts')->findOrFail($id);
        $products = Product::all();
        $tasks = Task::get();
        $warehouses = Warehouse::all();
        // Pass the note voucher and products to the view
        return view('admin.noteVouchers.edit', compact('noteVoucher', 'products','warehouses','tasks'));
    }


    public function update(Request $request, $id)
    {
        $noteVoucher = NoteVoucher::findOrFail($id);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Update the note voucher details
            $noteVoucher->update([
                'note_voucher_type_id' => $request['note_voucher_type_id'],
                'date_note_voucher' => $request['date_note_voucher'],
                'from_warehouse_id' => $request['fromWarehouse'],
                'to_warehouse_id' => $request['toWarehouse'] ?? null,
                'task_id' => $request['task'] ?? null,
                'note' => $request['note'],
            ]);

            $existingProducts = $noteVoucher->voucherProducts()->pluck('product_id')->toArray();
            $submittedProducts = [];

            // Attach or update products
            foreach ($request['products'] as $productData) {
                $product = Product::where('name', $productData['name'])->firstOrFail();
                $submittedProducts[] = $product->id;

                // Update existing product or attach a new one
                $noteVoucher->voucherProducts()->syncWithoutDetaching([
                    $product->id => [
                        'unit_id' => $productData['unit'],
                        'quantity' => $productData['quantity'],
                        'purchasing_price' => $productData['purchasing_price'] ?? null,
                        'note' => $productData['note'],
                    ]
                ]);
            }

            // Detach products that were not in the request
            $productsToDetach = array_diff($existingProducts, $submittedProducts);
            if (!empty($productsToDetach)) {
                $noteVoucher->voucherProducts()->detach($productsToDetach);
            }

            DB::commit();

            return redirect()->route('noteVouchers.index')->with('success', 'Note Voucher updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }



    public function destroy($id)
    {
        try {
            $noteVoucher = NoteVoucher::findOrFail($id);
            // Delete the category
            if ($noteVoucher->delete()) {
                return redirect()->back()->with(['success' => 'noteVoucher deleted successfully']);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'Something went wrong: ' . $ex->getMessage()]);
        }
    }
}
