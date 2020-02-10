<?php

namespace App\Transformers;

use App\Models\Book;
use League\Fractal\TransformerAbstract;

class BookTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['author', 'publisher', 'genres'];

    public function __construct()
    {
        $includes = array_intersect(explode(',' , request()->get('includes')), $this->availableIncludes);

        $this->setDefaultIncludes($includes);
    }

    /**
     * A Fractal transformer.
     *
     * @param Book $book
     * @return array
     */
    public function transform(Book $book)
    {
        return [
            'id' => (int) $book['id'],
            'authorId' => !is_null($book['author_id']) ? (int) $book['author_id'] : NULL,
            'publisherId' => !is_null($book['publisher_id']) ? (int) $book['publisher_id'] : NULL,
            'title' => (string) $book['title'],
            'summary' => (string) $book['summary'],
            'description' => (string) $book['description'],
            'quantity' => (int) $book['quantity'],
            'price' => (string) $book['price'],
            'image' => (string) $book['image'],
            'createdAt' => (string) $book['created_at']
        ];
    }

    public function includeAuthor(Book $book){
        return $book->author ? $this->item($book->author, new AuthorTransformer) : $this->null();
    }

    public function includePublisher(Book $book){
        return $book->publisher ? $this->item($book->publisher, new PublisherTransformer) : $this->null();
    }

    public function includeGenres(Book $book){
        return $book->genres ? $this->collection($book->genres, new GenreTransformer) : $this->null();
    }
}
