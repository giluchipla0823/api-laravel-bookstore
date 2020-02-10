<?php

namespace App\Models;

use App\Transformers\GenreTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genre extends Model
{
    use SoftDeletes;

    protected $table = 'genres';

    protected $fillable = [
        'name'
    ];

    public $transformer = GenreTransformer::class;

    public function books(){
    	return $this->belongsToMany(Book::class, 'books_genres');
    }
}
