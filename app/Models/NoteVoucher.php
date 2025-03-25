<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteVoucher extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function noteVoucherType()
    {
        return $this->belongsTo(NoteVoucherType::class, 'note_voucher_type_id');
    }

    public function voucherProducts()
    {
        return $this->belongsToMany(Product::class, 'voucher_products', 'note_voucher_id', 'product_id')->withPivot('quantity','purchasing_price','unit_id','created_at', 'updated_at')
        ->withTimestamps();
    }
}
