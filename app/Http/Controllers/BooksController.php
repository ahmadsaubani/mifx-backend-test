<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Requests\PostBookRequest;
use App\Http\Resources\BookResource;
use App\Services\BookServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BooksController extends Controller
{
    protected $bookServices;

    public function __construct(BookServices $bookServices)
    {
        $this->bookServices = $bookServices;
    }

    public function index(Request $request)
    {
        // @TODO implement
        try {
            $books = $this->bookServices->query(Book::query(), $request->all());

            $perPage = $request->has("per_page") ? $request->per_page : 15;
    
            return BookResource::collection($books->paginate($perPage));
        } catch (Exception $e) {
           return $this->responseError($e->getMessage(), $e->getCode());
        }
        
    }

    public function store(PostBookRequest $request)
    {
        // @TODO implement
        $request->validated();
        try {
            DB::beginTransaction();
           
            $book = new Book();
            $book->savingBook($request->all());

            DB::commit();
            return new BookResource($book);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->responseError($e->getMessage(), $e->getCode());

        }
        
    }
}
