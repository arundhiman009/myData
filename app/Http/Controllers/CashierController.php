<?php

namespace App\Http\Controllers;

use DB;
use DataTables;
use Carbon\Carbon;

use App\Mail\SendRegisterUser;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Role;
use App\Models\RoleHasUser;

use Spatie\Permission\Models\Role as Roles;
use Spatie\Permission\Models\Permission as Permissions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;

class CashierController extends Controller
{
    public function index(Request $request) {
        return view('cashier.cashier-list');
    }

    public function cashierlist(Request $request) {
        $data = User::orderBy('created_at','desc')->whereHas('roles',function($query){
            $query->where('name','!=','User')->where('name','!=','Admin');
        });

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('userinfo', function($row){
                $userinfo = $row->username."<br>".$row->email; 
                return $userinfo;
            })
            ->addColumn('account_status', function($row){
                if($row->status == 1)
                { 
                    $status = "Account Status: Active"; 
                }
                elseif ($row->status == 2) {
                    $status = "Account Status: Account Blocked by Admin";
                }
                $email_verified = $row->email_verified_at =='' ? 'Email Verified : No' : 'Email Verified: Yes';
                $account_status =  $email_verified ."<br>". 
                $status; 
                return $account_status;
            })
            ->addColumn('customer', function($row){
                $user = user::where('cashier_id',$row->id)->get();
                if($user){
                    $customer = $user->count();
                }
                else{
                    $customer="No Customer Assigned yet";     
                }
                return $customer;
            })
           ->addColumn('amount_history', function($row){
                $amount_history = " Loaded Money: $". number_format($row->paymentsInfo()->where('payment_method','!=','referre')->where('payment_method','!=','referral')->where('payment_method','!=','promo')->where('status',2)->sum('amount'),2) ??  0 ;

                $amount_history .= '<br> Earned Amount: $'. number_format($row->paymentsInfo()->where('payment_method','!=','referre')->where('payment_method','!=','referral')->where('payment_method','!=','promo')->where('status',2)->sum('amount')/5,2) ??  0 ;

                $amount_history .= '<br> Cash Out Request: $'.number_format($row->cashier_cashouts()->where('status','2')->sum('amount'),2);
                return $amount_history;
            })
            ->addColumn('created_at', function($row){
                $created_at = casinoDate($row->created_at);
                return $created_at;
            })
            ->addColumn('action', function($row){
                $userinfo = '<a href="javascript:;" data-id="'.$row->id.'" class="editcashier" title="Change User status"><i class="fa fa-edit"  aria-hidden="true"></i></a>';
                return $userinfo;
            })
            ->rawColumns(['created_at','action','userinfo','account_status','Amount_history','amount_history'])
            ->make(true);
    }

    public function addcashier(Request $request){

        $attributes =$request->all();
        $validateArray = array(
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string','min:4', 'max:255', 'unique:users'],
            'password' =>['required', 'string','min:6', 'max:10']                  
        );

        $validator = Validator::make($attributes, $validateArray);
        if ($validator->fails())
        {
            return response()->json(['success' => false, 'type' => 'validation-error', "error" => $validator->errors()]);
        }

        $email= $request->email;
        $adminData = Auth::user();
        DB::beginTransaction();
        try{

            $user = new User;
            $user->username = $attributes['username'];
            $user->email = $attributes['email'];
            $user->status = '1';       
            $user->password = Hash::make($attributes['password']);           
            $user->save();
            $user->assignRole('Cashier');

            event(new Registered($user));

            Mail::send(new SendRegisterUser($adminData, $email , $user,$attributes['password']));
            DB::commit();
            return response()->json(['success'=>True,'message'=>'Host Created and Email has sent successfully']);
        }
        catch(Exception $e){
            DB::rollback();
            return response()->json(['success' => false, 'error'=>'Something went wrong.']);
        }
    }

    public function getCashier(Request $request){
        $attributes = $request->all();

    $validateArray = array(
            'id' => 'required'                     
            
    );

        return $user = User::find($request->id);
    }
    
    public function CashierApproveStatus(Request $request){
     
       $attributes = $request->all();

    $validateArray = array(
            'id' => 'required',
            'user_status' => 'required'           
            
    );

    $validator = Validator::make($attributes, $validateArray);
     if ($validator->fails())
        {
            return response()->json(['success' => false, 'type' => 'validation-error', "error" => $validator->errors()]);
        }
        $user = User::find($request->id);
        if(!$user)
        {
            return response()->json(['success' => false, 'error'=>'User is not exist. Please try again.']); 
        }
        DB::beginTransaction();
        try{
            $user->status = $request->user_status;             
            $user->save();

            if($request->user_status == '2'){
              User::where('cashier_id',$request->id)->update(['cashier_id'=> NULL]); }                   
        
            DB::commit();
            return response()->json(['success' => true, 'message'=>'Status updated.']);
        }
        catch(Exception $e){
            DB::rollback();
            return response()->json(['success' => false, 'error'=>'Something went wrong.']);
        }
    }
}