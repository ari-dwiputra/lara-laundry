<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $fillable = [
        'product_type_id', 'name', 'price', 'created_by', 'updated_by'
    ];

    public function product_type()
    {
        return $this->belongsTo('App\ProductType', 'product_type_id', 'id');
    }

    public function transaction_details()
    {
        return $this->hasMany('App\TransactionDetail', 'product_id', 'id');
    }
}
