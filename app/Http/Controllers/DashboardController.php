<?php

namespace App\Http\Controllers;

use App\Models\PaymentInfo;
use App\Models\Transaction;
use App\Models\CashoutRequest;
use App\Models\AdminSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request){

        $role = Auth::user()->getRoleNames()->first();    
    
        if($role == 'User') {
            $user = $request->user();
            $role = $user->getRoleNames()->first();
            $user->role = $role;
            $bonus = AdminSetting::all()->first()->amount ?? 25;

            /*$userPayments = Auth::user()->transactions()->with('transaction',function($query){
                return $query->where('admin_status',2); //2 for Approved only
            })->get();*/

            // $userPayments = Auth::user()->transactions()->where('status',2)->get();

            // $balance = 0;
            // if($userPayments){
            //     $tx_amt =(float) $userPayments->whereNotNull('transaction_id')->whereNotIn('payment_method',['promo','referral','refree'])->sum('amount') ?? 0;
            //     $promo_amt =(float) $userPayments->whereNotNull('transaction_id')->where('payment_method','promo')->sum('amount') ?? 0;
            //     $ref =(float) $userPayments->whereNotNull('transaction_id')->where('payment_method','referral')->sum('amount') ?? 0;
            //     $balance = $tx_amt + $ref + $promo_amt + $ref;
            // }

            $referal_link =  url('/').'/?ref='. \Hashids::encode(auth()->user()->id);

            $lifetimeEarning = PaymentInfo::where(['user_id' => auth::user()->id, 'status' => '2'])->whereNotIn('payment_method',['referral','referre','promo'])->get()->sum('amount');
            $spentOnReferrences = PaymentInfo::where(['user_id' => auth::user()->id, 'status' => '2'])->whereIn('payment_method',['referral','referre'])->get()->sum('amount');
            $spendOnPromo = PaymentInfo::where(['user_id' => auth::user()->id, 'status' => '2'])->where('payment_method','promo')->get()->sum('amount');
            $pendingTransactions = PaymentInfo::where(['user_id' => auth::user()->id, 'status' => '1'])->count();


            return view('dashboard.user-dashboard', compact('user','referal_link','lifetimeEarning','spentOnReferrences','spendOnPromo','pendingTransactions','bonus'));
        }
        elseif($role == 'Cashier')
        {
            $bonus = AdminSetting::all()->first()->amount ?? 25;

            $pendingCashouts = CashoutRequest::where('to_id',auth::user()->id)->where('status','0')->count();        
            $totalWithdrawals = CashoutRequest::where('to_id',auth::user()->id)->where('status','2')->sum('amount');


           

            $lifetimeEarning = PaymentInfo::where(['to_id' => auth::user()->id, 'status' => '2'])->whereNotIn('payment_method',['referral','referre','promo'])->get()->sum('amount');
            $spentOnReferrences = PaymentInfo::where(['to_id' => auth::user()->id, 'status' => '2'])->whereIn('payment_method',['referral','referre'])->get()->sum('amount');
            $spendOnPromo = PaymentInfo::where(['to_id' => auth::user()->id, 'status' => '2'])->where('payment_method','promo')->get()->sum('amount');
            $pendingTransactions = PaymentInfo::where(['to_id' => auth::user()->id, 'status' => '1'])->count();
            return view('dashboard.cashier-dashboard',compact('lifetimeEarning','totalWithdrawals','spentOnReferrences','spendOnPromo','pendingTransactions','pendingCashouts','bonus'));
        }
        elseif($role == 'Admin')
        {
            
            $pendingCashouts = CashoutRequest::where('status','0')->count();
            $totalWithdrawals = CashoutRequest::where('status','2')->sum('amount');



            $lifetimeEarning = PaymentInfo::where('status','2')->whereNotIn('payment_method',['referral','referre','promo'])->get()->sum('amount');
            $spentOnReferrences = PaymentInfo::where('status','2')->whereIn('payment_method',['referral','referre'])->get()->sum('amount');
            $spendOnPromo = PaymentInfo::where('status','2')->where('payment_method','promo')->get()->sum('amount');
            $pendingTransactions = PaymentInfo::where(['status'=>'1'])->count();
            return view('dashboard.admin-dashboard',compact('lifetimeEarning','totalWithdrawals','spentOnReferrences','spendOnPromo','pendingTransactions','pendingCashouts'));
        }
    }

    public function updatePassword(Request $request) {
        $user = $request->user();
        $role = $user->getRoleNames()->first();

        return view('auth.change-password', compact('user'));
    }
}
