<?php

namespace App\Transformers\V1;

use Carbon\Carbon;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;
use Illuminate\Database\Eloquent\Builder;
use App\Transformers\V1\AuthorTransformer;
use App\Transformers\V1\CategoryTransformer;
use App\Transformers\V1\AudioBookTransformer;

class BookTransformer extends TransformerAbstract
{
    /**
     * List of resources to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'categories', 'audiobook', 'ebook', 'print',
    ];



    /**
     * Turn this item object into a generic array
     *
     * @param User $user
     *
     * @return array
     */
    public function transform(Book $book)
    {
        return [
            'id' => (int) $book->id,
            'title' => $book->title,
            'release_date' => $book->release_date,
            'authors' => $book->getAuthorsString(),
            'volume' => $book->getVolumeString(),
            'description' => $book->description,
            'cover' => $book->getMedia('books/covers')->count() >= 1 ? $book->getMediasTransformation()['covers'] : null,
            'ebook_version' => $book->ebook ? true : false,
            'print_version' => $book->print ? true : false,
            'audiobook_version' => $book->audiobook ? true :false,
        ];
    }

    /**
     * Include Categories
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeCategories(Book $book)
    {
        $categories = $book->categories;

        return $this->collection($categories, new CategoryTransformer);
    }

    /**
     * Include AudioBook
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeAudiobook(Book $book)
    {
        $audiobook = $book->audiobook;

        if ($audiobook) {
            return $this->item($audiobook, new AudioBookTransformer(true));
        }
        return $this->null();
    }

    /**
     * Include Ebook
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeEbook(Book $book)
    {
        $ebook = $book->ebook;

        if ($ebook) {
            return $this->item($ebook, new EbookTransformer);
        }
        return $this->null();
    }

    /**
     * Include Print
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includePrint(Book $book)
    {
        $print = $book->print;

        if ($print) {
            return $this->item($print, new PrintTransformer);
        }
        return $this->null();
    }

    /**
     * Include suggestions
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeSuggestions(Book $book)
    {
        $transformer = book::transformer();
        $authors_ids = $book->authors()->get()->pluck('id');
        $categories_ids = $book->categories()->get()->pluck('id');
        $data = Book::where('id', '!=', $book->id)
            ->whereHas('authors', function (Builder $query) use ($authors_ids) {
                $query->whereIn('authors.id', $authors_ids);
            })
            ->orWhereHas('categories', function (Builder $query) use ($categories_ids, $book) {
                $query->whereIn('categories.id', $categories_ids)->where('audio_books.id', '!=', $book->id);
            })
            ->inRandomOrder()
            ->limit(5)
            ->get()
            ->map(function($model) use ($transformer) {
                return $transformer->homeTransform($model);
            });
        $title = "Vous pourriez aimer...";

        $section = HomeSection::make([
            'order' => 3,
        ]);


        return $this->item($section, new SuggestionTransformer($data, $title));
    }
}
