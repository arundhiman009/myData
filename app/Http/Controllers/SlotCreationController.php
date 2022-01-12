<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CashoutSlot;
use App\Models\CashoutLocation;
use App\Models\CashoutSlotDate;
use DataTables;
use DB;
use Carbon\Carbon;


class SlotCreationController extends Controller
{
    public function index(Request $request){
		$data['getNotInSlot'] = DB::table("cashout_locations")->select('id', 'name')->where('status', '=', '1')->whereNotIn('id',function($query) {
		   $query->select('location_id')->from('cashout_slots');
		})->get()->toArray();
        $data['getAllLocation'] = CashoutLocation::select('id', 'name')->where('status', '=', '1')->get()->toArray();
		//dd($data['getLocation']);
        return view('admin.slot-creation',$data);
    }
    public function saveslot(Request $request){
        $attributes = $request->all();
            
        $validateArray = array(
            'location' => 'required',
            'slot_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',   
			'hidden_starttime'    => 'required|date',
			'hidden_endtime' => 'required|date|after_or_equal:hidden_starttime',
        );
        $validator = Validator::make($attributes, $validateArray);
        if ($validator->fails())
        {
            return response()->json(['success' => false, 'type' => 'validation-error', "error" => $validator->errors()]);
        }
        try{
			  
            $date = explode(",",$attributes['slot_date']);            
            $month = explode('-',$date[0]); 
            
            $location = new CashoutSlot;
            $location->location_id = $attributes['location'];
            $location->slot_month = $month[1];
            if(!empty($attributes['allmonth'])){
                $location->is_reacting = $attributes['allmonth'];
            }            
            $location->start_time = $attributes['start_time'];
            $location->end_time = $attributes['end_time'];           
            $location->save();
            $lastID = $location->id;            
            foreach($date as $slot_date){
                $slotdate = new CashoutSlotDate;
                $days = explode('-',$slot_date);             
                $slotdate->selected_day = $days[0];
                $slotdate->slot_id = $lastID; 
                $slotdate->save();
            } 
			$getNotInSlot = DB::table("cashout_locations")->select('id', 'name')->where('status', '=', '1')->whereNotIn('id',function($query) {
			$query->select('location_id')->from('cashout_slots');
			})->get()->toArray();
			$getAllLocation = CashoutLocation::select('id', 'name')->where('status', '=', '1')->get()->toArray();
			$get_not_in_slot ="";	
			$get_not_in_slot .= "<option value='' >Select</option>";		
			foreach($getNotInSlot as $getNotInSlots){
				$get_not_in_slot .= "<option value='".$getNotInSlots->id."'>".$getNotInSlots->name."</option>";		
			}
			$option_all_location ="";	
			$option_all_location .="<option value='' >Select</option>";			
			foreach($getAllLocation as $getAllLocations){
				$option_all_location .= "<option value='".$getAllLocations['id']."'>".$getAllLocations['name']."</option>";	
			}
			$data['getNotInSlot'] = $get_not_in_slot;
			$data['getAllLocation'] = $option_all_location;
            return response()->json(['success' => true, 'message'=>'Slot has been created successfully.','data'=>$data]);
        }
        catch(Exception $e){
            return response()->json(['success' => false, 'error'=>'Something went wrong.']);
        }        
    }
    public function slotlist(Request $request){
        $data = CashoutSlot::with(['location'])->orderBy('created_at','desc')->get();        
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('created_at', function($row){
                $created_at = casinoDate($row->created_at);
                return $created_at;
            })  
            ->addColumn('location', function($row){ 
                $name = $row->toArray();
                $location = $name['location']['name'];
                return $location;
             })      
            ->addColumn('action', function($row){              
                $userinfo = '<a href="javascript:;" data-id="'.$row->id.'" class="editlocation"><i class="fa fa-edit" aria-hidden="true"></i></a> | <a href="javascript:;" onclick="removelocation(this,'.$row->id.')" class="deletelocation" data-id="'.$row->id.'"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                return $userinfo;
            })  
            
            ->rawColumns(['created_at','slot_date','action'])     
            ->make(true);
    }
    public function getslot(Request $request){
        $attributes = $request->all();
        $data['data'] = CashoutSlot::where('id',$attributes['id'])->first();  
        $seleced_dates = CashoutSlotDate::where('slot_id',$attributes['id'])->get();      
       
       $arrDay = [];
        if(!empty($seleced_dates)){
            foreach($seleced_dates as $day){
                $arrDa[] = $day['selected_day'];
                //array_push($arrDay,$day['selected_day']);
            }
           $newArrDay = implode(',',$arrDa);
            $data['selected_date'] = $newArrDay;    
        }
    return response()->json(['success' => true, 'message'=>'match','data'=>$data]);   
    }
    public function updateslot(Request $request){       
        $attributes = $request->all();
        $validateArray = array(
            'location' => 'required',
            'slot_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required', 
			//'hidden_starttime'    => 'required|date',
			//'hidden_endtime' => 'required|date|after_or_equal:hidden_starttime',
        );
        $validator = Validator::make($attributes, $validateArray);
        if ($validator->fails())
        {
            return response()->json(['success' => false, 'type' => 'validation-error', "error" => $validator->errors()]);
        }
        try{
			if(!empty($attributes['allmonth'])){
                $location->is_reacting = $attributes['allmonth'];
            }
            $date = explode(",",$attributes['slot_date']);            
            $month = explode('-',$date[0]); 
            $attributes['slot_month'] = $month[1];
            $attributes['location_id'] = $attributes['location'];
            unset($attributes['location']);
			unset($attributes['hidden_starttime']);
			unset($attributes['hidden_endtime']);
            unset($attributes['slot_date']);
            unset($attributes['allmonth']);
            //$attributes['slot_date'] = Carbon::createFromFormat('Y M d', $attributes['slot_date'])->format('Y-m-d');
            CashoutSlot::where('id',$attributes['id'])->update($attributes);
            CashoutSlotDate::where('slot_id',$attributes['id'])->delete();
            foreach($date as $slot_date){                
                $slotdate = new CashoutSlotDate;
                $days = explode('-',$slot_date);             
                $slotdate->selected_day = $days[0];
                $slotdate->slot_id = $attributes['id']; 
                $slotdate->save();
            }
			$getNotInSlot = DB::table("cashout_locations")->select('id', 'name')->where('status', '=', '1')->whereNotIn('id',function($query) {
			   $query->select('location_id')->from('cashout_slots');
			})->get()->toArray();
			$getAllLocation = CashoutLocation::select('id', 'name')->where('status', '=', '1')->get()->toArray();
			$get_not_in_slot ="";	
			$get_not_in_slot .= "<option value='' >Select</option>";		
			foreach($getNotInSlot as $getNotInSlots){
				$get_not_in_slot .= "<option value='".$getNotInSlots->id."'>".$getNotInSlots->name."</option>";	
			}
			$option_all_location ="";	
			$option_all_location .="<option value='' >Select</option>";			
			foreach($getAllLocation as $getAllLocations){
				$option_all_location .= "<option value='".$getAllLocations['id']."'>".$getAllLocations['name']."</option>";	
			}
			$data['getNotInSlot'] = $get_not_in_slot;
			$data['getAllLocation'] = $option_all_location;	
            return response()->json(['success' => true, 'message'=>'Slot has been updated successfully.','data'=>$data]);
        }
        catch(Exception $e){
            return response()->json(['success' => false, 'error'=>'Something went wrong.']);
        }        
    }
    public function deleteslot(Request $request){
        $attributes = $request->all();
        try{
		CashoutSlotDate::where('slot_id',$attributes['id'])->delete();	
		CashoutSlot::where('id',$attributes['id'])->delete();	
		$getNotInSlot = DB::table("cashout_locations")->select('id', 'name')->where('status', '=', '1')->whereNotIn('id',function($query) {
		   $query->select('location_id')->from('cashout_slots');
		})->get()->toArray();
        $getAllLocation = CashoutLocation::select('id', 'name')->where('status', '=', '1')->get()->toArray();
		$get_not_in_slot ="";	
		$get_not_in_slot .= "<option value='' >Select</option>";		
		foreach($getNotInSlot as $getNotInSlots){
			$get_not_in_slot .= "<option value='".$getNotInSlots->id."'>".$getNotInSlots->name."</option>";	
		}
		$option_all_location ="";	
		$option_all_location .="<option value='' >Select</option>";			
		foreach($getAllLocation as $getAllLocations){
			$option_all_location .= "<option value='".$getAllLocations['id']."'>".$getAllLocations['name']."</option>";	
		}
		$data['getNotInSlot'] = $get_not_in_slot;
		$data['getAllLocation'] = $option_all_location;
		
        return response()->json(['success' => true, 'message'=>'Slot has been deleted successfully.','data'=>$data]);
        }
        catch(Exception $e){
            return response()->json(['success' => false, 'error'=>'Something went wrong.']);
        }    
    }
}
