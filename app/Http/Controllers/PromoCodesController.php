<?php

namespace App\Http\Controllers;

use DataTables;
use Carbon\Carbon;

use App\Models\PromoCode;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PromoCodesController extends Controller
{
    public function index(Request $request){
        return view('admin.promo-code');
    }
    public function savepromocode(Request $request){
        $attributes = $request->all();
        $validateArray = array(
            'name' => 'required|unique:promo_codes,name',
            'status' => 'required',
            'limit' => 'required',
            'discount_type' => 'required',
            'amount' => 'required',
            'expiry_date' => 'required'
        );
        $validator = Validator::make($attributes, $validateArray);
        if ($validator->fails())
        {
            return response()->json(['success' => false, 'type' => 'validation-error', "error" => $validator->errors()]);
        }
        try{
            $location = new PromoCode;
            $location->name = $attributes['name'];
            $location->status = $attributes['status'];
            $location->limit = $attributes['limit'];
            $location->discount_type = $attributes['discount_type'];
            $location->amount = $attributes['amount'];
            $location->expiry_date = Carbon::createFromFormat('m/d/Y', $attributes['expiry_date'])->format('Y-m-d');
            $location->save();
            return response()->json(['success' => true, 'message'=>'Promo Code has created successfully.']);
        }
        catch(Exception $e){
            return response()->json(['success' => false, 'error'=>'Something went wrong.']);
        }
    }
    public function promocodelist(Request $request){
        $data = PromoCode::orderBy('created_at','desc')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){
                $name =  ucwords($row->name);
                return $name;
            })
            ->addColumn('created_at', function($row){
                $created_at = casinoDate($row->created_at);
                return $created_at;
            })
            ->addColumn('expiry_date', function($row){
                $expiry_date =  casinoDate($row->expiry_date);
                return $expiry_date;
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
            ->addColumn('discount_type', function($row){
                $discount_type =  ucwords($row->discount_type == 0 ? 'Fixed' : 'Percentage');
                return $discount_type;
            })
            ->addColumn('action', function($row){
                $userinfo = '<a href="javascript:;" data-id="'.$row->id.'" class="editpromo"><i class="fa fa-edit" aria-hidden="true"></i></a> | <a href="javascript:;" onclick="removepromo(this,'.$row->id.')" class="deletepromo" data-id="'.$row->id.'"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                return $userinfo;
            })
            ->rawColumns(['created_at','expiry_date','action'])
            ->make(true);
    }
    public function getpromo(Request $request){
        $attributes = $request->all();
        
        try{
        $data = PromoCode::where('id',$attributes['id'])->first();
        return response()->json(['success' => true, 'message'=>'match','data'=>$data]);
        }
        catch(Exception $e){
            return response()->json(['success' => false, 'error'=>'Something went wrong.']);
        }
    }
    public function updatepromo(Request $request){
        $attributes = $request->all();
       
        $validateArray = array(
            'name' => 'required:unique:promo_codes,$this->id,id"',
            'status' => 'required',
            'limit' => 'required',
            'discount_type' => 'required',
            'amount' => 'required',
            'expiry_date' => 'required'
        );
        $validator = Validator::make($attributes, $validateArray);
        if ($validator->fails())
        {
            return response()->json(['success' => false, 'type' => 'validation-error', "error" => $validator->errors()]);
        }
        try{
            $attributes['expiry_date'] =  Carbon::createFromFormat('m/d/Y', $attributes['expiry_date'])->format('Y-m-d');
            $data = PromoCode::where('id',$attributes['id'])->update($attributes);
            return response()->json(['success' => true, 'message'=>'Promo Code has updated successfully.']);
        }
        catch(Exception $e){
            return response()->json(['success' => false, 'error'=>'Something went wrong.']);
        }
    }
    public function deletepromocode(Request $request){
        $attributes = $request->all();
        try{
        $data = PromoCode::where('id',$attributes['id'])->delete();
        return response()->json(['success' => true, 'message'=>'Promo code has deleted successfully.']);
        }
        catch(Exception $e){
            return response()->json(['success' => false, 'error'=>'Something went wrong.']);
        }
    }
}