<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WithdrawMoneyNotController extends Controller
{
    public function getMoney(Request $request){
        $user = $request->user();
        $role = $user->getRoleNames()->first();
        $user->role = $role;

        return view('user.withdraw-money', compact('user'));
    }
}
