<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class SocialLoginController extends Controller
{
    //


 public function redirectToProvider($service)
    {
    	// dd($service);
        return Socialite::driver($service)->redirect();
    }

 public function handleProviderCallback($service ,Request $request)
    {

       if (!$request->input('code')) {
        
          return redirect()->route('home.page');
        }


    else{

        try{

            $userSocial = Socialite::driver($service)->user();

        }catch(\Exception $error){

            $userSocial = Socialite::driver($service)->stateless()->user();

        }

        $find_user = User::where('email',$userSocial->email)->first();


        if($find_user){
            Auth::login($find_user);
            return redirect()->route('dashboard');
        }else{

           $user = new User;
           $user->name      =     $userSocial->name;
           $user->email     =     $userSocial->email != '' ? $userSocial->email : $userSocial->name.'@'.$service.'.com';
           $user->username  =      $userSocial->name;
        //    $user->role_name =       'User';
           $user->password  =    Hash::make($userSocial->name);
           $user->password  =    Hash::make($userSocial->name);
           $user->email_verified_at  =    Carbon::now();
           $data= $user->save();

           if($data){

               // Assign role
               $user->assignRole('User');
                Auth::login($user);
            // notify()->success('Registration Successfull !');

            return redirect()->route('dashboard');
             }
          else{
        // notify()->success('Registration Unsuccessfull !');
        return redirect()->route('home.page');

       }
        }
    }

  }

}
