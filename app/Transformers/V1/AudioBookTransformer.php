<?php

namespace App\Transformers\V1;

use Carbon\Carbon;
use App\Models\AudioBook;
use App\Models\HomeSection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;
use Illuminate\Database\Eloquent\Builder;
use App\Transformers\V1\AuthorTransformer;
use App\Transformers\V1\ChapterTransformer;
use App\Models\Interfaces\HomeTransformable;
use App\Transformers\V1\CategoryTransformer;
use App\Transformers\V1\AudioBookTransformer;

class AudioBookTransformer extends TransformerAbstract implements HomeTransformable
{
    protected $route;

    public function __construct($route = false)
    {
        $this->route = $route;
    }
    /**
     * List of resources to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'authors', 'chapters', 'categories', 'suggestions', 'bonuses'
    ];



    /**
     * Turn this item object into a generic array
     *
     * @param User $user
     *
     * @return array
     */
    public function transform(AudioBook $audioBook)
    {
        if ($this->route) {
            return [
                'id' => (int) $audioBook->id,
                'duration' => $audioBook->web_duration,
                'release_date' => $audioBook->release_date,
                'reference' => $audioBook->internal_code,
            ];
        }

        $user = Auth::user();
        if($audioBook->getDuration() == 0) {
            $progress = null;
        } else {
            if ($audioBook->users->contains($user)) {
                $progress = array( 
                    'label' => $audioBook->getRemainingTimeLabel($user),
                    'step' => $audioBook->getProgress($user),
                    'duration' =>  $audioBook->getDuration(),
                );
            } else {
                $progress = array(
                    'label' => $audioBook->getDurationLabel($user),
                    'step' => 0,
                    'duration' =>  $audioBook->getDuration(),
                );
            }
        }
         if($audioBook->publication_date > Carbon::now()) {
            if($audioBook->publication_date->isSameDay(Carbon::now())){
                $availability = 'Arrive bientôt !';
            }
            $availability = 'Disponible le '.$audioBook->publication_date->format('d/m');
        }
        return [
           'id' => (int) $audioBook->id,
           'title' => $audioBook->name,
           'subtitle' => $audioBook->getAuthorsString(),
           'readers' => $audioBook->getReadersString(),
           'description' => $audioBook->description,
           'progress' => $progress ?? null,
           'availability' => $availability ?? null,
           'is_masked' => $audioBook->maskedStatus($user),
           'uri' => [
                'id' => $audioBook->id,
                'type' => 'audiobook',
                'url' => route('audio_books.show', ['audio_book' => $audioBook->id]),
            ],
            'cover' => $audioBook->getMedia('audio_books/covers')->count() >= 1 ? $audioBook->getMediasTransformation()['covers'] : null,
            'view_count' => $audioBook->view_count,
            'have_extract' => $audioBook->getMedia('audio_books/extracts')->count() >= 1 ? true : false,
            'have_audiobook' => $audioBook->possessesAudioBook($user),
        ];
    }

    /**
     * Turn this item object into a generic array
     *
     * @param User $user
     *
     * @return array
     */
    public function extractTransform(AudioBook $audioBook)
    {
        return [
            'data' => [
               'id' => (int) $audioBook->id,
               'extract' => $audioBook->getMedia('audio_books/extracts')->count() >= 1 ? $audioBook->getMediasTransformation()['extracts'] : null,
            ]
        ];
    }

    /**
     * Turn this item object into a generic array
     *
     * @param Model $model
     *
     * @return array
     */
    public static function homeTransform(Model $model) : array
    {
        $user = Auth::user();
        $model->loadMissing('authors');
        $authors = $model->authors->makeHidden('pivot');
        if($model->publication_date > Carbon::now()) {
            if($model->publication_date->isSameDay(Carbon::now())){
                $availability = 'Arrive bientôt !';
            }
            $availability = 'Disponible le '.$model->publication_date->format('d/m');
        }
        if($model->users->contains($user)){
            if($model->getDuration() == 0) {
                $progress = null;
            } else {
                $progress = array( 
                    'label' => $model->getRemainingTimeLabel($user),
                    'step' => $model->getProgress($user),
                    'duration' =>  $model->getDuration(),
                );
            }
        }
        return [
            'id' => (int) $model->id,
            'title' => $model->name,
            'subtitle' => $model->getAuthorsString(),
            'publication_date' => $model->publication_date ? $model->publication_date->toIso8601String() : null,
            'description' => $model->description,
            'availability' => $availability ?? null,
            'progress' => $progress ?? null,
            'is_locked' => false,
            'cover' => $model->getMedia('audio_books/covers')->count() >= 1 ? $model->getMediasTransformation()['covers'] : null,
            'uri' => [
                'id' => $model->id,
                'type' => 'audiobook',
                'url' => route('audio_books.show', ['audio_book' => $model->id]),
            ],
            'view_count' => $model->view_count,
            'have_extract' => $model->getMedia('audio_books/extracts')->count() >= 1 ? true : false,
            'have_audiobook' => $model->possessesAudioBook($user),
        ];
    }

    /**
     * Include Authors
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeAuthors(AudioBook $audioBook)
    {
        $authors = $audioBook->authors;

        return $this->collection($authors, new AuthorTransformer(true));
    }

    /**
     * Include Categories
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeCategories(AudioBook $audioBook)
    {
        $categories = $audioBook->categories;

        return $this->collection($categories, new CategoryTransformer);
    }

    /**
     * Include chapters
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeChapters(AudioBook $audioBook)
    {
        $chapters = $audioBook->chapters;

        return $this->collection($chapters, new ChapterTransformer);
    }

    /**
     * Include suggestions
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeSuggestions(AudioBook $audioBook)
    {
        $transformer = AudioBook::transformer();
        $authors_ids = $audioBook->authors()->get()->pluck('id');
        $categories_ids = $audioBook->categories()->get()->pluck('id');
        $data = AudioBook::where('id', '!=', $audioBook->id)
            ->whereHas('authors', function (Builder $query) use ($authors_ids) {
                $query->whereIn('authors.id', $authors_ids);
            })
            ->orWhereHas('categories', function (Builder $query) use ($categories_ids, $audioBook) {
                $query->whereIn('categories.id', $categories_ids)->where('audio_books.id', '!=', $audioBook->id);
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

    /**
     * Include Bonuses
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeBonuses(AudioBook $audioBook)
    {
        $bonuses = $audioBook->bonuses;

        return $this->collection($bonuses, new BonusTransformer);
    }
}
