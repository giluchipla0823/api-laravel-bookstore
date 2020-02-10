<?php

namespace App\Transformers;

use App\Models\Publisher;
use League\Fractal\TransformerAbstract;

class PublisherTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['books'];

    public function __construct()
    {
        $includes = array_intersect(explode(',' , request()->get('includes')), $this->availableIncludes);

        $this->setDefaultIncludes($includes);
    }

    /**
     * A Fractal transformer.
     *
     * @param Publisher $publisher
     * @return array
     */
    public function transform(Publisher $publisher)
    {
        return [
            'id' => (int) $publisher['id'],
            'name' => (string) $publisher['name'],
            'active' => (int) $publisher['active'],
            'createdAt' => (string) $publisher['created_at']
        ];
    }

    public function includeBooks(Author $author){
        return $author->books ? $this->collection($author->books, new BookTransformer) : $this->null();
    }
}
