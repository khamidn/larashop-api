<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Spanduks as SpanduksResource;
use App\Spanduk;
use Auth;

class SpandukController extends Controller
{
    public function index(){
    	$spanduk = Spanduk::orderBy('id','DESC')->get();
    	return new SpanduksResource($spanduk);
    }

    public function newSpanduk(Request $request){
    	$user = Auth::user();
    	$status = "error";
    	$message = "";
    	$data = null;
    	$code = 200;

    	if($user){
    		$this->validate($request, [
    			'name' => 'required|unique:spanduks|max:255',
    			'image_spanduk' => 'required',
    			'creator' => 'required|max:255',
    			'category' => 'required|max:255',
    		]);

    		$newSpanduk = new Spanduk();
    		$newSpanduk->name = $request->name;
    		$newSpanduk->creator = $request->creator;
    		$newSpanduk->category = $request->category;

    		if($request->image_spanduk){
    			$file = $request->image_spanduk;
				$name = time().'.'.$file->getClientOriginalExtension();
				$file->move(public_path('images/spanduks/'), $name);
				$newSpanduk->image_spanduk = $name;	
    		}

    		if($newSpanduk->save()){
    			$status = "success";
    			$message = "Spanduk baru berhasil ditambahkan";
    			$data = $newSpanduk->toArray();
    		}

    		else{
    			$message = "Menambahkan spanduk baru Failed!";
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

    public function update(Request $request, $id){
    	$user = Auth::user();
    	$spanduk = Spanduk::find($id);
    	$oldImage = $spanduk->image_spanduk;
    	$status = "error";
    	$message = "";
    	$data = null;
    	$code = 200;

    	if($user){
    		if($spanduk){
    			$this->validate($request, [
		    		'name' => 'required',
		    		'creator' => 'required',
		    		'category'=> 'required',
		     	]);

		     	$spanduk->name = $request->name;
		     	$spanduk->creator = $request->creator;
		     	$spanduk->category = $request->category;

    			if($request->image_spanduk){
    				$file = $request->image_spanduk;
    				$name = time().'.'.$file->getClientOriginalExtension();
    				$file->move(public_path('images/spanduks/'), $name);
    				unlink(public_path('images/spanduks/'). $oldImage);
    				$spanduk->image_spanduk = $name;

    			}
    			if($spanduk->save()){
    				$status = "success"; 
    				$message  = "Spanduk Berhasil Diperbarui";
    				$data = $spanduk->toArray();
    			}

    			else{
    				$message = "Update Spanduk Failed!";
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
    	],$code);
    }


    public function delete($id){
        $user =Auth::user();
        $spanduk = Spanduk::findOrFail($id);
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;


        if($user){
            if($spanduk){
                if($spanduk->delete()){
                    $status = "success";
                    $message = "Spanduk Berhasil dihapus";
                    $data = "";
                }
            }
            else{
                $message = "Delete Spanduk Failed!";
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

    public function trash(){
        $user = Auth::user();
        $trash = Spanduk::onlyTrashed()->get();
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;

        if($user){
            if($trash){
                $status = "success";
                $message = "List Trash from Spanduk";
                $data = $trash->toArray();
            }
        }
        else {
            $message = "user not found!";
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ],$code);
    }

    public function restore($id){
        $user = Auth::user();
        $spanduk = Spanduk::withTrashed()->findOrFail($id);
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;

        if($user){
            if($spanduk){
                if($spanduk->trashed()){
                    $spanduk->restore();
                    $status = "success";
                    $message = "Spanduk Berhasil direstore";
                    $data = "";

                }
                else{
                    $message = "Spanduk is not trash";
                }
            }

        }
        else {
            $message = "User not found";
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ],$code);

    }

    public function deletePermanet($id){
        $user = Auth::user();
        $spanduk = Spanduk::withTrashed()->findOrFail($id);
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;

        if($user){
            if($spanduk){
                if($spanduk){
                    $spanduk->forceDelete();
                    $status = "status";
                    $message ="Spanduk dihapus permanent";
                    $data = "";
                }
                else{
                    $message = "Tidak bisa hapus permanent active spanduk";
                }
            }
        }
        

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ],$code);

    }
}
