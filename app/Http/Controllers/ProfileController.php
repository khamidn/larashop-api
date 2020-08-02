<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use Auth;
use File;

class ProfileController extends Controller
{
    public function updateKontak(Request $request){
    	$user = Auth::user();
    	$status = "error";
    	$message = "";
    	$data = null;
    	$code = 200;

    	if($user){
    		$this->validate($request, [
                'name' => 'required', 
                'email' => 'required'
            ]);

            $user->name = $request->name;
            $user->email = $request->email;
            if($user->save()){
            	$status = "success";
                $message = "Informasi Kontak Berhasil Diperbarui";
                $data = $user->toArray();   
            }
            else{
                $message = "Update Informasi Kontak Failed!";
            }  
    	}

    	else{
            $message = "User not found";
        }
        
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function updateShipping(Request $request){
    	$user = Auth::user();
    	$status = "error";
    	$message = "";
    	$data = null;
    	$code = 200;

    	if($user){
    		$this->validate($request, [
    			'address' => 'required',
    			'phone' => 'required',
    			'province_id' => 'required',
    			'city_id' => 'required',
    		]);

    		$user->address = $request->address;
            $user->phone = $request->phone;
            $user->province_id = $request->province_id;
            $user->city_id = $request->city_id;

    		if($user->save()){
    			$status = "success";
                $message = "Update Alamat Penagihan/Alamat Pengiriman Berhasil";
                $data = $user->toArray();
    		}
    		else{
    			$message = "Update Alamat Penagihan/Alamat Pengiriman Failed!";
    		}
    	}

    	else{
    		$message = "user not found";
    	}

    	return response()->json([
    		'status' => $status,
    		'message' => $message,
    		'data' => $data
    	],$code);
    }

    public function gantiFotoProfil(Request $request){
    	$user = Auth::user();
    	$oldAvatar = Auth::user()->avatar;
    	$status = "error";
    	$message = "";
    	$data = null;
    	$code = 200;

    	if($user){
    		$this->validate($request, [
    			'avatar' => 'image|mimes:jpeg,png,jpg|max:2000'
    		]);
    		$file = $request->avatar;
    		if($oldAvatar){
    			// hapus foto sebelumnya terlebih dahulu
    			unlink(public_path('images/users/'). $oldAvatar);
    		}
    		$imageName = time(). '.'.$file->getClientOriginalExtension();
    		$file->move(public_path('images/users/'), $imageName);
    		$user->avatar = $imageName;

    		if($user->save()){
    			$status = "success";
    			$message = "Update Foto Profil Berhasil";
    			$data = $user->toArray();
    		}
    		else{
    			$message = "Update Foto Profil Failed!";
    		}
    	}

    	else {
    		$message = "User not found";
    	}

    	return response()->json([
    		'status' => $status,
    		'message' => $message,
    		'data' => $data
    	], $code);
    }
}
