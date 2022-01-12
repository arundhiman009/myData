<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use DataTables;
use Carbon\Carbon;

use App\Models\User;
use App\Models\PaymentInfo;
use App\Models\UserPaymentMethods;
use App\Models\CashoutSlot;
use App\Models\CashoutRequest;
use App\Models\CashoutLocation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CashoutRequestController extends Controller
{
    public function index(Request $request) 
    {

        $locations = CashoutLocation::where('status','1')->get();
        $cashier_list = [];
        $customer_list = [];
        $payment_methods =[];
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
        else if(Auth::user()->getRoleNames()->first() == 'User'){          
            if(Auth::user()->cashier_id){
                $data = UserPaymentMethods::where('user_id',Auth::user()->cashier_id)->first();
                if($data){
                    $payment_methods = unserialize($data->method_name);
                }
            }       
            else{
                $data = User::role('admin')->select('id')->first();
                $data = UserPaymentMethods::where('user_id',$data->id)->first();
                if($data){
                    $payment_methods = unserialize($data->method_name);
                }
            }
            
        }
      
        return view('cashoutrequest.index',compact('locations','cashier_list','customer_list','payment_methods'));
    }

    public function store(Request $request) 
    {
        
        $attributes = $request->all();
        $customMessages = [
            'lte' => 'The amount must be less than or equal $999999.99'
        ]; 
        $validateArray = array(
            'amount' => 'required|lte:999999.99',
            'method' => 'required',
            'paypal_id' => 'required_without:offline_location_id',
            'offline_location_id' => 'required_without:paypal_id',
            'offline_location_slot' => 'required_without:paypal_id'
        );
        $validator = Validator::make($attributes, $validateArray, $customMessages);
        if ($validator->fails())
        {
            return response()->json(['success' => false, 'type' => 'validation-error', "error" => $validator->errors()]);
        }
        $id = $request->id ?? null;
        $data = $validator->valid();
        $data['offline_location_slot'] = $request->offline_location_slot ? Carbon::parse($request->offline_location_slot)->format('Y-m-d') : NULL;
        $data['to_id'] = Auth::user()->cashier_id ? Auth::user()->cashier_id : 1;

        if($request->method == 1)
        {
            $data['offline_location_id'] = $data['offline_location_slot']  = null;
        }
        try{
            $data['user_id'] = Auth::id();
            CashoutRequest::updateOrCreate(['id'=>$id], $data);
            return response()->json(['success' => true, 'message'=>'Request has sent successfully.']);
        }
        catch(Exception $e){
            return response()->json(['success' => false, 'error'=>'Something went wrong.']);
        }
    }

    public function list(Request $request) 
    {
        if(Auth::user()->getRoleNames()->first() == 'Admin'){
            $query = CashoutRequest::with(['user','to_user']); 
        }
        elseif(Auth::user()->getRoleNames()->first() == 'Cashier')
        {     
            $query = CashoutRequest::with(['user','to_user'])->where('to_id',auth::user()->id);
        }
        elseif(Auth::user()->getRoleNames()->first() == 'User'){
            $query = CashoutRequest::with(['user','to_user'])->where('user_id',auth::user()->id);
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
            $query->where('method',$search_request_type);
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

        return Datatables::of($query)
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
            ->addColumn('payment_info', function($row){
                $response = $row->method == '1' ?'<b>Mode:</b> Online<br/>':'<b>Mode:</b> PickUp<br/>';
                $response .= "<b>Amount:</b> $" .number_format($row->amount,2);
                return $response;
            })
            ->addColumn('payment_source', function($row){
                if($row->method == '1')
                {
                    $response = '<b>Payment Method:</b> '.$row->paypal_id;
                }
                else {
                    $response = '<b>Location:</b> '.$row->location->name.' ('.$row->location->cashoutSlot->start_time.' - '.$row->location->cashoutSlot->end_time.')<br>';
                    $response .= '<b>Date:</b> '.casinoDate($row->offline_location_slot);
                }
                return $response;
            })
            ->addColumn('created_at', function($row){
                return casinoDate($row->created_at);
            })
            ->addColumn('status', function($row){
                $status = getCashoutRequestStatus($row->status);
                $status .= "<br>".$row->comments;
                return $status;
            })
            ->addColumn('action', function($row){
                $userinfo = '';
                if(Auth::user()->getRoleNames()->first() == 'User' && $row->status=='0'){
                    $userinfo .= '<a href="javascript:;" data-off_loc_slot="'.Carbon::parse($row->offline_location_slot)->format('Y-m-d').'" data-user-name="'.$row->user->username.'" data-off_loc="'.$row->offline_location_id.'" data-pay_id="'.$row->paypal_id.'" data-mode="'.$row->method.'" data-id="'.$row->id.'" data-amt="'.$row->amount.'" class="editRequest"><i class="fa fa-edit" style="color:white" aria-hidden="true"></i></a> | <a href="javascript:;" onclick="removeRequest(this,'.$row->id.')" class="deleteRequest" data-id="'.$row->id.'"><i class="fa fa-trash" style="color:white" aria-hidden="true"></i></a>';
                } 
                elseif(Auth::user()->getRoleNames()->first() == 'Admin' || Auth::user()->getRoleNames()->first() == 'Cashier'){
                    $userinfo .= '<a href="javascript:;" data-user-name="'.$row->user->username.'" data-user_id="'.$row->user->id.'" data-off_loc_slot="'.Carbon::parse($row->offline_location_slot)->format('m/d/Y').'" data-payment-location="'.$row->location['name'].'('.$row->location['cashoutSlot']['start_time'].'-'. $row->location['cashoutSlot']['end_time'] .') " data-comments="'.$row->comment.'" data-status="'.$row->status.'" data-off_loc="'.$row->offline_location_id.'" data-pay_id="'.$row->paypal_id.'" data-mode="'.$row->method.'" data-id="'.$row->id.'" data-amt="'.$row->amount.'" class="approveBox"><i class="fa fa-edit" aria-hidden="true" style="color:white"></i></a>';
                }
                return $userinfo;
            })
            ->rawColumns(['username', 'cashierinfo', 'payment_info', 'payment_source', 'created_at', 'status', 'action'])
            ->make(true);
    }

    public function getLocationSlots(Request $request)
    {
        $dat = [];

        $validator = Validator::make($request->all(), ['location' => 'required']);

        if ($validator->fails())
        {
            return response()->json(['success' => false, 'type' => 'validation-error', "error" => $validator->errors()]);
        }

        $location = CashoutLocation::find($request->location);

        if($location){
            $slots = collect($location->cashoutSlot()->with('dates',function($query){
                return $query->orderBy('selected_day');
            })->get());

        }

        $final =  $slots->filter(function ($value, $key) {
            return ($value['slot_month'] == Carbon::now()->isoFormat('M') || $value['is_reacting'] == 1);
        })->first();
        $currentDate=Carbon::now()->format('Y-m-d');

        if($final){
         $dat = $final->dates->map(function($item) {
            $currentDate=Carbon::now()->format('Y-m-d');
            $dates= Carbon::now()->startOfMonth()->addDay(($item['selected_day'] - 1))->format('Y-m-d'); // 2019-04-15
            if($dates > $currentDate){
                return $dates;
            }

            });

        }

        return response()->json(['success' => true, 'data'=>$dat->filter()->values()]);
    }

    public function delete(Request $request)
    {
        $attributes = $request->all();
        $validateArray = array(
            'id' => 'required',
        );
        $validator = Validator::make($attributes, $validateArray);
        if ($validator->fails())
        {
            return response()->json(['success' => false, "error" => $validator->errors()]);
        }
        try{
            $data = Auth::user()->cashouts()->where(['status' => '0', 'id' => $attributes['id']])->delete();
            return response()->json(['success' => true, 'message'=>'Request has been deleted successfully.']);
        }
        catch(Exception $e){
            return response()->json(['success' => false, 'error'=>$e->getMessage()]);
        }
    }

    public function updateRequestStatus(Request $request)
    {
        $attributes = $request->all();
        $validateArray = array(
            'id' => 'required|integer',
            'status' => 'required|integer',
            'comment' => 'required|min:5',
        );
        $validator = Validator::make($attributes, $validateArray);
        if ($validator->fails())
        {
            return response()->json(['success' => false, 'type' => 'validation-error', "error" => $validator->errors()]);
        }

        $cashout_info = CashoutRequest::find($request->id);
        if(!$cashout_info)
        {
            return response()->json(['success' => false, "error" => 'Cash out request not found.']);
        }

        try{
            if( Auth::user()->getRoleNames()->first() == 'Cashier' && $cashout_info->to_id != Auth::id())
            {
                return response()->json(['success' => false, 'error'=>'You can not change the status of this cashout request.']);
            }
            $cashout_info->status = $request->status;
            $cashout_info->comment = $request->comment;
            $cashout_info->save();
            return response()->json(['success' => true, 'message'=>'Cashout Request has updated Successfully.']);
        }
        catch(Exception $e){
            return response()->json(['success' => false, 'error'=>$e->getMessage()]);
        }
    }
}