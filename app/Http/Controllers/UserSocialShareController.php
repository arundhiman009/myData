<?php

namespace App\Http\Controllers;

use App\Mail\SendReferralMail;
use App\Models\AdminSetting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Vinkla\Hashids\Facades\Hashids;

class UserSocialShareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $info = AdminSetting::all()->first();
        $referal_link =  url('/').'/?ref='. Hashids::encode(auth()->user()->id);
        return view('users.social-share',compact('referal_link','info'));
    }

    public function sendInvitation(Request $request)
    {

       $referal_link =  url('/').'/?ref='. Hashids::encode(auth()->user()->id);
       $email =  $request->validate([
            'invitation_email' => 'required|email|unique:users,email',
        ]);
        $adminEmail = User::role('Admin')->pluck('email');

        $user = Auth::user();
        try{
            Mail::send(new SendReferralMail($user, $email,$referal_link,$adminEmail));
        } catch(\Exception $ex){
            return response()->json(['success'=>false,'message'=>$ex->getMessage()], 200);
        }

        return response()->json(['success'=>true,'message'=>'Invitation email sent.'], 200);
    }

    public function messages()
    {
        return [
            'invitation_email.unique' => 'User is already registered',
        ];
    }
}
