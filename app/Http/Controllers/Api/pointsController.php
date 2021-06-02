<?php


namespace App\Http\Controllers\Api;


use App\Helpers\APIHelpers;
use App\Http\Controllers\Controller;
use App\Models\customer;
use App\Models\point_transaction;
use Illuminate\Http\Request;
use Throwable;
use Validator;

class pointsController extends Controller
{


    /**
     * @OA\Post(
     ** path="/api/points",
     *   tags={"Points"},
     *   summary="Add points to customer",
     *   operationId="points",
     * @OA\MediaType(
     *              mediaType="multipart/form-data"
     *     ),
     *  @OA\Parameter(
     *      name="customer_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="point",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *
     *   @OA\Parameter(
     *      name="transaction_type",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    /**
     * Register api
     *
     * @return Response
     */
        //Add Points to customer
    public function addPoints(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|numeric',
            'point' => 'required|numeric',
            'transaction_type' => 'required|string',

        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation errors', 'errors' => $validator->errors()->first(), 'status' => false], 422);
        }

        try {
            customer::findorfail($request['customer_id']);
        }
        catch (\Throwable $throwable)
        {
            $response = APIHelpers::createAPIResponse(true, 'Customer does Not Exist', '');
            return response(['response' => $response], 404);
        }
        try {
            $request['status']="credit";
            $pointTransaction = point_transaction::create($request->toarray());

        } catch (Throwable $throwable) {
            $response=APIHelpers::createAPIResponse(true,'Points cannot added this moment' ,'');
            return response(['response'=>$response],200);
        }

            $response=APIHelpers::createAPIResponse('false',$request['point'].' points Added SuccessFully','');
            return response(['response'=>$response],200);

    }



    /**
     * @OA\Put(
     ** path="/api/void-points/{id}",
     *   tags={"Points"},
     *   summary="Void point",
     *   operationId="void Points",
     * @OA\MediaType(
     *              mediaType="multipart/form-data"
     *     ),
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *     description="transaction Id",
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    /**
     * Register api
     *
     * @return Response
     */



        //Void Points of a customer

    public function voidPoints($id,Request $request)
    {
        //Transaction Id

        $request['id']=$id;

        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation errors', 'errors' =>$validator->errors()->first(), 'status' => false], 422);
        }
        try {
            point_transaction::findorfail($request['id'])->update(['status'=>'void']);
        }
        catch (Throwable $th)
        {
            $response=APIHelpers::createAPIResponse(true,'Transaction Id Does not Exist','');
            return response(['response'=>$response],404);

        }

        $response=APIHelpers::createAPIResponse(false,' points Voided Successfully','');
        return response(['response'=>$response],200);

    }



}
