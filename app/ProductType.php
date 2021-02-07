<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $table = 'product_types';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name', 'created_by', 'updated_by'
    ];

    public function products()
    {
        return $this->hasMany('App\Produk', 'product_type_id', 'id');
    }
}
