<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Models\Book;
use OpenApi\Annotations as OA;

/**
 * Class BookController
 * 
 * @author Yobel
 */
class BookController extends Controller
{
    /** 
     * @OA\Get(
     *     path="/api/book",
     *     tags={"Book"},
     *     summary="Display a listing of the items",
     *     operationId="index",
     *     @OA\Response(
     *         response=200,
     *         description="successful",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function index()
    {
        return Book::get();
    }

    /**
     * @OA\Post(
     *     path="/api/book",
     *     tags={"Book"},
     *     summary="Store a newly created item",
     *     operationId="store",
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful",
     *         @OA\JsonContent()
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Request body description",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/Book",
     *             example={"title": "Makanan bersih", "author": "Yobel Kimtoputra", "publisher": "Bumi Pustaka", "publication_year": "2019",
     *                      "cover": "https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1482170055i/33511107.jpg",
     *                      "description": "Menjadi sehat adalah impian semua orang. Makanan yang selama ini kita pikir sehat ternyata belum tentu 'sehat' bagi tubuh kita.",
     *                      "price": 95000}
     *         ),
     *     ),
     *     security={{"passport_token_ready":{}, "passport":{}}}
     * )
     */
    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'title'  => 'required|unique:books',
                'author'  => 'required|max:100',
            ]);
            if ($validator->fails()) {
                throw new HttpException(400, $validator->messages()->first());
            }
            $book = new Book;
            $book->fill($request->all())->save();
            return $book;

        } catch(\Exception $exception) {
            throw new HttpException(400, "Invalid Data : {$exception->getMessage()}");
        }
    }

    /**
     * @OA\Get(
     *     path="/api/book/{id}",
     *     tags={"Book"},
     *     summary="Display the specified item",
     *     operationId="show",
     *     @OA\Response(
     *         response=404,
     *         description="Item not found",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of item that needs to be displayed",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     * )
     */
    public function show($id)
    {
        $book = Book::find($id);
        if(!$book){
            throw new HttpException(404, 'Item not found');
        }
        return $book;
    }

    /**
     * @OA\Put(
     *     path="/api/book/{id}",
     *     tags={"Book"},
     *     summary="Update the specified item",
     *     operationId="update",
     *     @OA\Response(
     *         response=404,
     *         description="Item not found",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of item that needs to be updated",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Request body description",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/Book",
     *             example={"title": "Eating Clean", "author": "Inge Tumiwa-Bachrens", "publisher": "Kawan Pustaka", "publication_year": "2016",
     *                      "cover": "https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1482170055i/33511107.jpg",
     *                      "description": "Menjadi sehat adalah impian semua orang. Makanan yang selama ini kita pikir sehat ternyata belum tentu 'sehat' bagi tubuh kita.",
     *                      "price": 95000}
     *         ),
     *     ),
     *     security={{"passport_token_ready":{}, "passport":{}}}
     * )
     */
    public function update(Request $request, string $id)
    {
        $book = Book::find($id);
        if(!$book){
            throw new HttpException(404, 'Item not found');
        }

        try{
            $validator = Validator::make($request->all(), [
                'title'  => 'required|unique:books',
                'author'  => 'required|max:100',
            ]);
            if ($validator->fails()) {
                throw new HttpException(400, $validator->messages()->first());
            }
           $book->fill($request->all())->save();
           return response()->json(array('message'=>'Updated successfully'), 200);

        } catch(\Exception $exception) {
            throw new HttpException(400, "Invalid Data : {$exception->getMessage()}");
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/book/{id}",
     *     tags={"Book"},
     *     summary="Remove the specified item",
     *     operationId="destroy",
     *     @OA\Response(
     *         response=404,
     *         description="Item not found",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of item that needs to be removed",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     security={{"passport_token_ready":{}, "passport":{}}}
     * )
     */
    public function destroy(string $id)
    {
        $book = Book::find($id);
        if(!$book){
            throw new HttpException(404, 'Item not found');
        }

        try {
            $book->delete();
            return response()->json(array('message'=>'Deleted successfully'), 200);

        } catch(\Exception $exception) {
            throw new HttpException(400, "Invalid data : {$exception->getMessage()}");
        }
    }
}
