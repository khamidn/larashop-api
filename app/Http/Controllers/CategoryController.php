<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Categories as CategoryResourceCollection;
use App\Http\Resources\Category as CategoryResource;
use Auth;
use App\Category;

class CategoryController extends Controller
{
    public function random($count){
        $criteria = Category::select('*')
            ->inRandomOrder()
            ->limit($count)
            ->get();
        return new CategoryResourceCollection($criteria);
    }
    public function index()
    {
        $criteria = Category::paginate(6);
        return new CategoryResourceCollection($criteria);
    }

    public function showAddCategory(){
        $user = Auth::user();
        $category = Category::get();
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;
        if($user){
            if($category){
                $status = "success";
                $message = "List Category Product";
                $data = $category->toArray();
            }

        }
        else{
            $message = "User not found";
        }
        
        return response()->json([
            'status' => $status,
            'message'=> $message,
            'data' => $data,
        ], $code);
    }

    public function slug($slug){
        $criteria = Category::where('slug', $slug)->first();
        return new CategoryResource($criteria);
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;

        if ($user){
            $this->validate($request, [
                'name'=> 'required|unique:categories|max:255',
            ]);

            $create = new Category();
            $name = $request->name;
            $create->name = $name;
            $create->slug = str_slug($name, '-');
            $create->created_by = $user->id;

            if($request->image_category){
                $file = $request->image_category;
                $nameFile = time(). '.'.$file->getClientOriginalExtension();
                $file->move(public_path('images/categories/'), $nameFile);
                $create->image = $nameFile;
            }
            $create->status = 'PUBLISH';

            if($create->save()){
                $status = "success";
                $message = "Spanduk baru berhasil ditambahkan";
                $data = $create->toArray();
            }
            else{
                $message = "Menambahkan Category baru Failed!";
            }
        }
        else{

        }
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ],$code);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $category = Category::find($id);
        $oldImage=$category->image;
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;

        if($user){
            if($category){
                $this->validate($request, [
                    'name' => 'required',
                ]);
                $name = $request->name;
                $category->name = $name;
                $category->slug = str_slug($name, '-');
                $category->status = 'PUBLISH';
                $category->updated_by = $user->id;
                

                if($request->image_category){
                    $file = $request->image_category;
                    $namaFile = time().'.'.$file->getClientOriginalExtension();
                    $file->move(public_path('images/categories/'), $namaFile);
                    unlink(public_path('images/categories/'). $oldImage);
                    $category->image = $namaFile;
                }
                if($category->save()){
                    $status = "success";
                    $message = "Spanduk Berhasil Diperbarui";
                    $data = $category->toArray();
                }

                else{
                    $message = "Update Spanduk Failed";
                }
            }

        }
        else{
            $message = "user not found";
        }

        return response()->json([
            'status' => $status,
            'message'=> $message,
            'data' => $data,
        ], $code);
    }

    public function delete($id)
    {
     
     $user = Auth::user();
     $category = Category::findOrFail($id);
     $status = "error";
     $message = "";
     $data = null;
     $code = 200;

     if($user){
        if($category){
            if($category->delete()){
                $status = "success";
                $message = "Category Berhasil dihapus";
                $data = "";
            }

        }
        else{
            $message = "Delete Category Failed!";
        }

     } 
     else{
        $message = "User not found";
     }

     return response()->json([
        'status'=> $status,
        'message' => $message,
        'data' => $data
     ],$code);
    }

    public function trash(){
        $user = Auth::user();
        $trash = Category::onlyTrashed()->get();
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;

        if($user){
            if($trash){
                $status = "success";
                $message = "List Trash from Category";
                $data = $trash->toArray();
            }
        }
        else{
            $message = "User not found!";
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $code);
        
    }

    public function restore($id){
        $user = Auth::user();
        $category = Category::withTrashed()->findOrFail($id);
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;

        if($user){
            if($category){
                if($category->trashed()){
                    $category->restore();
                    $status = "success";
                    $message = "Category Berhasil direstore";
                    $data = "";
                }
                else{
                    $message = "Category is not in trash";
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

    public function deletePermanent($id){
        $user = Auth::user();
        $category = Category::withTrashed()->findOrFail($id);
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;

        if($user){
            if($category){
                if($category->trashed()){
                    $category->forceDelete();
                    $status = "success";
                    $message = "Category dihapus permanent";
                    $data = "";
                    
                }
                else{
                    $message = "Tidak Bisa hapus permanent active category";
                }
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
}
