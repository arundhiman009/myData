<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CashoutLocation;
use App\Models\CashoutSlot;
use App\Models\CashoutSlotDate;
use DataTables;
use Carbon\Carbon;


class CashoutLocationController extends Controller
{
    public function index(Request $request){  
        return view('admin.cashout-location');
    }
    public function savelocation(Request $request){
        $attributes = $request->all();
       
        $validateArray = array(
            'name' => 'required',
            'city' => 'required',
            'state' => 'required', 
            'pincode' => 'required', 
            'address' => 'required', 
        );
        $validator = Validator::make($attributes, $validateArray);
        if ($validator->fails())
        {
            return response()->json(['success' => false, 'type' => 'validation-error', "error" => $validator->errors()]);
        }
        try{
            $location = new CashoutLocation;
            $location->name = $attributes['name'];
            $location->city = $attributes['city'];
            $location->state = $attributes['state'];
            $location->pincode = $attributes['pincode'];
            $location->address = $attributes['address'];
			$location->status = $attributes['status'];
            $location->lat = $attributes['lat'];
            $location->lng = $attributes['lng'];
            $location->save();
            return response()->json(['success' => true, 'message'=>'Location has been created successfully.']);
        }
        catch(Exception $e){
            return response()->json(['success' => false, 'error'=>'Something went wrong.']);
        }        
    }
    public function locationlist(Request $request){
        $data = CashoutLocation::orderBy('created_at','desc')->get();        
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('created_at', function($row){
                $created_at =  casinoDate($row->created_at);
                return $created_at;
            })
           
            ->addColumn('action', function($row){
                $userinfo = '<a href="javascript:;" data-id="'.$row->id.'" class="editlocation"><i class="fa fa-edit" aria-hidden="true"></i></a> | <a href="javascript:;" onclick="removelocation(this,'.$row->id.')" class="deletelocation" data-id="'.$row->id.'"><i class="fa fa-trash" aria-hidden="true" ></i></a>';
                return $userinfo;
            }) 
			->addColumn('status', function($row){
                if($row->status==0){
                    $txt = 'Inactive';    
                }else{
                    $txt = 'Active';    
                }
                $status = $txt;
                return $status;
            }) 
            ->rawColumns(['created_at','action'])      
            ->make(true);
    }
    public function updatelocation(Request $request){       
        $attributes = $request->all();
        $validateArray = array(
            'name' => 'required',
            'city' => 'required',
            'state' => 'required', 
            'pincode' => 'required', 
            'address' => 'required', 
        );
        $validator = Validator::make($attributes, $validateArray);
        if ($validator->fails())
        {
            return response()->json(['success' => false, 'type' => 'validation-error', "error" => $validator->errors()]);
        }
        try{
            $data = CashoutLocation::where('id',$attributes['id'])->update($attributes);
            return response()->json(['success' => true, 'message'=>'Location has been updated successfully.']);
        }
        catch(Exception $e){
            return response()->json(['success' => false, 'error'=>'Something went wrong.']);
        }        
    }
    public function getlocation(Request $request){
        $attributes = $request->all();
        $data = CashoutLocation::where('id',$attributes['id'])->first();
        return response()->json(['success' => true, 'message'=>'match','data'=>$data]);
    }
    public function deletelocation(Request $request){
        $attributes = $request->all();
        try{
		$slot_id = CashoutSlot::where('location_id',$attributes['id'])->first(); 
        CashoutLocation::where('id',$attributes['id'])->delete();       
		if(!empty($slot_id) || !is_null($slot_id)){	
			CashoutSlotDate::where('slot_id',$slot_id->id)->delete();
			CashoutSlot::where('id',$slot_id->id)->delete();			
		} 	
       
        return response()->json(['success' => true, 'message'=>'Location has been deleted successfully.']);
        }
        catch(Exception $e){
            return response()->json(['success' => false, 'error'=>'Something went wrong.']);
        }    
    }
}

