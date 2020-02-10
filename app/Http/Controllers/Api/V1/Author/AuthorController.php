<?php

namespace App\Http\Controllers\Api\V1\Author;

use App\Models\Author;
use App\Helpers\AppHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\AuthorRequest;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param AuthorRequest $request
     * @return JsonResponse
     */
    public function index(AuthorRequest $request)
    {
        $data = $request->all();

        $query = new Author;

        if($includes = AppHelper::getIncludesFromUrl()){
            $query = $query->with($includes);
        }
        
        if(isset($data['name'])){
            $query = $query->where('name', 'LIKE', "%" . $data['name'] . "%");
        }

        $authors = $query->get();

        return $this->showAll($authors);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AuthorRequest $request
     * @return JsonResponse
     */
    public function store(AuthorRequest $request)
    {
        Author::create($request->all());

        return $this->showMessage('Author created successfully', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Author $author
     * @return JsonResponse
     */
    public function show(Author $author)
    {
        return $this->showOne($author);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AuthorRequest $request
     * @param Author $author
     * @return JsonResponse
     */
    public function update(AuthorRequest $request, Author $author)
    {
        $author->fill($request->all())->save();

        return $this->showMessage('Author updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Author $author
     * @return JsonResponse
     */
    public function destroy(Author $author)
    {
        Author::destroy($author->id);

        return $this->showMessage('Author removed successfully');
    }

    /**
     * Enable the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore($id){
        Author::withTrashed()->findOrFail($id)->restore();

        return $this->showMessage('Author restored successfully');
    }
}
