<?php


namespace App\Http\Controllers\Api;


use App\Helpers\APIHelpers;
use App\Http\Controllers\Controller;
use App\Models\customer;
use App\Models\point_transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;
use Validator;
class customerController extends Controller
{



//Get User Transactions

    public function getuserPoints($id,Request $request)
    {
        $request['id'] = $id;
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'page_size' => 'required|numeric',

        ]);

        if ($validator->fails()) {
            return response(['message' => 'Validation errors', 'errors' => $validator->errors(), 'status' => false], 422);
        }

        try {
            User::findorfail($id);
        }
        catch (\Throwable $throwable)
        {
            $response = APIHelpers::createAPIResponse(true, 'User does Not Exist', '');
            return response(['response' => $response], 404);
        }

            $user = point_transaction::where('customer_id', $id)->Paginate($request->query('page_size'));

            $data = [
                'current_page'=>$user->currentPage(),
                'transactions' => $user->items(),
                'per_page'=>$user->perPage(),
                'total'=>$user->total(),
            ];



            $response = APIHelpers::createAPIResponse(false, 'Points transactions', $data);
            return response(['response' => $response], 200);
        }


//        $users = point_transaction::where('customer_id', $id)->get();
//        foreach ($users as $user)
//        {
//            $user->created_at=$user->created_at->format('d-m-Y H:i:s');
//        }
//        $user=$this->paginate($users, $perPage = $request->query('page_size'), $page = null, $options = []);
    }

//Get Total Balance of user
    public function totalbalancePoint($id,Request $request)
    {
        $request['id'] = $id;
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation errors', 'errors' => $validator->errors(), 'status' => true], 422);
        }

        try {
            User::findorfail($id);
        }
catch (\Throwable $throwable)
{
    $response = APIHelpers::createAPIResponse(true, 'User does Not Exist', '');
    return response(['response' => $response], 404);
}

            $credit = point_transaction::where('customer_id', $id)->where('status', 'credit')->sum('point');
            $debit = point_transaction::where('customer_id', $id)->where(function ($query) {
                $query->where('status', 'debit')
                    ->orwhere('status', 'void');
            })->sum('point');

            dd($debit);
            $data = [
                'totalPoints' => $credit - $debit
            ];
            $response = APIHelpers::createAPIResponse(false, 'Total Active Points', $data);
            return response(['response' => $response], 200);

    }
}
