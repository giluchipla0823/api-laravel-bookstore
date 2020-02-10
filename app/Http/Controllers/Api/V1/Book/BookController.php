<?php

namespace App\Http\Controllers\Api\V1\Book;

use App\Helpers\AppHelper;
use App\Http\Controllers\ApiController;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\BookRequest;
use Symfony\Component\HttpFoundation\Response;

class BookController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param BookRequest $request
     * @return JsonResponse
     */
    public function index(BookRequest $request)
    {
        $data = $request->all();

        $query = new Book();

        if($includes = AppHelper::getIncludesFromUrl()){
            $query = $query->with($includes);
        }

        if(isset($data['title'])){
            $query = $query->where('title', 'LIKE', "%" . $data['title'] . "%");
        }

        if(isset($data['author']) && isset($data['author']['id'])){
            $authorId = $data['author']['id'];

            $query = $query->whereHas('author', function($q) use($authorId){
                        $q->where('id', $authorId);
                     });
        }

        if(isset($data['author']) && isset($data['author']['name'])){
            $authorName = $data['author']['name'];

            $query = $query->whereHas('author', function($q) use($authorName){
                        $q->where('name', 'LIKE', "%" . $authorName . "%");
                     });
        }

        if(isset($data['publisher']) && isset($data['publisher']['id'])){
            $publisherId = $data['publisher']['id'];

            $query = $query->whereHas('publisher', function($q) use($publisherId){
                $q->where('id', $publisherId);
            });
        }

        if(isset($data['publisher']) && isset($data['publisher']['name'])){
            $publisherName = $data['publisher']['name'];

            $query = $query->whereHas('publisher', function($q) use($publisherName){
                        $q->where('name', 'LIKE', "%" . $publisherName . "%");
                     });
        }

        $books = $query->get();

//        if($request->get('listFormat') === 'datatables'){
//            $books = Datatables::of($books)
//                ->setTransformer(new BookDatatablesTransformer)
//                ->orderByNullsLast()
//                ->make(true);
//
//            // return $books;
//
//            $books = $this->datatablesResponse($books->getData());
//
//            return $this->successResponse($books);
//        }

        return $this->showAll($books);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BookRequest $request
     * @return JsonResponse
     */
    public function store(BookRequest $request)
    {
        if($book = Book::create($request->all())){
            $book->genres()->sync($request->get('genres'));
        }

        return $this->showMessage('Book created successfully', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param BookRequest $request
     * @param Book $book
     * @return JsonResponse
     */
    public function show(BookRequest $request, Book $book)
    {
        return $this->showOne($book);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BookRequest $request
     * @param Book $book
     * @return JsonResponse
     */
    public function update(BookRequest $request, Book $book)
    {
        if($book->fill($request->all())->save()){
            $book->genres()->sync($request->get('genres'));
        }

        return $this->showMessage('Book updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Book $book
     * @return JsonResponse
     */
    public function destroy(Book $book)
    {
        Book::destroy($book->id);

        return $this->showMessage('Book removed successfully');
    }

    /**
     * Enable the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function restore($id){
        Book::withTrashed()->findOrFail($id)->restore();

        return $this->showMessage('Book restored successfully');
    }
}
