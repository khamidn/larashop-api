<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
use App\Http\Resources\Books as BookResourceCollection;
use App\Http\Resources\Book as BookResource;
use Auth;
use App\Book;
use App\Cover;
use App\BookCover;
use App\BookCategory;

class BookController extends Controller
{
    public function index(){
    	$criteria = Book::with('covers')->paginate(6);
        return new BookResourceCollection($criteria);
    }

    public function top($count){
    	$criteria = Book::select('*')
    		->orderBy('views', 'DESC')
    		->limit($count)
    		->with('covers')
            ->get();
    	return new BookResourceCollection($criteria);
    }

    public function slug($slug){
        $criteria = Book::where('slug', $slug)->first();
        return new BookResource($criteria);
    }

    public function search($keyword){
        $criteria = Book::select('*')
            ->where('title','LIKE', "%".$keyword."%")
            ->orderBy('views','DESC')
            ->with('covers')
            ->get();
        return new BookResourceCollection($criteria);
    }

    public function findBook($id){
        $book = Book::where('id',$id)->with('covers')->get();
        return new BookResourceCollection($book);

    }


    public function showProduct(){
        $user = Auth::user();
        $product = Book::with('categories','covers')->get();
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;
        if($user){
            if($product){
                $status = "success";
                $message = "List Product";
                $data = $product->toArray();
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

    public function create(Request $request){
        $user = Auth::user();
        $status ="error";
        $message = "";
        $data = [];
        $code = 200;

        if ($user){
            $product = new Book;
            $title = $request->title;
            $product->title = $title;
            $product->slug = str_slug($title, '-');
            $product->description = $request->description;
            $product->author = $request->author;
            $product->publisher = $request->publisher;
            $product->price = $request->price;
            $product->weight = $request->weight;
            $product->stock = $request->stock;
            $product->status= 'PUBLISH';
            
            // $product->categories()->attach($request->categories);

            if($product->save()){
                $status = "success";
                $message = "Book baru berhasil ditambahkan";
                $data = $product->toarray();

                if($request->cover){
                    // foreach($request->cover as $cover){
                        $file = $request->cover;
                        $namaFile = time(). '.'.$file->getClientOriginalExtension();
                        $move = $file->move(public_path('images/books/'), $namaFile);
                            if($move){
                                $new_cover = new Cover;
                                $new_cover->file_name = $namaFile;
                                $new_cover->save();

                                $new_book_cover = new BookCover;
                                $new_book_cover->book_id = $product->id;
                                $new_book_cover->cover_id = $new_cover->id;
                                $new_book_cover->save(); 
                            }
                    // }
                }

                if($request->categories){
                    // foreach($request->categories as $categories){
                        $book_category = new BookCategory;
                        $book_category->book_id = $product->id;
                        $book_category->category_id = $request->categories;
                        // $book_category->category_id = $categories;
                        $book_category->save();
                    // }
                }
                
            }

            else {
                $message = "Menambahkan Buku Baru Failed";
            }

        }

        else {
            $message = "user not found";
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function update(Request $request, $id){}

    public function delete($id){
        $user = Auth::user();
        $product = Book::findOrFail($id);
        $status ="error";
        $message = "";
        $data = null;
        $code = 200;

        if($user){
            if($product){
                if($product->delete()){
                    $status = "success";
                    $message = "Product Berhasil dihapus";
                    $data = "";
                }
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

    public function trash(){
        $user = Auth::user();
        $trash = Book::onlyTrashed()->with('categories', 'covers')->get();
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;

        if($user){
            if($trash){
                $status = "success";
                $message = "List Trash form Product";
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
        ],$code);
    }
    public function restore($id){
        $user = Auth::user();
        $product = Book::withTrashed()->findOrFail($id);
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;

        if($user){
            if($product){
                if($product->trashed()){
                    $product->restore();
                    $status = "success";
                    $message = "Product Berhasil direstore";
                    $data = "";
                }

            }
            else {
                $message = "Product is not in trash";
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
    public function deletePermanent($id){}
}
