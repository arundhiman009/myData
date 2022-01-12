<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Game;
use Illuminate\Support\Facades\Validator;
use DataTables;

class GameController extends Controller
{
    function index(){
    	return view('admin.game'); 
    }

    public function gameslist(Request $request)
    {
        $data = Game::orderBy('created_at','desc');

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){
                $name =  ucwords($row->name);
                return $name;
            })
            ->addColumn('image', function($row){
                $image = '<img src="'.asset('assets/images/'.$row->image).'" height="50" width="50";/>';
                return $image;
            })
            ->addColumn('url', function($row){
                $url =  $row->download_path;
                return $url;
            })
             ->addColumn('created_at', function($row){
                $created_at =  $row->created_at;
                return $created_at;
            })
            ->addColumn('action', function($row){
                $userinfo = '<a href="javascript:;" data-id="'.$row->id.'" class="editpromo"><i class="fa fa-edit" aria-hidden="true"></i></a> | <a href="javascript:;" onclick="removepromo(this,'.$row->id.')" class="deletepromo" data-id="'.$row->id.'"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                return $userinfo;
            })
            ->rawColumns(['action','image'])
            ->make(true);
    }

    public function savegame(Request $request){    	
   
        $attributes =$request->all();
        $imagename =$request->file('file_image');
        $validateArray = array(
            'name' => 'required|unique:promo_codes,name',
            'file_image' => 'required',
            'download_path' => 'required',                  
        );

        $validator = Validator::make($attributes, $validateArray);

        if ($validator->fails())
        {
            return response()->json(['success' => false, 'type' => 'validation-error', "error" => $validator->errors()]);
        }
        try{

        	$image= $imagename->getClientOriginalName();
            $imagename->move(public_path('assets/images'), $image);
      
            $game = new Game;
            $game->name = $attributes['name'];
            $game->image = $image;
            $game->download_path = $attributes['download_path'];
            
            $game->save();
            return response()->json(['success' => true, 'message'=>'Game has been created successfully.']);
        }
        catch(Exception $e){
            return response()->json(['success' => false, 'error'=>'Something went wrong.']);
        }
    }

    public function gamedelete(Request $request)
    {
        $attributes = $request->all();
        try{
        $data = Game::where('id',$attributes['id'])->delete();
        return response()->json(['success' => true, 'message'=>'Game has been deleted successfully.']);
        }
        catch(Exception $e){
            return response()->json(['success' => false, 'error'=>'Something went wrong.']);
        }
    }

    public function updategame(Request $request)
    {
        $attributes = $request->all();
        $image=$request->file('image');
        $validateArray = array(
            'name' => 'required',
            'download_path' => 'required',
            
        );
        $validator = Validator::make($attributes, $validateArray);
        if ($validator->fails())
        {
            return response()->json(['success' => false, 'type' => 'validation-error', "error" => $validator->errors()]);
        }
        try{
            if(!empty($image))
            {
                $image=$request->file('image');
                $imageName= $image->getClientOriginalName();
                $image->move(public_path('assets/images'), $imageName);
                $image_path = public_path('assets/images/'.$attributes['image_name']);
                unlink($image_path);
                $data = [
                    'id' => $attributes['id'],
                    'name' => $attributes['name'],
                    'image' =>$imageName,
                    'download_path' => $attributes['download_path']
                ];

            }
            else{
            	$data =[
                    'id' => $attributes['id'],
                    'name' => $attributes['name'],
                    'image' =>$attributes['image_name'],
                    'download_path' => $attributes['download_path']
                ];
            }
            
            $data = Game::where('id',$attributes['id'])->update($data);
            return response()->json(['success' => true, 'message'=>'Game has been updated successfully.']);
        }
        catch(Exception $e){
            return response()->json(['success' => false, 'error'=>'Something went wrong.']);
        }
    }
    
    public function getgame(Request $request){
        $attributes = $request->all();
        
        try{
        $data = Game::where('id',$attributes['id'])->first();
        return response()->json(['success' => true, 'message'=>'match','data'=>$data]);
        }
        catch(Exception $e){
            return response()->json(['success' => false, 'error'=>'Something went wrong.']);
        }
    }
}