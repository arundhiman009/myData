<?php

namespace App\Http\Controllers;

use Stripe;
use Carbon\Carbon;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\PromoCode;
use App\Models\Transaction;
use App\Models\PaymentInfo;
use App\Models\AdminSetting;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

use Yajra\DataTables\DataTables;

class LoadMoneyRequestController extends Controller
{
    public function __construct()
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function index()
    {

        $cashier_list = [];
        $customer_list = [];
        if(Auth::user()->getRoleNames()->first() == 'Admin'){
            $cashier_list = User::with('roles')->whereHas('roles',function($role) {
                $role->where('name','Cashier');
            })->pluck('username','id')->toArray();
            $customer_list = User::with('roles')->whereHas('roles',function($role) {
                $role->where('name','User');
            })->pluck('username','id')->toArray();
        }
        else if(Auth::user()->getRoleNames()->first() == 'Cashier'){
            $customer_list = User::with('roles')->where('cashier_id', Auth::user()->id)->whereHas('roles',function($role) {
                $role->where('name','User');
            })->pluck('username','id')->toArray();
        }
        return view('loadmoneyrequest.index',compact('cashier_list', 'customer_list'));
    }

    public function indexReport(Request $request)
    {
        $cashier_list = [];
        $customer_list = [];
        if(Auth::user()->getRoleNames()->first() == 'Admin'){
            $cashier_list = User::with('roles')->whereHas('roles',function($role) {
                $role->where('name','Cashier');
            })->pluck('username','id')->toArray();
            $customer_list = User::with('roles')->whereHas('roles',function($role) {
                $role->where('name','User');
            })->pluck('username','id')->toArray();
        }
        else if(Auth::user()->getRoleNames()->first() == 'Cashier'){
            $customer_list = User::with('roles')->where('cashier_id', Auth::user()->id)->whereHas('roles',function($role) {
                $role->where('name','User');
            })->pluck('username','id')->toArray();
        }
        return view('loadmoneyrequest.index-report',compact('cashier_list', 'customer_list'));
    }

    public function changeStatus(Request $request)
    {   
        $attributes = $request->all();
        $validateArray = array(
            'status' => 'required|integer',
            'comments' => 'required|min:5',
        );
        $validator = Validator::make($attributes, $validateArray);
        if ($validator->fails())
        {
            return response()->json(['success' => false, 'type' => 'validation-error', "error" => $validator->errors()]);
        }
        try{
        PaymentInfo::where('id',$request->id)->update(['status'=> $request->status ,'comments' => $request->comments]);
       
            return response()->json(['success' => true, 'message'=>'Status updated.']);
        }
        catch(Exception $e){

            return response()->json(['success' => false, 'error'=>'Something went wrong.']);
        }
    }

    public function loadMoneyList(Request $request)
    {
        if(Auth::user()->getRoleNames()->first() == 'Admin'){
            $query = PaymentInfo::with(['user','to_user']); 
        }
        elseif(Auth::user()->getRoleNames()->first() == 'Cashier')
        {     
            $query = PaymentInfo::with(['user','to_user'])->where('to_id',auth::user()->id);
        }
        elseif(Auth::user()->getRoleNames()->first() == 'User'){
            $query = PaymentInfo::with(['user','to_user'])->where('user_id',auth::user()->id);
        }

        // Start Advance Search Filter
        $search_cashier_id = $request->input('search_cashier_id');
        $search_customer_id = $request->input('search_customer_id');
        $search_request_type = $request->input('search_request_type');
        $search_request_status = $request->input('search_request_status');
        $search_request_between = $request->input('search_request_between');
        if(isset($search_cashier_id) && $search_cashier_id!=="")
        {
            $query->where('to_id',$search_cashier_id);
        }
        if(isset($search_customer_id) && $search_customer_id!=="")
        {
            $query->where('user_id',$search_customer_id);
        }
        if(isset($search_request_type) && $search_request_type!=="")
        {
            $query->where('type',$search_request_type);
        }
        if(isset($search_request_status) && $search_request_status!=="")
        {
            $query->where('status',$search_request_status);
        }
        if(isset($search_request_between) && $search_request_between!=="" )
        {
            $data = explode('-',$search_request_between);
            $startDate = Carbon::CreateFromFormat('m/d/Y',trim($data[0]))->format('Y-m-d');
            $endDate = Carbon::CreateFromFormat('m/d/Y',trim($data[1]))->format('Y-m-d');
            $data = $query->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59']); 
        }
        // End Advance Search Filter

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('username', function($row){
                return $row->user->username."<br/>".$row->user->email;
            })
            ->addColumn('cashierinfo', function($row){
                $cashierinfo = "No Host";
                if($row->user->cashier_id)
                {
                    $cashier_info = $row->to_user->username."<br/>";
                    $cashier_info .= $row->to_user->email;
                    return $cashier_info;
                }
                return $cashierinfo;
            })
            ->addColumn('method', function($row){
                $pay=$row->amount ?? '--';
                if($pay != '--'){
                    $pay= str_replace('_', ' ', $pay);
                }
                return $pay;
            })
            ->addColumn('payment_info', function($row){
                $response = "<b>Mode:</b> ". $row->type."<br/>";
                $response .= "<b>Game:</b> " . $row->game."<br/>";
                $response .= "<b>Method:</b> " .ucwords($row->payment_method)."<br/>";
                $response .= "<b>Amount:</b> $" .number_format($row->amount,2)."<br/>";
                return $response;
            })
            ->addColumn('transaction_history', function($row){
                $response = "-";
                if($row->type=="Online")
                {
                    /*$response = "<b>Order Id:</b> ". $row->transaction->order_id."<br/>";*/
                    $response = "<b>Txn Id:</b> " .$row->transaction->txn_id."<br/>";
                    $response .= "Status: Completed<br/>";
                    return $response;
                }
                return $response;
            })
            ->addColumn('created_at', function($row){
                return casinoDate($row->created_at);
            })
            ->addColumn('request_status', function($row){
                $status = getLoadMoneyStatus($row->status);
                $status .= "<br>".$row->comments;
                return $status;
            })
            ->addColumn('action', function($row){
                // $amount = collect($row->payments);
                // $balance = $row->amount;
                // $tx_amt = $promo_amt = $ref = $ref_amt = 0;
                // if($amount)
                // {
                //     $tx_amt =(float) $amount->whereIn('payment_method',['paypal_account','credit_card','paypal'])->sum('amount') ?? 0;
                //     $promo_amt =(float) $amount->where('payment_method','promo')->sum('amount') ?? 0;
                //     $ref_amt =(float) $amount->where('payment_method','referre')->sum('amount') ?? 0;
                //     $ref =(float) $amount->where('payment_method','referral')->sum('amount') ?? 0;
                //     $balance = $tx_amt + $ref + $promo_amt + $ref_amt;
                // }

                $userinfo = '<a href="javascript:void(0);" title="Approve transaction" data-id="'.$row->id.'" data-name= "'.$row->user->username.'"data-comments="'.$row->comments.'" data-status="'.$row->status.'" data-user_cashier="'.$row->to_user->username .'" data-tx_number="'.$row->transaction_id.'" data-promo-amount="'.number_format($row->amount,2).'" data-promo="'. $row->promo_name .'" data-promo-discount="'. $row->promo_discount .'" data-promo-type="'. $row->promo_type .'" data-transaction-type="'.$row->type .' - ' . ucwords($row->payment_method) .' " data-refrerral-by="'.@$row->referralInfo->username.'" data-method="'.$row->payment_method.'" class="approveTransaction" ><i class="fa fa-edit approveTransaction" style="color:white !important" aria-hidden="true"></i></a>';

                return $userinfo;
            })
            ->rawColumns(['username','cashierinfo','payment_info','transaction_history','amt','created_at','expiry_date','action','request_status'])
            ->make(true);
    }

    public function loadMoneyReportList(Request $request)
    {
        if(Auth::user()->getRoleNames()->first() == 'Admin'){
            $query = PaymentInfo::where('to_id','!=', auth::user()->id)->where(['type' => 'Offline'])->with(['user','to_user'])->orderBy('created_at','desc'); 
        }
        elseif(Auth::user()->getRoleNames()->first() == 'Cashier')
        {     
            $query = PaymentInfo::with(['user','to_user'])->where(['to_id' => auth::user()->id, 'type' => 'Offline'])->orderBy('created_at','desc');
        }

        // Start Advance Search Filter
        $search_cashier_id = $request->input('search_cashier_id');
        $search_request_status = $request->input('search_request_status');
        $search_request_between = $request->input('search_request_between');
        if(isset($search_cashier_id) && $search_cashier_id!=="")
        {
            $query->where('to_id',$search_cashier_id);
        }
        if(isset($search_request_status) && $search_request_status!=="")
        {
            $query->where('admin_status',$search_request_status);
        }
        if(isset($search_request_between) && $search_request_between!=="" )
        {
            $data = explode('-',$search_request_between);
            $startDate = Carbon::CreateFromFormat('m/d/Y',trim($data[0]))->format('Y-m-d');
            $endDate = Carbon::CreateFromFormat('m/d/Y',trim($data[1]))->format('Y-m-d');
            $data = $query->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59']); 
        }
        $result = clone $query;
        $outstanding = $result->whereIn('admin_status',['0','1'])->sum('amount');
        $outstanding = '$'.number_format($outstanding,2);
        // End Advance Search Filter

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('cashierinfo', function($row){
                $cashierinfo = "No cashier";
                if($row->user->cashier_id)
                {
                    $cashier_info = $row->to_user->username."<br/>";
                    $cashier_info .= $row->to_user->email;
                    return $cashier_info;
                }
                return $cashierinfo;
            })
            ->addColumn('method', function($row){
                $pay=$row->amount ?? '--';
                if($pay != '--'){
                    $pay= str_replace('_', ' ', $pay);
                }
                return $pay;
            })
            ->addColumn('comment', function($row){
                $comment = '';
                if($row->comment){
                    $comment= $row->comment;
                }
                return $comment;
            })
            ->addColumn('payment_info', function($row){
                $response = "<b>Mode:</b> ". $row->type."<br/>";
                $response .= "<b>Game:</b> " .$row->game."<br/>";
                $response .= "<b>Method:</b> " .$row->payment_method."<br/>";
                $response .= "<b>Amount:</b> $" .number_format($row->amount,2)."<br/>";
                return $response;
            })
            ->addColumn('created_at', function($row){
                return casinoDate($row->created_at);
            })
          
            ->addColumn('request_status', function($row){
                $status = getLoadMoneyReportStatus($row->admin_status);
                return $status;
            })
            ->rawColumns(['cashierinfo','payment_info','created_at','request_status'])
            ->with('outstanding',$outstanding)
            ->make(true);
    }


    public function checkPromoCode(Request $request)
    {
        $data = null;
        $promoCode = $request->validate([
            'promo_code' => 'required|exists:promo_codes,name',
            'amount' => 'required|integer|min:10'
        ]);

        $promoData =  PromoCode::where(['name' => $promoCode['promo_code']])->whereDate('expiry_date', '>=', Carbon::today())->first();
         
        if($promoData['name'] !== $request->promo_code){
            return response()->json(['success' => false,  'message' => 'Invalid Promo Code']);
        }
       
        // dd($promoData->users()->sync(Auth::id()));

        if ($request->session()->get('promoCode')) {
            $request->session()->forget('promoCode');
            return response()->json(['success' => true, 'btn' => 'Apply', 'data' => $data, 'message' => 'Promo code has been removed.']);
        }

        if ($promoData) {

            //Calulcate Amount from coupan and validate for Fixed method for percentage need to validate at Coupan side
            $amt = getAmountFromFixedPercentage($promoCode['amount'], $promoData->amount, $promoData->discount_type);
            if($amt > $promoCode['amount'])
            {
                return response()->json(['success' => false, 'message' => 'Opps you need to add at least $'.((float)$promoCode['amount'] + ($amt)).'.']);
            }
            $data = '<td>Promo Bonus</td><td>$<span id="promo_balance">' .  number_format($amt,2) . '</span></td>';
            if ($promoData->users()->count() < $promoData->limit) {
                $promoData->finalAmount = number_format($amt,2);
                $request->session()->put('promoCode', $promoData);
                return response()->json(['success' => true, 'data' => $data, 'btn' => 'Remove', 'message' => 'Promo code has successfully applied.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Promo code limit has been exceeded.']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Promo code has been expired.']);
        }
    }

    public function cashierRequestSend(Request $request)
    {

        $attributes = $request->all();
        $validateArray = array(
            'ids' => 'required',
            'comment' => 'required|min:5',
        );
        $validator = Validator::make($attributes, $validateArray);
        if ($validator->fails())
        {
            return response()->json(['success' => false, 'type' => 'validation-error', "error" => $validator->errors()]);
        }
           
        try
        {
        
            $ids = explode(",", $request->ids);
            PaymentInfo::whereIn('id',$ids)->update(['admin_status'=> '1' ,'comment' => $request->comment]);
            $user = Auth::user();
            $mails = User::role('admin')->pluck('email')->toArray();

            Mail::send('emails/admin/CashierSendRequest',['data' => $user,'adminEmail'=>$mails], function ($m) use ($mails) {
                $m->to($mails)->subject('Transaction Request Send');
            });
            return response()->json(['success' => true, 'message'=>'Request send.']);
        }
        catch(Exception $e){

            return response()->json(['success' => false, 'error'=> $e->getMessage()]);       
        }
    }


    public function adminRequestAccept(Request $request)
    {

        $attributes = $request->all();

        $validateArray = array(
            'ids' => 'required'            
        );
        $validator = Validator::make($attributes, $validateArray);

        if ($validator->fails())
            {
                return response()->json(['success' => false, 'type' => 'validation-error', "error" => $validator->errors()]);
            }
       
        try
        {       
            
            PaymentInfo::whereIn('id',$request->ids)->update(['admin_status'=> '2']);
                return response()->json(['success' => true, 'message'=>'Status updated.']);
        }
        catch(Exception $e){

            return response()->json(['success' => false, 'error'=>'Something went wrong.']);
        }

    }


    public function create(Request $request, $type)
    {

        $role = Auth::user()->getRoleNames()->first();
        if($type == 'online' && $role == 'User')
        {

            $request->session()->forget('promoCode');
            $request->session()->forget('referralBonus');
            $referralBonus=null;
            /*$token = $this->gateway->ClientToken()->generate();*/
            
            if(Auth::user()->referred_by && Auth::user()->numberOfPayments()->count() == 0)
            {
            $referralBonus =  AdminSetting::all()->first()->amount ?? 25;
            $request->session()->put('referralBonus',$referralBonus);
            }

            return view('loadmoneyrequest.online-payment', ['referralBonus'=>$referralBonus]);
        }
        else if($type == 'offline' && $role != 'User')
        {
           if(Auth::user()->getRoleNames()->first() == 'Admin'){
            $users = User::role('User')->get(); 
           }
           elseif(Auth::user()->getRoleNames()->first() == 'Cashier'){
            $users = User::where('cashier_id',auth::user()->id)->get(); 
           }
            return view('loadmoneyrequest.offline-payment',compact('users'));
        }
    }

    /*public function getOfflineCashouts()
    {
        if(Auth::user()->getRoleNames()->first() == 'Admin'){
            $users = User::role('User')->get(); 
        }
        elseif(Auth::user()->getRoleNames()->first() == 'Cashier'){
            $users = User::where('cashier_id',auth::user()->id)->get(); 
        }
        return view('loadmoneyrequest.offline-payment',compact('users'));
    }*/
    
    public function store(Request $request)
    {   
        $customMessages = [
            'lte' => 'The amount must be less than or equal $999999.99'
        ];   
       $request->validate([
            'game' => 'required',
            'amount' => 'required|gte:20|lte:999999.99',
        ],$customMessages);

        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => $request->amount * 100,
                    'product_data' => [
                    'name' => 'Load Money Request - '.$request->game,
                    'images' => ["https://www.alibabaonlinegames.com/public/assets/images/logo/logo.png"],
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('load.money.success').'?session_id={CHECKOUT_SESSION_ID}&game='.$request->game,
            'cancel_url' => route('load.money',['type'=>'online']),
        ]);   

        return redirect($checkout_session->url);
    }

    public function storeSuccess(Request $request)
    {

        $attributes = $request->all();   
        $validateArray = array(
            'session_id' => 'required', 
        );

        $validator = Validator::make($attributes, $validateArray);
        if ($validator->fails())
        {
            return redirect()->route('load.money',['type'=>'online'])->with('error', $validator->errors());
        }
 
        $result = \Stripe\Checkout\Session::retrieve($request->get('session_id'));
        if($result->payment_status != 'paid')
        {
            $errorString = "";
            foreach ($result->errors->deepAll() as $error) {
                $errorString .= 'Error: ' . $error->code . ": " . $error->message . "\n";
            }
            return redirect()->route('load.money',['type'=>'online'])->with('error', $errorString);
        }
        
        DB::beginTransaction();
        try {
            $payment_insert_data['order_id'] = $result->id;
            $payment_insert_data['item'] = $request->game;
            $this->saveTransactionInfo($result, $payment_insert_data);
            $user = Auth::user();
            $mails = User::role('admin')->pluck('email')->toArray();

            Mail::send('emails/admin/SendMoney',['data' => $user,'adminEmail' => $mails,'result' =>$result], function ($m) use ($mails) {
              $m->to($mails)->subject('Amount Received');
                });

            if(Auth::user()->cashier_id){
             
              $mail = Auth::user()->cashier_info->email;

              Mail::send('emails/admin/SendMoney',['data' => $user,'adminEmail' => $mails,'result' => $result], function ($m) use ($mail) {
              $m->to($mail)->subject('Amount Received');
                });
            }

            
            DB::commit();
            return redirect()->route('admin.loadmoney.request')->with('success','Payment has completed successfully.');
        }
        catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('load.money',['type'=>'online'])->with('error', $e->getMessage());
        }
    } 
      

    private function saveTransactionInfo($result, $transactionData = array())
    {
        $game = $transactionData['item'];
        unset($transactionData['item']);
        $transactionData['user_id'] = Auth::id();
        $transactionData['amount'] = $result->amount_total/100;
        $transactionData['currency'] = $result->currency;
        $transactionData['status'] = 'completed';
        $transactionData['txn_id'] = $result->payment_intent;

        $transactionInfo = Transaction::updateOrCreate(['order_id' => $result->id], $transactionData);
        // record an entry at payment infos
        $payment_data['user_id'] = Auth::id();
        $payment_data['status'] = 1;
        $payment_data['to_id'] = Auth::user()->cashier_id ? Auth::user()->cashier_id : 1;
        $payment_data['game'] = $game;
        $payment_data['payment_method'] = $result->payment_method_types[0];
        $payment_data['transaction_id'] = $transactionInfo->id;
        $payment_data['amount'] = $transactionInfo->amount;

        $transactionInfo->paymentInfo()->updateOrCreate(['transaction_id' => $transactionInfo->id], $payment_data);
        if (strtolower($transactionData['status']) == 'completed') 
        {
            // Check if Any Referral is here
            if (Session::has('referralBonus')) {
                // Referral to
                $referral_data['user_id'] = Auth::id();
                $referral_data['game'] = $game;
                $referral_data['to_id'] = Auth::user()->cashier_id ? Auth::user()->cashier_id : 1;
                $referral_data['status'] = 1;
                $referral_data['amount'] = Session::get('referralBonus', 0);
                $referral_data['referrer_id'] = Auth::user()->referred_by;
                $referral_data['payment_method'] = 'referre';
                $transactionInfo->paymentInfo()->updateOrCreate($referral_data);

                // Bonus for the person who refer this user
                $refree_data['user_id'] = Auth::user()->referred_by;
                $refree_data['game'] = $game;
                $refree_data['to_id'] = Auth::user()->referred_info->cashier_id ? Auth::user()->referred_info->cashier_id : 1;
                $refree_data['status'] = 1;
                $refree_data['amount'] = Session::get('referralBonus', 0);
                $refree_data['referrer_id'] = Auth::id();
                $refree_data['payment_method'] = 'referral';
                $transactionInfo->paymentInfo()->updateOrCreate($refree_data);
            }
            // Check if any promo is applied
            if (Session::has('promoCode')) {
                $promoCode = Session::get('promoCode', []);
                $promo_data['user_id'] = Auth::id();
                $promo_data['status'] = 1;
                $promo_data['game'] = $game;
                $promo_data['to_id'] = Auth::user()->cashier_id ? Auth::user()->cashier_id : 1;
                $promo_data['amount'] = $promoCode['discount_type']==1?floor($transactionInfo->amount*$promoCode['amount']/100):$promoCode['amount'];
                $promo_data['promo_name'] = $promoCode['name'];
                $promo_data['promo_discount'] = $promoCode['amount'];
                $promo_data['promo_type'] = $promoCode['discount_type'];
                $promo_data['payment_method'] = 'promo';
                $transactionInfo->paymentInfo()->updateOrCreate($promo_data);
                PromoCode::find($promoCode['id'])->users()->sync(Auth::id());
            }
            // Save Success info to the Paymnets_info table
        }
        return $transactionInfo->item;
    }

    public function saveOfflineRequest(Request $request)
    {

        $attributes = $request->all();
        $customMessages = [
            'lte' => 'The amount must be less than or equal $999999.99'
        ]; 
        $validateArray = array(
            'game' => 'required',
            'customer' => 'required',
            'amount'   => 'required|gte:20|lte:999999.99',
            'payment_method' => 'required'
        );

        $validator = Validator::make($attributes, $validateArray,  $customMessages);
        if ($validator->fails())
        {
            return response()->json(['success' => false, 'type' => 'validation-error', "error" => $validator->errors()]);
        }
        DB::beginTransaction();
        try{
            $PaymentInfos = new PaymentInfo;
            $PaymentInfos->user_id = $request['customer'];
            $PaymentInfos->game = $request['game'];
            $PaymentInfos->to_id = auth::user()->id;
            $PaymentInfos->transaction_id = null;
            $PaymentInfos->referrer_id = null;
            $PaymentInfos->amount = $request['amount'];
            $PaymentInfos->payment_method = $request['payment_method'];
            $PaymentInfos->type = 'Offline';
            $PaymentInfos->status = 2;
            $PaymentInfos->save();
            DB::commit();
            return response()->json(['success' => true , 'message' => 'Payment has been sent']);
        }
        catch(Exception $e){
            DB::rollback();
            return response()->json(['success' => false, 'error'=> $e->getMessage()]);
        }
    }   
}