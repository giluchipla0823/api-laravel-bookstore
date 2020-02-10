<?php

namespace App\Models;

use App\Models\Genre;
use App\Models\Author;
use App\Models\Publisher;
use App\Transformers\BookDatatablesTransformer;
use App\Transformers\BookTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    protected $table = 'books';

    public $transformer = BookTransformer::class;
    public $transformerDatatable = BookDatatablesTransformer::class;

    protected $fillable = array(
        'author_id',
        'publisher_id',
        'title',
        'summary',
        'description',
        'quantity',
        'price'
    );

    public function author(){
    	return $this->belongsTo(Author::class);
    }

    public function publisher(){
    	return $this->belongsTo(Publisher::class);
    }

    public function genres(){
    	return $this->belongsToMany(Genre::class, 'books_genres');
    }

}
