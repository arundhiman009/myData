<?php

namespace App\Http\Controllers;

use Cache;
use Pusher\Pusher;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Chat;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
  protected $PUSHER_APP_KEY;
  protected $PUSHER_APP_SECRET;
  protected $PUSHER_APP_ID;
  protected $PUSHER_APP_CLUSTER;

  public function __construct()
  { 
    $this->PUSHER_APP_KEY = config('services.pusher.PUSHER_APP_KEY');
    $this->PUSHER_APP_SECRET = config('services.pusher.PUSHER_APP_SECRET');
    $this->PUSHER_APP_ID = config('services.pusher.PUSHER_APP_ID');
    $this->PUSHER_APP_CLUSTER = config('services.pusher.PUSHER_APP_CLUSTER');
  }

  public function pusherAuth(Request $request)
  {
    $attributes = $request->all();
    $user = Auth::user()->id;
    if (Auth::check()) {
      $options = array(
        'cluster' =>  $this->PUSHER_APP_CLUSTER,
        'useTLS' => true
      );
      try
      {
        $pusher = new Pusher(
          $this->PUSHER_APP_KEY,
          $this->PUSHER_APP_SECRET,
          $this->PUSHER_APP_ID,
          $options
        );
        $presence_data = ['user_id'=>Auth::user()->id,'email'=>Auth::user()->email];
        header('Content-Type: application/javascript');
        echo $pusher->socket_auth($attributes['channel_name'],$attributes['socket_id'],json_encode($presence_data));
        return;
      }
      catch(\Exception $e){
        return response()->json(['success'=>false,'error'=>$e->getMessage()]);
      }
    }
    else {
      header('', true, 403);
      echo "Forbidden";
      return;
    }
  }

  public function index()
  {
    $user_count = 0;
    $cashier_count = 0;
    if(Auth::user()->getRoleNames()->first() == 'Admin')
    {
      $users = User::where('id', '!=', 1)->where('status','=','1')->orderBy('name','asc')->pluck('username','id'); 
      
      $user_count = User::with('roles')->where(['status' => '1'])->whereHas('roles',function($role) {
        $role->where('name','User');
      })->count();

      $cashier_count = User::with('roles')->where(['status' => '1'])->whereHas('roles',function($role) {
        $role->where('name','Cashier');
      })->count();
    }
    else if(Auth::user()->getRoleNames()->first() == 'Cashier')
    {
      $users = User::where('id', '!=', Auth::user()->id)->where(['status' => '1', 'cashier_id' => Auth::user()->id])->orWhere(['id' => 1])->orderBy('name','asc')->pluck('username','id');

      $user_count = User::with('roles')->where(['status' => '1', 'cashier_id' => Auth::user()->id])->whereHas('roles',function($role) {
        $role->where('name','User');
      })->count();
    }
    else if(Auth::user()->getRoleNames()->first() == 'User')
    {
      $query = User::where('id', '!=', Auth::user()->id);
      if(Auth::user()->cashier_id) {
        $users = $query->where(['status' => '1', 'id' => Auth::user()->cashier_id])->orderBy('name','asc')->pluck('username','id'); 
      }
      else {
        $users = $query->where(['id' => 1])->orderBy('name','asc')->pluck('username','id');
      }
    }
    return view('chat.index', compact('users','user_count','cashier_count')); 
  }
  
  public function userChatList(Request $request)
  {
    try{
      $contact_list = Chat::with('toMessage','fromMessage')->where('is_last','1')->where(function ($query) {
                $query->where('sender_id', Auth::user()->id)
                ->orWhere('receiver_id', Auth::user()->id);
            })->orderBy('id','desc')->get();
      $user_list_count = $contact_list->count(); 
      return response()->json(['success'=>true, 'html'=>view('chat.ajax.user-chat-list',compact('contact_list'))->render(),'user_list_count'=>$user_list_count]);
    }
    catch(Exception $e){
      return response()->json(['success'=>false, 'error'=>$e->getMessage()]);
    }
  }

  public function fetchMessages(Request $request)
  {
    if($request->user_id)
    {
      $selected_group = '';
      $send_message = false;
      $user_id = $request->user_id;
      $sender_info = User::find($user_id);
      if(Auth::user()->getRoleNames()->first() == 'Cashier')
      {
        if($sender_info->getRoleNames()->first() == 'Admin')
        {
          $send_message = true;
        }
        else if($sender_info->status == 1 && $sender_info->cashier_id == Auth::user()->id)  
        {
          $send_message = true;
        }
      }
      else if(Auth::user()->getRoleNames()->first() == 'Admin')
      {
        if($sender_info->status == 1)  
        {
          $send_message = true;
        }
      }
      else if(Auth::user()->getRoleNames()->first() == 'User')
      {
        if($sender_info->status == 1 && Auth::user()->cashier_id == $sender_info->id)  
        {
          $send_message = true;
        }
        else if($sender_info->id == 1)  
        {
          $send_message = true;
        }
      }
      
      $query = Chat::with('fromMessage','toMessage')
      ->where(function ($query) use ($user_id) {
        $query->where('sender_id', $user_id)->where('receiver_id', Auth::user()->id);
      })->orWhere(function ($query) use ($user_id) {
        $query->where('sender_id', Auth::user()->id)->where('receiver_id', $user_id);
      });
      $max_count = $query->count();
      $notifyMessage = $query->paginate(30);
      $html = "";
      $reciever_name = "";
      $reciever_logo_text = "";
      $message = $query->latest()->first();
      if($notifyMessage->count()>0)
      {
        if($message->sender_id==Auth::user()->id){
          $reciever_name = $message->toMessage->username;
          $reciever_logo_text = getFirstLetterString($message->toMessage->username);
        }
        else if($message->receiver_id==Auth::user()->id)
        {
          $reciever_name = $message->fromMessage->username;
          $reciever_logo_text = getFirstLetterString($message->fromMessage->username);
        }
      }
      else{
        $max_count = 0;
        $reciever_name = "";
        $reciever_logo_text = "";
      }  
      $user_id = $request->user_id;
      Chat::where(['sender_id'=>$user_id,'receiver_id'=>Auth::user()->id])->update(['is_read' => '1']);
      return response()->json(['success'=>true,'html'=>view('chat.ajax.user-message-list',compact('notifyMessage','max_count','user_id','selected_group'))->render(),'reciever_name'=>$reciever_name,'reciever_logo_text'=>$reciever_logo_text,'max_count'=>$max_count,'send_message' => $send_message]);
    }
    return response()->json(['success'=>false,'error'=>'You are not authroised']);
  }
  
  public function fetchGroupMessages(Request $request){

    if($request->userRequest)
    {
      $selected_group = $request->userRequest;
      $send_message = true;
      $sender_info = [];
      if(Auth::user()->getRoleNames()->first() == 'Admin') {
        if($request->userRequest == "customer") {
          $recieverName ="Customers";
          $logoName = "C";
          $sender_info = User::with('roles')->where(['status' => '1'])->whereHas('roles',function($role) {
            $role->where('name','User');
          })->pluck('id')->toArray();
        }
        else if($request->userRequest == "cashier") {
          $recieverName ="Hosts";
          $logoName = "H";
          $sender_info = User::with('roles')->where(['status' => '1'])->whereHas('roles',function($role) {
            $role->where('name','Cashier');
          })->pluck('id')->toArray();
        }
      }
      else if(Auth::user()->getRoleNames()->first() == 'Cashier') {
        if($request->userRequest == "customer") {
          $recieverName ="Customers";
          $logoName = "C";
          $sender_info = User::with('roles')->where(['status' => '1', 'cashier_id' => Auth::user()->id])->whereHas('roles',function($role) {
            $role->where('name','User');
          })->pluck('id')->toArray();
        }
      }
  
      $query = Chat::where('sender_id',Auth::user()->id)->where('is_group','1')->groupBy('group_identifier');    
      $max_count = $query->count();
      $notifyMessage = $query->paginate(30);
      $html = "";
      $reciever_name = $recieverName;
      $reciever_logo_text = $logoName;
      $message = $query->latest()->first();

      if( count($sender_info) > 0) {
        $user_id = implode(',' , $sender_info);
      }

      return response()->json(['success'=>true,'html'=>view('chat.ajax.user-message-list',compact('notifyMessage','max_count','user_id','selected_group'))->render(),'reciever_name'=>$reciever_name,'reciever_logo_text'=>$reciever_logo_text,'max_count'=>$max_count,'send_message' => $send_message]);
    }
    return response()->json(['success'=>false,'error'=>'You are not authroised']);
    
  }

  public function sendMessage(Request $request)
  {

    $attributes = $request->all();
    $validateArray = array(
      'user_id'=>'required',
      'message_text'=>'required'
    );
    $validator = Validator::make($attributes, $validateArray); 
    if($validator->fails())
    {
      return response()->json(['success' => false, 'type' => 'validation-error', "error" => $validator->errors()]);
    }
    DB::beginTransaction();
    try{
      
      $user_id = $attributes['user_id'];
      $options = array(
        'cluster' => $this->PUSHER_APP_CLUSTER,
        'useTLS' => true
      );

      $pusher = new Pusher(
        $this->PUSHER_APP_KEY,
        $this->PUSHER_APP_SECRET,
        $this->PUSHER_APP_ID,
        $options
      );
      if(strpos($user_id, ','))
      {
        $useridslist = $user_id;
        $userIds = explode(",",$user_id);     
        
        do{
          $randomString = Str::random(40);
        } while(!empty(Chat::where('group_identifier',$randomString)->first()));
        
        foreach ($userIds as $key => $userid) {
          $userid = (int) $userid;
          $bulkMsg[] = [
            'sender_id' => Auth::user()->id, 
            'receiver_id' => $userid,
            'message' => $attributes['message_text'],
            'is_read' => '0',
            'is_last' => '1',
            'is_group' => 1,
            'group_identifier' => $randomString 
          ];

          $updateLastMessage = Chat::where(['sender_id'=>Auth::user()->id,'receiver_id'=>$userid])
          ->orWhere(function($query) use($userid) {
              $query->where(['receiver_id'=>Auth::user()->id,'sender_id'=>$userid]);
          })->update(['is_last' => '0']);
        }
      
        Chat::insert($bulkMsg);

        $logotext = getFirstLetterString(Auth::user()->username);      
        $heading_message_text = Auth::user()->username;
        $chat_date = setChatDate(Carbon::now());
        $last_short_message = limitString($attributes['message_text'], 10);

        $data = ['from' => Auth::user()->id, 'to' => $useridslist,'last_message'=>$attributes['message_text'],'message_id'=>1,'logo_text'=>$logotext,'heading_message_text'=>$heading_message_text,'chat_date'=>$chat_date,'last_short_message'=>$last_short_message]; // sending from and to user id when pressed enter

        $user = User::find(1);

        $pusher->trigger('send_message_channel', 'send_message_event', $data);
        DB::commit();
        return response()->json(['success'=>true,'message'=>'message has been sent']);
      }

      $updateLastMessage = Chat::where(['sender_id'=>Auth::user()->id,'receiver_id'=>$user_id])
      ->orWhere(function($query) use($user_id) {
          $query->where(['receiver_id'=>Auth::user()->id,'sender_id'=>$user_id]);
      })->update(['is_last' => '0']);
      $message = new Chat();
      $message->sender_id = Auth::user()->id;
      $message->receiver_id = $attributes['user_id'];
      $message->message = $attributes['message_text'];
      $message->is_read = '0';
      $message->is_last = '1';
      $message->save();
      Chat::where(['sender_id'=>$attributes['user_id'],'receiver_id'=>Auth::user()->id])->update(['is_read' => '1']);
      
      $logotext = getFirstLetterString(Auth::user()->username);
      $heading_message_text = Auth::user()->username;
      $chat_date = setChatDate($message->created_at);
      $last_short_message = limitString($message->message, 10);
      $data = ['from' => $message->sender_id, 'to' => $message->receiver_id,'last_message'=>$message->message,'message_id'=>$message->id,'logo_text'=>$logotext,'heading_message_text'=>$heading_message_text,'chat_date'=>$chat_date,'last_short_message'=>$last_short_message]; // sending from and to user id when pressed enter
      $user = User::find($message->receiver_id); 
      
      $pusher->trigger('send_message_channel', 'send_message_event', $data);

      // broadcast(new SendMessage($user,$data));
      DB::commit();
      return response()->json(['success'=>true,'message'=>'message has been sent']) ;
    }
    catch(\Exception $e)
    {
      DB::rollback();
      return response()->json(['success'=>false,'message'=>$e->getMessage()]) ;
    }
  }
}