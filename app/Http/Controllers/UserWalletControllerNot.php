<?php

namespace App\Http\Controllers;

use App\Models\PaymentInfo;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class UserWalletControllerNot extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userPayments = Auth::user()->transactions()->with('transaction',function($query){
            return $query->where('admin_status',2); //2 for Approved only
        })->get();
        $balance = 0;
        if($userPayments){
            $tx_amt =(float) $userPayments->whereNotNull('transaction')->whereIn('payment_method',['paypal_account','credit_card','paypal'])->sum('amount') ?? 0;
            $promo_amt =(float) $userPayments->whereNotNull('transaction')->where('payment_method','promo')->sum('amount') ?? 0;
            $ref_amt =(float) $userPayments->whereNotNull('transaction')->where('payment_method','referre')->sum('amount') ?? 0;
            $ref =(float) $userPayments->whereNotNull('transaction')->where('payment_method','referral')->sum('amount') ?? 0;
            $balance = $tx_amt + $ref + $promo_amt + $ref_amt;
        }

        return view('user.user-wallet',compact('balance','tx_amt','promo_amt','ref_amt'));
    }

    public function transactionList(Request $request){

        $data = Collect(PaymentInfo::where('status','completed')
        ->where('user_id',Auth::id())
        ->with('transaction')
        ->orderBy('created_at','desc')
        ->get());
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('item', function($row){
                $item = $row->transaction->item;
                return $item;
            })
            ->addColumn('created_at', function($row){
                $created_at = casinoDate($row->created_at);
                return $created_at;
            })
            ->addColumn('type', function($row){
              $pay=ucfirst($row->payment_method);
                 return str_replace('_', ' ', $pay);

            })
            ->addColumn('amount', function($row){
                $type = number_format($row->amount,2);
                return $type;
            })
            ->addColumn('admin_status', function($row){
                $type = getStatusFromId($row->transaction->admin_status);
                return $type;
            })
            ->editColumn('txn_id', function ($row) {
                return  in_array($row->payment_method,['paypal_account','credit_card','paypal']) ?  $row->transaction->txn_id : '-';
             })
            ->rawColumns(['txn_id','created_at','type','admin_status'])
            ->make(true);
    }

    public function filterColumn($column, callable $callback)
    {
        $this->columnDef['filter'][$column] = ['method' => $callback];

        return $this;
    }
}
