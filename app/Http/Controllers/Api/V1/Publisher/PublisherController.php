<?php

namespace App\Http\Controllers\Api\V1\Publisher;

use App\Http\Controllers\ApiController;
use App\Models\Publisher;
use App\Helpers\AppHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\PublisherRequest;
use Symfony\Component\HttpFoundation\Response;

class PublisherController extends ApiController
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

        $query = new Publisher;

        if($includes = AppHelper::getIncludesFromUrl()){
            $query = $query->with($includes);
        }

        if(isset($data['name'])){
            $query = $query->where('name', 'LIKE', "%" . $data['name'] . "%");
        }

        $publishers = $query->get();

        return $this->showAll($publishers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PublisherRequest $request
     * @return JsonResponse
     */
    public function store(PublisherRequest $request)
    {
        Publisher::create($request->all());

        return $this->showMessage('Publisher created successfully',Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Publisher $publisher
     * @return JsonResponse
     */
    public function show(Publisher $publisher)
    {
        return $this->showOne($publisher);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PublisherRequest $request
     * @param Publisher $publisher
     * @return JsonResponse
     */
    public function update(PublisherRequest $request, Publisher $publisher)
    {
        $publisher->fill($request->all())->save();

        return $this->showMessage('Publisher updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Publisher $publisher
     * @return JsonResponse
     */
    public function destroy(Publisher $publisher)
    {
        Publisher::destroy($publisher->id);

        return $this->showMessage('Publisher removed successfully');
    }

    /**
     * Enable the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore($id){
        Publisher::withTrashed()->findOrFail($id)->restore();

        return $this->showMessage('Publisher restored successfully');
    }
}
