<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'customer_id', 'no_nota', 'start_date', 'end_date', 'total', 'status', 'created_by', 'updated_by'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id', 'id');
    }

    public function transactionDetails()
    {
        return $this->hasMany('App\TransactionDetail', 'transaction_id', 'id');
    }

    public function transactionStatuses()
    {
        return $this->hasMany('App\TransactionStatus', 'transaction_id', 'id')->orderBy('created_at', 'desc');
    }
    
    public function lastTransactionStatuses()
    {
        return $this->hasOne('App\TransactionStatus', 'transaction_id', 'id')->orderBy('created_at', 'desc');
    }
}
