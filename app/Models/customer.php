<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    protected $fillable=[
        'name','email','contact',
    ];

public function points()
{
    return $this->hasMany(point_transaction::class,'customer_id','id');
}

}
