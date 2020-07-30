<?php

namespace App\Http\Controllers;

use App\Book;
use App\Jobs\BookStoreJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class BookController extends Controller
{

    /**
     * @OA\Get(
     *      path="/api/books",
     *      operationId="getBooksList",
     *      tags={"Books"},
     *      security={
     *          {"bearer": {}},
     *      },
     *      summary="Get list of books",
     *      description="Returns list of books",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @OA\Response(response=400, description="Bad request")
     *     )
     *
     * Returns list of projects
     */
    public function index()
    {
        return Book::with('categories:name,book_id')->get();
    }


    /**
     * @OA\Get(
     *      path="/api/books/{id}",
     *      operationId="getBook",
     *      tags={"Books"},
     *      security={
     *          {"bearer": {}},
     *      },
     *      summary="Get book",
     *      description="Returns book",
     *      @OA\Parameter(
     *          name="id",
     *          description="Book id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @OA\Response(response=400, description="Bad request")
     *     )
     *
     * Returns list of projects
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        if (Book::whereId($id)->exists()) {
            return Book::with('categories:name,book_id')->find($id);
        }
        return response()->json(["message" => "Book with id {$id} not Found"], 404);
    }

    /**
     * @OA\Post(
     *      path="/api/books/",
     *      operationId="Store book data",
     *      tags={"Books"},
     *      security={
     *          {"bearer": {}},
     *      },
     *      summary="Store book",
     *      description="Store the book with success response",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="title",
     *                     type="string"
     *                 ),
     *                 example={"title": "Jessica Smith", "description": "about book", "categories": {{"name": "first"}, {"name": "second"}}}
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @OA\Response(response=400, description="Bad request")
     *     )
     *
     * Returns list of projects
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:books',
        ]);
        if ($validator->fails()) {
            Log::error($validator->errors());
            return response()->json($validator->errors(), 400);
        }

        BookStoreJob::dispatch($request->all())->onQueue('store');
        return response()->json(['message' => 'success'], 201);
    }

    /**
     * @OA\Delete(
     *      path="/api/books/{id}",
     *      operationId="Delete book",
     *      tags={"Books"},
     *      security={
     *          {"bearer": {}},
     *      },
     *      summary="Delete book",
     *      description="Delete the book",
     *     @OA\Parameter(
     *          name="id",
     *          description="Book id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @OA\Response(response=400, description="Bad request")
     *     )
     *
     * Returns list of projects
     * @param $id
     * @return \Illuminate\Http\JsonResponse|bool
     * @throws \Exception
     */
    public function destroy($id) {
        if (Book::whereId($id)->exists() && Book::destroy($id)) {
            return response()->json(['message' => "Book with id {$id} deleted"]);
        }
        return response()->json(["message" => "Book with id {$id} not Found"], 404);
    }
}
