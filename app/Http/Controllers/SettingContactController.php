<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Contact;
use App\Province;
use Auth;

use App\Http\Resources\Contact as ContactResource;

class SettingContactController extends Controller
{
    public function index(){
    	$contact = Contact::first();
    	return new ContactResource($contact);

    }

    public function update(Request $request){
    	$user = Auth::user();
    	$contact = Contact::findOrFail(1);
    	$status = "error";
    	$message = "";
    	$data = null;
    	$code = 200;

    	if($user){
    		if($contact){
	    			$this->validate($request, [
		    			'email'				=> 'required|email',
		    			'phone'				=> 'required|max:12',
		    			'address'			=> 'required|max:255',
		    			'province_id' 		=> 'required|numeric',
		    			'city_id'			=> 'required|numeric',
		    			'postal_code' 		=> 'required|max:5',
	    			]);


		    		$contact->email 			= $request->email;
		    		$contact->phone 			= $request->phone;
		    		$contact->address 			= $request->address;
		    		$contact->province_id 		= $request->province_id;
		    		$contact->city_id 			= $request->city_id;
		    		$contact->postal_code 		= $request->postal_code;

	    		if($contact->save()){
	    			$status = "success";
	    			$message = "Contact Toko Telah Diperbarui";
	    			$data = $contact->toArray();
	    		}
	    		else{
	    			$message = "Update Contact Toko Failed!";
	    		}
    		}
    	}
    	else{
    		$message = "User not found";
    	}

    	return response()->json([
    		'status' => $status,
    		'message' => $message,
    		'data' => $data,
    	], $code);
    }
}
