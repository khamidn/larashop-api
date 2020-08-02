<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\About;
use Auth;

use App\Http\Resources\About as AboutResources;

class AboutController extends Controller
{
    public function index(){
    	$about = About::findOrFail(1);
    	return new AboutResources($about);
    }

    public function update(Request $request){
    	$user = Auth::user();
    	$about= About::find(1);
        $oldImage = $about->image_company;
    	$status = "error";
    	$message = "";
    	$data = null;
    	$code = 200;

    	if($user){
    		if($about){
    			$this->validate($request, [
    				'title' => 'required',
    				'description' => 'required', 
    			]);

    			$about->title =$request->title;
    			$about->description = $request->description;
                
                

    			if($request->image){
    				$file = $request->image;
    				$name = time(). '.'.$file->getClientOriginalExtension();
    				$file->move(public_path('images/abouts/'), $name);
                    
    				// if($oldImage){
    					unlink(public_path('images/abouts/'). $oldImage);
    				// }
    				$about->image_company = $name;
    			}

    			if($about->save()){
    				$status = "success";
    				$message = "About Company Berhasil Diperbarui";
    				$data = $about->toArray();
    			}
    			else{
    				$message = "Update About Company Failed!";
    			}
    		}

    	}
    	else{
    		$message = "user not found";
    	}

    	return response()->json([
    		'status' => $status,
    		'message' => $message,
    		'data' => $data,
    	], $code);
    }
}
