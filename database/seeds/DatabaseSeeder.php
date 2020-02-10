<?php

use App\User;
use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Genre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        
        User::truncate();
        Book::truncate();
        Author::truncate();
        Publisher::truncate();
        Genre::truncate();

        factory(User::class, 50)->create();
        factory(Author::class, 200)->create();
        factory(Publisher::class, 100)->create();
        factory(Genre::class, 50)->create();
		factory(Book::class, 400)->create();
    }
}
