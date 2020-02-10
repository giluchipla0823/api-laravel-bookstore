<?php

namespace App\Http\Controllers\Api\V1\Book;

use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Yajra\Datatables\Datatables;
use App\Http\Requests\BookRequest;
use App\Libraries\MyArraySerializer;
use App\Transformers\BookTransformer;
use App\Http\Controllers\ApiController;

class BookDatatablesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param BookRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function index(BookRequest $request)
    {
        $data = $request->all();

        $query = new Book();

        if(array_key_exists('includes', $data) && $data['includes']){
            $includes = explode(',', $data['includes']);

            $query = $query->with($includes);
        }

        if(isset($data['title'])){
            $query = $query->where('title', 'LIKE', "%" . $data['title'] . "%");
        }

        if(isset($data['author']) && isset($data['author']['name'])){
            $authorName = $data['author']['name'];

            $query = $query->whereHas('author', function($q) use($authorName){
                $q->where('name', 'LIKE', "%" . $authorName . "%");
            });
        }

        if(isset($data['publisher']) && isset($data['publisher']['name'])){
            $publisherName = $data['publisher']['name'];

            $query = $query->whereHas('publisher', function($q) use($publisherName){
                $q->where('name', 'LIKE', "%" . $publisherName . "%");
            });
        }

        $books = $query->get();

        $books = Datatables::of($books)
            ->setTransformer(new BookTransformer())
            ->make(TRUE);

        return $this->successResponse($this->datatablesTransformResponse($books->getData()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
}
