<?php

namespace App\Transformers;

use App\Models\Genre;
use League\Fractal\TransformerAbstract;

class GenreTransformer extends TransformerAbstract
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
     * @param Genre $genre
     * @return array
     */
    public function transform(Genre $genre)
    {
        return [
            'id' => (int) $genre['id'],
            'name' => (string) $genre['name'],
            'active' => (int) $genre['active'],
            'createdAt' => (string) $genre['created_at']
        ];
    }

    public function includeBooks(Genre $genre){
        return $genre->books ? $this->collection($genre->books, new BookTransformer) : $this->null();
    }
}
