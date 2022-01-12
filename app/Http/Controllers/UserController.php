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
use App\Models\UserPaymentMethods;

use Spatie\Permission\Models\Role as Roles;
use Spatie\Permission\Models\Permission as Permissions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function index(Request $request){

        $roles=Role::where('name','!=','admin')->get();
       
        $Users = User::whereHas('roles', function($q){
                    $q->where('name','!=','admin')->where('name','!=','User');
                })->get();
        return view('users.user-list',compact('Users'));
    }
    
    public function profile(){
        $payment_methods =[];   
        $data = UserPaymentMethods::where('user_id',auth::user()->id)->first();
        if($data){
            $payment_methods = unserialize($data->method_name);
        }
        return view('users.profile',compact('payment_methods'));
    }
    
    public function updateProfile(Request $request){
        $attributes = $request->all();

        $validateArray = array(
            'user_id' => 'required',
            'payment' => 'required'
                       
        );
        $validator = Validator::make($attributes, $validateArray);
        if ($validator->fails())
        {
            return response()->json(['success' => false, 'type' => 'validation-error', "error" => $validator->errors()]);
        }
        /*$update_values = array(
            
            'method_name' => serialize($request->payment)
        );*/
        try{
            UserPaymentMethods::updateOrCreate(['user_id'=>$request->user_id],['method_name'=>serialize($request->payment)]);
            
            return response()->json(['status'=>'success','success'=>true,'message'=>'Profile Updated']);
        }
        catch (\Exception $e){
           
            return response()->json(['status' => 'error', 'success'=>false,'type'=>'common-error', "error" => $e->getMessage()]);
        }
     
    }

    public function userlist(Request $request)
    {
        $query = User::whereHas('roles',function($query){
            $query->where('name','User');
        });

        if(Auth::user()->hasRole("Cashier"))
        {
            $query = $query->where('cashier_id',Auth::user()->id);
        }
        return Datatables::of($query)
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
              elseif ($row->status == 0)
                  {
                       $status ="Account Status: Admin Verfication Pending";
                  }
              elseif ($row->status == 2)
                  {
                     $status = "Account Status: Account Blocked by Admin";
                  }

              $email_verified = $row->email_verified_at =='' ? 'Email Verified : No' : 'Email Verified: Yes';

                $account_status =  $email_verified ."<br>". 
               $status  ; 

               

                return $account_status;
            })
            ->addColumn('amount_history', function($row){
            
            if(Auth::user()->getRoleNames()->first() == 'Admin'){
                $amount_history = " Loaded Money: $". number_format($row->transactions()->where('payment_method','!=','referre')->where('payment_method','!=','referral')->where('payment_method','!=','promo')->where('status',2)->sum('amount'),2) ??  0 ;

                $amount_history .= "<br> Cashout Money: $".number_format($row->cashouts()->where('status','2')->sum('amount'),2) ??  0;

                $amount_history .= "<br> Referral Money: $".number_format($row->transactions()->where(['payment_method'=>'referral','status'=> 2])->sum('amount'),2) ??  0;

                $amount_history .= "<br> Referre Money: $".number_format($row->transactions()->where(['payment_method'=>'referre','status'=> 2])->sum('amount'),2) ??  0;

                $amount_history .= "<br> Promo Money: $".number_format($row->transactions()->where(['payment_method'=>'promo','status'=> 2])->sum('amount'),2) ??  0;
            }
            else{

                $amount_history = " Loaded Money: $". number_format($row->transactions()->where('payment_method','!=','referre')->where('payment_method','!=','referral')->where('payment_method','!=','promo')->where('to_id',Auth::user()->id)->where('status',2)->sum('amount'),2) ??  0 ;

                $amount_history .= "<br> Cashout Money: $".number_format($row->cashouts()->where('user_id',Auth::user()->id)->where('status','2')->sum('amount'),2) ??  0;

                $amount_history .= "<br> Referral Money: $".number_format($row->transactions()->where(['payment_method'=>'referral','status'=> 2])->where('to_id',Auth::user()->id)->sum('amount'),2) ??  0;

                $amount_history .= "<br> Referre Money: $".number_format($row->transactions()->where(['payment_method'=>'referre','status'=> 2,'to_id' => Auth::user()->id])->sum('amount'),2) ??  0;

                $amount_history .= "<br> Promo Money: $".number_format($row->transactions()->where(['payment_method'=>'promo','status'=> 2])->where('to_id',Auth::user()->id)->sum('amount'),2) ??  0;


            }
                

                return $amount_history;
            })

            ->addColumn('created_at', function($row){
                $created_at = casinoDate($row->created_at);
                return $created_at;
            })
           
         
            ->addColumn('cashier', function($row){

            if( $row->cashier_id == ''){

               $cashier = "No Host Assigned Yet"; 

              }
            else{

                $user = User::find($row->cashier_id);

                if($user){

                       $cashier =  ucwords($user->username);
                    }
                else{

                       $cashier = "Host removed"; 
                    }
                }
                
                return $cashier;
            })

            
            ->addColumn('action', function($row){
                $action = '<a href="javascript:;" data-id="'.$row->id.'" class="edituser" title="Change User status"><i class="fa fa-edit"  aria-hidden="true"></i></a>| <a href="javascript:;" class="assignUser" title="Assign to Host" data-id="'.$row->id.'"><i class="fas fa-user-edit" aria-hidden="true"></i></a>';
                return $action;
            })
       

            ->rawColumns(['created_at','action','userinfo','account_status','amount_history'])
            ->make(true);
    }


public function userView(Request $request){


    $roles=Role::where('name','!=','admin')->get();
    
    $Users=User::whereHas(
                  'roles', function($q){
                $q->where('name','!=','Admin')->where('name','!=','User');
                   }
                 )->get();

    
  $usersdata = User::role('User')->get();
   
    return view('admin.all-users',compact('Users','usersdata','roles'));
}

public function userlistPermission(Request $request){
        $data = User::orderBy('created_at','desc')->where('role_name','User');

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('created_at', function($row){
                $created_at = casinoDate($row->created_at);
                return $created_at;
            })
          
            ->addColumn('referral_earnings', function($row){
                $amount = $row->transactions()->where(['payment_method'=>'referre','status'=>'completed'])->sum('amount') ?? 0;
                return amountFormat($amount);
            })
           
            ->addColumn('Check', function($row){
                $check ='<input type="checkbox" class="checkbox" id="user_id[]" name="user_id[]" value="'.$row->id.'">';

                return $check;
            })
            ->addColumn('action', function($row){
                $userinfo = '<a href="javascript:;" data-id="'.$row->id.'" class="editlocation"><i class="fa fa-edit" aria-hidden="true"></i></a> | <a href="javascript:;" onclick="removelocation(this,'.$row->id.')" class="deletelocation" data-id="'.$row->id.'"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                return $userinfo;
            })

            ->rawColumns(['created_at','action','Check'])
            ->make(true);
    }

 
    public function getUser(Request $request){

    $attributes = $request->all();

        $validateArray = array(
            'id' => 'required',
                       
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
    try{

     if($user->status != 0){
            $data = '<option value="1">Active</option><option value="2">Block</option>';
       }
    else{
          $data='<option value="0">Pending</option><option value="1">Approve</option>';
        }  

        return response()->json(['success' => true, 'user'=>$user, 'data' => $data]);
        /* return $user = User::find($request->id);*/
    }
     catch(Exception $e){
            {
                
                return response()->json(['success' => false, 'error'=>'Something went wrong.']); 
            }
    }
}

    public function adminApprove(Request $request){
        $attributes = $request->all();

        $validateArray = array(
            'id' => 'required',
            'user_status' => 'required',
            
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
            db::commit();
            return response()->json(['success' => true, 'message'=>'Status updated.']);
            }
     catch(Exception $e){
            {
                db::rollback();
                return response()->json(['success' => false, 'error'=>'Something went wrong.']); 
            }
        
    }
}

    Public function getAllCashier(Request $request){
    $attributes = $request->all();

    $validateArray = array(
            'id' => 'required',
           
            
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
   try{

    $data = "<option value=''>No Host</option>";
    $cashier = User::role('Cashier')->where('status','1')->pluck('username','id');
    
    if($cashier->count() > 0){
         foreach ($cashier as $key => $value) {
            $data .= "<option value=".$key.">".$value."</option>";
         }
       }
    

    return response()->json(['success' => true, 'user'=>$user, 'data' =>$data]);
   }
    catch(Exception $e){
           
            return response()->json(['success' => false, 'error'=>'Something went wrong.']);
        }

    }


   public function cashierAssign(Request $request){

   $attributes = $request->all();
    $validateArray = array(
            'id' => 'required',
            
            
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
            $user->cashier_id = $request->cashier_id;
            $user->save();            
            
            DB::commit();
            return response()->json(['success' => true, 'message'=>'Host Assigned.']);
         
        }
        catch(Exception $e){
            DB::rollback();
            return response()->json(['success' => false, 'error'=>'Something went wrong.']);
        }

   }


//     public function assignPermissionUser(Request $request){

//     $user = User::find($request->id);
//     $role = $user->getRoleNames();
//     $user->roles()->detach();
//     $result=$user->assignRole($request->user_role);

//     if($result){
//          return response()->json(['success' => true, 'message'=>'Status updated.']);
//     }
//     else{

//       return response()->json(['success' => false, 'error'=>'Something went wrong.']);
//     }
// }

/* public function assigntouser(Request $request){

        $roleName = $request->role_name;
        $UserIds= $request->user_id;
        foreach ($UserIds as $UserId) {   

            $assignUser = new RoleHasUser;
            $assignUser->user_role_id  = $roleName;
            $assignUser->assign_user_id = $UserId;
            $assignUser->save();
            
        }
        return redirect()->back()->with('success','success!');
    }
*/

/*
    public function newUser(){

       $roles=Role::where('name','!=','admin')->get();
         
       return view('admin.new-user',compact('roles'));  

    }
*/

/*public function createUser(Request $request){

       $attributes = $request->all();
       $email= $request->email;
      
        $validateArray = array(
            'firstname' => 'required',
            'username' => 'required',
            'email' => 'required', 
            'password' => 'required', 
            
        );  
        
    $validator = Validator::make($attributes, $validateArray);
    if ($validator->fails())
        {
            return response()->json(['success' => false, 'type' => 'validation-error', "error" => $validator->errors()]);
        }
    $email= $request->email;
    $userData = Auth::user();
  try{
            $user = new User;
            $user->name = $attributes['firstname'];
            $user->lastname = $attributes['lastname'];
            $user->email = $attributes['email'];
            $user->username = $attributes['username'];

            $user->password = Hash::make($attributes['password']);
            
         
           $result= $user->save();
           if($result)
             { 
                 try{
                 $user->assignRole('User');
           Mail::send(new SendRegisterUser($userData, $email));
           } 
        catch(\Exception $ex){
            return response()->json(['success'=>false,'message'=>$ex->getMessage()], 200);
             }
          
      return response()->json(['success'=>True,'message'=>'User Create and Email has sent succesfully']);
          }
                     
        }
        catch(Exception $e){
            return response()->json(['success' => false, 'error'=>'Something went wrong.']);
        }  



}*/




}
