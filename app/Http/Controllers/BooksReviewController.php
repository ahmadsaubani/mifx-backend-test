<?php

namespace App\Http\Controllers;

use App\Book;
use App\BookReview;
use App\Http\Requests\PostBookReviewRequest;
use App\Http\Resources\BookReviewResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class BooksReviewController extends Controller
{
    public function __construct()
    {

    }

    public function store(int $bookId, PostBookReviewRequest $request)
    {
        // @TODO implement
        $request->validated();
        try {
            DB::beginTransaction();
            $user       = Auth::user();
            $book       = Book::find($bookId);

            if (!$book) {
                throw new ModelNotFoundException('Book not found by ID ' . $bookId, Response::HTTP_NOT_FOUND);
            }

            $bookReview             = new BookReview();
            $bookReview->savingBookReview($user, $book, $request->all());

            DB::commit();

            return new BookReviewResource($bookReview);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseError($e->getMessage(), $e->getCode());
        }
    }

    public function destroy(int $bookId, int $reviewId, Request $request)
    {
        // @TODO implement
        $book       = Book::find($bookId);

        if (!$book) {
            throw new ModelNotFoundException('Book not found by ID ' . $bookId, Response::HTTP_NOT_FOUND);
        }

        $review       = BookReview::find($reviewId);

        if (!$review) {
            throw new ModelNotFoundException('Review not found by ID ' . $reviewId, Response::HTTP_NOT_FOUND);
        }

        try {
            DB::beginTransaction();

            $review->delete();

            DB::commit();
            return response()->json([], Response::HTTP_NO_CONTENT);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseError($e->getMessage(), $e->getCode());
        }
    }
}
