<?php


namespace App\Http\Controllers\Api;


use App\Helpers\APIHelpers;
use App\Http\Controllers\Controller;
use App\Models\customer;
use App\Models\point_transaction;
use Illuminate\Http\Request;
use Validator;

class customerController extends Controller
{

    /**
     * @OA\Get (
     ** path="/api/customer-transaction/{id}?page_size={page_size}",
     *   tags={"Customer Points"},
     *   summary="customer points Transaction",
     *   operationId="customer Points",
     * @OA\MediaType(
     *              mediaType="multipart/form-data"
     *     ),
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *     description="customer Id",
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ), *  @OA\Parameter(
     *      name="page_size",
     *      in="query",
     *      required=true,
     *     description="page size",
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


//Get User Transactions

    public function getuserPoints($id, Request $request)
    {
        $request['id'] = $id;
        $request['page_size'] = $request->query('page_size');
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'page_size' => 'required|numeric',

        ]);

        if ($validator->fails()) {
            return response(['message' => 'Validation errors', 'errors' => $validator->errors()->first(), 'status' => false], 422);
        }

        try {
            customer::findorfail($id);
        } catch (\Throwable $throwable) {
            $response = APIHelpers::createAPIResponse(true, 'Customer does Not Exist', '');
            return response(['response' => $response], 404);
        }


        $user = point_transaction::where('customer_id', $id)->Paginate($request['page_size']);
        if ($user->total() == 0) {
            $response = APIHelpers::createAPIResponse(true, 'No Transactions Found', '');
            return response(['response' => $response], 404);
        } else {

            $data = [
                'current_page' => $user->currentPage(),
                'transactions' => $user->items(),
                'per_page' => $user->perPage(),
                'total' => $user->total(),
            ];

        }


        $response = APIHelpers::createAPIResponse(false, 'Points transactions', $data);
        return response(['response' => $response], 200);
    }



    /**
     * @OA\Get (
     ** path="/api/customer-balance/{id}",
     *   tags={"Customer Points"},
     *   summary="customer Balance",
     *   operationId="customer Balance",
     * @OA\MediaType(
     *              mediaType="multipart/form-data"
     *     ),
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *     description="customer Id",
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
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


//Get Total Balance of user
    public function totalbalancePoint($id, Request $request)
    {
        $request['id'] = $id;
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation errors', 'errors' => $validator->errors()->first(), 'status' => true], 422);
        }

        try {
            customer::findorfail($id);
        } catch (\Throwable $throwable) {
            $response = APIHelpers::createAPIResponse(true, 'Customer does Not Exist', '');
            return response(['response' => $response], 404);
        }

        $credit = point_transaction::where('customer_id', $id)->where('status', 'credit')->sum('point');
        $debit = point_transaction::where('customer_id', $id)->where(function ($query) {
            $query->where('status', 'debit')
                ->orwhere('status', 'void');
        })->sum('point');

        $data = [
            'totalPoints' => $credit - $debit
        ];
        $response = APIHelpers::createAPIResponse(false, 'Total Active Points', $data);
        return response(['response' => $response], 200);

    }
}
