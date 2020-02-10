<?php

namespace App\Traits;

use App\Helpers\DatatablesHelper;
use App\Libraries\ArraySerializer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Yajra\Datatables\Datatables;

Trait ResponseTransformer{

    protected function transformData($data, $transformer)
    {
        $transformation = fractal($data, new $transformer, new ArraySerializer());
        return $transformation->toArray()['data'];
    }

    protected function transformInstance(Model $instance){
        $transformer = $instance->transformer;

        if(!$transformer){
            return $instance;
        }

        $instance = $this->transformData($instance, $transformer);

        return $instance;
    }

    protected function transformCollection(Collection $collection){
        if ($collection->isEmpty()) {
            return $collection;
        }

        $transformer = $collection->first()->transformer;

        if(!$transformer){
            return $collection;
        }

        $collection = $this->transformData($collection, $transformer);

        return $collection;
    }


    protected function transformDatatables(Collection $collection){
        $transformer = NULL;

        if (!$collection->isEmpty()) {
            $transformer = $collection->first()->transformerDatatable;
        }

        $collection = Datatables::of($collection);

        if(!$transformer){
            $collection->setTransformer(new $transformer());
        }

        $collection = $collection->make(true);

        return DatatablesHelper::makeResponse($collection);
    }

}