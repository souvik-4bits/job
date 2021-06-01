<?php


namespace App\Http\Controllers\Api;


use App\Helpers\APIHelpers;
use App\Http\Controllers\Controller;
use App\Models\point_transaction;
use Illuminate\Http\Request;
use Throwable;
use Validator;

class pointsController extends Controller
{

    public function addPoints(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|numeric',
            'point' => 'required|numeric',
            'transaction_type' => 'required|string',
            'status' => 'required|in:credit,debit,void',

        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation errors', 'errors' => $validator->errors(), 'status' => false], 422);
        }
        try {
            $pointTransaction = point_transaction::create($request->toarray());
            if($request['status']=="credit"){
            $response=APIHelpers::createAPIResponse('false',$request['point'].' points Added SuccessFully','');
            return response(['response'=>$response],200);
            }
            else{
                $response=APIHelpers::createAPIResponse('false',$request['point'].' points Deducted SuccessFully','');
                return response(['response'=>$response],200);
            }
        } catch (Throwable $throwable) {
            $response=APIHelpers::createAPIResponse(true,'Points cannot added this moment' ,'');
            return response(['response'=>$response],200);
        }


    }

    public function voidPoints(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation errors', 'errors' => $validator->errors(), 'status' => false], 422);
        }
        $pointTransaction=point_transaction::find($request['id'])->update(['status'=>'void']);
        $response=APIHelpers::createAPIResponse('false',' points Voided Successfully','');
        return response(['response'=>$response],200);

    }



}
