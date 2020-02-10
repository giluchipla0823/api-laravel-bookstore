<?php

namespace App\Models;

use App\Transformers\PublisherTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publisher extends Model
{
    use SoftDeletes;

    protected $table = 'publishers';

    public $transformer = PublisherTransformer::class;

    protected $fillable = array(
    	'name'
    );

    public function books(){
    	return $this->hasMany(Book::class);
    }
}
