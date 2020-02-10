<?php

namespace App\Transformers;

use App\Models\Author;
use App\Transformers\BookTransformer;
use League\Fractal\TransformerAbstract;

class AuthorTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['books'];

    public function __construct()
    {
        $includes = array_intersect(explode(',' , request()->get('includes')), $this->availableIncludes);

        $this->setDefaultIncludes($includes);
    }

    public function transform(Author $author)
    {
        return [
            'id' => (int) $author['id'],
            'name' => (string) $author['name'],
            'active' => (int) $author['active'],
            'createdAt' => (string) $author['created_at']
        ];
    }

    public function includeBooks(Author $author){
        return $author->books ? $this->collection($author->books, new BookTransformer) : $this->null();
    }
}
