<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminApproval;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Support\Facades\Cookie;

use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            // 'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ])->validate();

        return DB::transaction(function () use ($input) {

            $cookie = Cookie::get('referral');
            $referred_by = $cookie ? \Hashids::decode($cookie)[0] : null;

            $user = User::create([
                // 'name' => $input['name'],
                'email' => $input['email'],
                'username' => $input['username'],
                'password' => Hash::make($input['password']),
                'referred_by' => $referred_by,
            ]);
       
         $user->assignRole('User');

        
        $admin = User::role('admin')->pluck('email');

         if($admin->count() >0 && $user){
         $mails = $admin->toArray();
      
             
             Mail::send('emails/users/WelcomeUserMail',['data' => $user, 'password'=>$input['password'], 'adminEmail' => $mails], function ($m) use ($user) {
              $m->to($user->email)->subject('Welcome Mail');
                });

              Mail::send('emails/admin/admin-approval',['data' => $user,'adminEmail' => $mails], function ($m) use ($mails) {
              $m->to($mails)->subject('Approval Request');
          });
        
     }
            return $user;
        });
    }
  }

