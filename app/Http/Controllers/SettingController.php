<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $admin = AdminSetting::all()->first();
        return view('settings.index',compact('admin'));
    }

    public function socialInfo(Request $request)
    {
        $id = null;
        $data = $request->validate([
            'content' => 'required|min:10',
            'tag' => 'required|min:5',
            'amount' => 'required|integer|min:0',
        ]);

        if($request->id){
            $id = $request->id;
        }

        $info = AdminSetting::updateOrCreate(['id'=>$id],$data);

        return response()->json(['success'=>true,'data'=>$info,'message'=>'Settings updated.'], 200);
    }
}
