<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Book;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookCollection as BookResource;
use App\Http\Requests\BookRequest;
use Carbon\Carbon;


class BookController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendResponse( [
                "items"=> BookResource::collection(Book::all()->toArray())
            ],
            "Books Retrieved successfully!"
        );

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\RegisterRequest  $registerRequest
     * @return \Illuminate\Http\Response
     */
    public function store(BookRequest $request)
    {
        $book = new Book;
        $book->title = $request->title;
        $book->author = $request->author;
        $book->publish_date = Carbon::parse( $request->publish_date )->toDateTimeString();
        $book->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Book::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $book->update($request->all());

        return $this->sendResponse($book, 'Successfully update book');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return $this->sendResponse([], 'Successfully delete book');
    }
}
