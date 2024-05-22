<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Models\Book;

class BookController extends Controller
{
    public function index()
    {
        return Book::all();
    }

    public function store(Request $request)
    {
        try {
            $book = new Book;
            $book->fill($request->all())->save();

            return $book;

        } catch(\Exception $exception) {
            throw new HttpException(400, "Invalid data - {$exception->getMessage()}");
        }
    }

    public function show($id)
    {
        $book = Book::findOrFail($id);

        return $book;
    }

    public function update(Request $request, $id)
    {
        if(!$id) {
            throw new HttpException(400, "Invalid id");
        }

        try{
            $book = Book::findOrFail($id);
            $book->fill($request->all())->save();

            return $book;

        } catch(\Exception $exception) {
            throw new HttpException(400, "Invalid data - {$exception->getMessage()}");
        }
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json(null, 204);
    }
}
