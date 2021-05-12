<?php

namespace App\Http\Controllers\Api\V1;

use RevenueCat;
use Carbon\Carbon;
use App\Models\Book;
use App\Models\User;
use App\Models\Ebook;
use App\Models\Author;
use App\Models\Category;
use App\Models\AudioBook;
use App\Models\PrintBook;
use App\Models\StoreLink;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EdisourceController extends Controller
{

    /**
     * Webhook API
     *
     * @return Illuminate\Http\Response
     */
    public function webhook(Request $request)
    {
        $authors = $request->authors;

        if ($authors) {
            foreach ($authors as $author) {
                if (! $exist = Author::where('edisource_id', $author['id'])->first()) {
                    $name_array = explode(" ", $author['name']);
                    
                    $created_author = new Author([
                        'first_name' => $name_array[0],
                        'last_name' => $name_array[1],
                        'description' => strip_tags($author['biography']),
                        'edisource_id' => $author['id'],
                        'is_web' => 1,
                    ]);

                    $created_author->save();
                }
            }
        }

        $books = $request->books;

        if ($books) {
            foreach ($books as $book) {
                if (! $exist = Book::where('edisource_id', $book['id'])->first()) {
                    $created_book = new Book([
                        'edisource_id' => $book['id'],
                        'title' => $book['title'] . ' ' . $book['subTitle'],
                        'description' => strip_tags($book['pitch']),
                    ]);

                    $created_book->save();

                    if (isset($book['authors'])) {
                        foreach ($book['authors'] as $author) {
                            if ($exist = Author::where('edisource_id', $author)->first()) {
                                $created_book->authors()->attach($exist);
                            }
                        }
                    }

                    if (isset($book['coverPicture'])) {
                        $created_book->addMediaFromBase64($book['coverPicture'])->usingFileName(Carbon::now()."-".$book['coverPictureTitle'].'.jpg')->toMediaCollection('books/covers');
                    }

                    if (isset($book['previous'])) {
                        if ($exist = Book::where('edisource_id', $book['previous'])->first()) {
                            $created_book->parent()->associate($exist);
                        }
                    }

                    if (isset($book['keywords'])) {
                        foreach ($book['keywords'] as $keyword) {
                            if (! $exist = Category::where('name', $keyword)->first()) {
                                $exist = new Category([
                                    'name' => $keyword,
                                ]);
                                $exist->save();
                            }
                            $created_book->categories()->attach($exist);
                        }
                    }

                    if (isset($book['ebook'])) {
                        $created_ebook = new Ebook([
                            'release_date' => $book['ebook']['releaseDate'],
                        ]);
                        $created_book->release_date = $created_ebook->release_date;
                        $created_ebook->save();

                        if (isset($book['ebook']['storeLinks'])) {
                            foreach ($book['ebook']['storeLinks'] as $link) {
                                $created_store_link = new StoreLink([
                                    'store' => $link['store'],
                                    'url' => $link['url'],
                                ]);
                                $created_store_link->save();

                                $created_ebook->links()->save($created_store_link);
                            }
                        }
                        $created_book->ebook()->associate($created_ebook);
                    }

                    if (isset($book['print'])) {
                        $created_print_book = new PrintBook([
                            'isbn' => $book['print']['isbn'],
                            'release_date' => $book['print']['releaseDate'],
                            'page_count' => $book['print']['pageCount'],
                        ]);
                        if ($created_book->release_date > $created_print_book->release_date) {
                            $created_book->release_date = $created_print_book->release_date;
                        }

                        $created_print_book->save();

                        foreach ($book['print']['storeLinks'] as $link) {
                            $created_store_link = new StoreLink([
                                'store' => $link['store'],
                                'url' => $link['url'],
                            ]);

                            $created_store_link->save();
                            $created_print_book->links()->save($created_store_link);
                        }
                        $created_book->print()->associate($created_print_book);
                    }

                    if (isset($book['audio'])) {
                        if ($exist = AudioBook::where('internal_code', $book['audio']['appReference'])->first()) {
                            $exist->web_duration = $book['audio']['duration'];
                            $exist->release_date = $book['audio']['releaseDate'];
                            $exist->save();
                            $created_book->audiobook()->associate($exist);
                            if ($created_book->release_date > $exist->release_date) {
                                $created_book->release_date = $exist->release_date;
                            }
                        }
                    }
                    $created_book->save();
                }
            }
            return response()->json(['message' => 'success'], 200);
        }
    }
}
