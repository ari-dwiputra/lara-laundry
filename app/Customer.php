<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name', 'email', 'phone', 'address', 'created_by', 'updated_by'
    ];

    public function transactions()
    {
        return $this->hasMany('App\Transaction', 'customer_id', 'id');
    }
}
