<?php


namespace App\Models;
use Carbon\Carbon;

class point_transaction extends \Illuminate\Database\Eloquent\Model
{
    protected $fillable=[
        'customer_id','point','transaction_type','status',
    ];
    protected $hidden=[
      'updated_at'
    ];
    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i:s A',
    ];

//    public function getCreatedAtAttribute($date)
//    {      $date=strtotime($date);
//        return date('d/m/Y H:i:s', $date);
//    }
//
//    public function getUpdatedAtAttribute($date)
//    {
//        $date=strtotime($date);
//        return date('d/m/Y H:i:s', $date);
//    }
}
