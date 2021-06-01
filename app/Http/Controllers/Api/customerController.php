<?php


namespace App\Http\Controllers\Api;


use App\Helpers\APIHelpers;
use App\Http\Controllers\Controller;
use App\Models\customer;
use App\Models\point_transaction;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;
class customerController extends Controller
{



//Get User Transactions

    public function getuserPoints($id,Request $request)
    {
        if ($id) {
            $user = point_transaction::where('customer_id', $id)->Paginate($request->query('page_size'));


            $data = [
                'current_page'=>$user->currentPage(),
                'transactions' => $user->items(),
                'per_page'=>$user->perPage(),
                'total'=>$user->total(),
            ];
//            foreach ($data['transactions'] as $created)
//            {
//                $created= $created->format('d-m-Y H:i:s'));
//            }



            $response = APIHelpers::createAPIResponse(false, 'Points transactions', $data);
            return response(['response' => $response], 200);
        } else {
            $response = APIHelpers::createAPIResponse(true, 'Id is necessary', '');
            return response(['response' => $response], 400);
        }
    }

//Get Total Balance of user
    public function totalbalancePoint($id)
    {
        if ($id) {
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
        } else {
            $response = APIHelpers::createAPIResponse(true, 'Id is necessary', '');
            return response(['response' => $response], 400);
        }
    }
}
