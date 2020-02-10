<?php

namespace App\Transformers;

use App\Models\Book;
use App\Traits\ApiResponser;
use League\Fractal\TransformerAbstract;

class BookDatatablesTransformer extends TransformerAbstract
{
    use ApiResponser;

    /**
     * A Fractal transformer.
     *
     * @param Book $book
     * @return array
     */
    public function transform(Book $book)
    {
        $relations = array_keys($book->getRelations());

        $data = [
            'id' => (int) $book['id'],
            'authorId' => !is_null($book['author_id']) ? (int) $book['author_id'] : NULL,
            'publisherId' => !is_null($book['publisher_id']) ? (int) $book['publisher_id'] : NULL,
            'title' => (string) $book['title'],
            'summary' => (string) $book['summary'],
            'description' => (string) $book['description'],
            'quantity' => (int) $book['quantity'],
            'price' => (string) $book['price'],
            'image' => (string) $book['image'],
            'createdAt' => (string) $book['created_at'],
            'updatedAt' => !is_null($book['updated_at']) ? (string) $book['updated_at'] : NULL,
            'deletedAt' => !is_null($book['deleted_at']) ? (string) $book['deleted_at'] : NULL,
        ];

        if(in_array('author', $relations)){
            $data['author'] = !isset($book['author']) ? ['name' => ''] : $this->transformInstance($book['author']);
        }

        if(in_array('genres', $relations)){
            $data['genres'] = $this->transformCollection($book['genres']);
        }

        return $data;
    }
}
