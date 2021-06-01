<?php


namespace App\Models;


class point_transaction extends \Illuminate\Database\Eloquent\Model
{
    protected $fillable=[
        'customer_id','point','transaction_type','status',
    ];
}
