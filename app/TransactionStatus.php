<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionStatus extends Model
{
    protected $table = 'transaction_statuses';
    protected $primaryKey = 'id';
    protected $fillable = [
        'transaction_id', 'status', 'created_by', 'updated_by'
    ];

    public function transaction()
    {
        return $this->belongsTo('App\Transaction', 'transaction_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }
}
