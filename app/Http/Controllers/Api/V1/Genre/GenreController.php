<?php

namespace App\Http\Controllers\Api\V1\Genre;

use App\Http\Controllers\ApiController;
use App\Models\Genre;
use App\Helpers\AppHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\GenreRequest;
use Symfony\Component\HttpFoundation\Response;

class GenreController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $data = $request->all();

        $query = new Genre;

        if($includes = AppHelper::getIncludesFromUrl()){
            $query = $query->with($includes);
        }
        
        if(isset($data['name'])){
            $query = $query->where('name', 'LIKE', "%" . $data['name'] . "%");
        }

        $genres = $query->get();

        return $this->showAll($genres);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GenreRequest $request
     * @return JsonResponse
     */
    public function store(GenreRequest $request)
    {
        Genre::create($request->all());

        return $this->showMessage('Genre created successfully',Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Genre $genre
     * @return JsonResponse
     */
    public function show(Genre $genre)
    {
        return $this->showOne($genre);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param GenreRequest $request
     * @param Genre $genre
     * @return JsonResponse
     */
    public function update(GenreRequest $request, Genre $genre)
    {
        $genre->fill($request->all())->save();

        return $this->showMessage('Genre updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Genre $genre
     * @return JsonResponse
     */
    public function destroy(Genre $genre)
    {
        Genre::destroy($genre->id);

        return $this->showMessage('Genre removed successfully');
    }

    /**
     * Enable the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore($id){
        Genre::withTrashed()->findOrFail($id)->restore();

        return $this->showMessage('Genre restored successfully');
    }
}