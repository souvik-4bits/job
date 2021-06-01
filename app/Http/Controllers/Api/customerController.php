<?php


namespace App\Http\Controllers\Api;


use App\Helpers\APIHelpers;
use App\Http\Controllers\Controller;
use App\Models\customer;
use App\Models\point_transaction;
use Illuminate\Http\Request;
class customerController extends Controller
{
    //Create Customer
//    public function create(Request $request)
//    {
//
//
//    }
    public function getuserPoints($id)
    {
        if($id)
        {
        $user=point_transaction::where('customer_id',$request['id'])->get();
        $data=[
          'customerPoints'=>$user
        ];
        $response=APIHelpers::createAPIResponse(false,'Points transactions' ,$data);
        return response(['response'=>$response],200);
        }
        else{
            $response=APIHelpers::createAPIResponse(true,'Id is necessary' ,'');
            return response(['response'=>$response],400);
        }
    }


    public function totalbalancePoint($id)
    {
        if($id)
        {
        $credit=point_transaction::where('customer_id',$id)->where('status','credit')->sum('point');
        $debit=point_transaction::where('customer_id',$id)->where(function ($query) {
            $query->where('status','debit')
                ->orwhere('status','void');
        })->sum('point');
        $data=[
            'totalPoints'=>$credit-$debit
        ];
        $response=APIHelpers::createAPIResponse(false,'Total Active Points' ,$data);
        return response(['response'=>$response],200);
    }
        else{
            $response=APIHelpers::createAPIResponse(true,'Id is necessary' ,'');
            return response(['response'=>$response],400);
        }
}
