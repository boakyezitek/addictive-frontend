<?php

namespace App\Nova;

use Spatie\TagsField\Tags;
use Laravel\Nova\Fields\ID;
use AudioBook\Status\Status;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\AudiobookTags;
use App\Nova\Filters\AudiobookPrice;
use App\Nova\Filters\AudiobookAuthor;
use App\Nova\Filters\RecordingStudio;
use Laravel\Nova\Fields\BelongsToMany;
use App\Nova\Filters\AudiobookPublication;
use Laravel\Nova\Http\Requests\NovaRequest;
use Audiobooks\CreateChapters\CreateChapters;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Eminiarts\NovaPermissions\Nova\ResourceForUser;
use App\Nova\Actions\CreateMultipleChaptersRedirection;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class AudioBook extends ResourceForUser
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\AudioBook::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    // public static $title = $this->internal_code+'name';
    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        if($this->internal_code) {
            return $this->internal_code.'-'.$this->name;
        } else {
            return $this->name;
        }
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'e_number', 'recording_studio', 'isbn', 'internal_code', 'internal_code_ebook', 'tags.name', 'tags.slug'
    ];

    public static function label()
    {
        return __('AudioBook');
    }

    public static function singularLabel()
    {
        return __('AudioBook');
    }

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->select('audio_books.*')
                    ->leftjoin('taggables', 'audio_books.id', '=', 'taggable_id' )
                    ->leftjoin('tags','taggables.tag_id','=', 'tags.id')
                    ->distinct();
    }


    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            DateTime::make(__('Publication date'), 'publication_date')
                ->sortable()->help("<div class='flex flex-col'><div class='ml-auto text-sm font-bold text-primary cursor-pointer dim' style='font-style: normal;' onclick = \"this.parentElement.parentElement.parentElement.querySelector('input')._flatpickr.clear()\" >Draft Mode</div></div>")
                ->format('D/MM/Y'),
            Images::make(__('Cover'), 'audio_books/covers')
                ->setFileName(function($originalFilename, $extension, $model){
                    return md5($originalFilename) . '.' . $extension;
                }
            )->rules('required'),
            Text::make(__('E-number'), 'e_number')
                ->sortable()
                ->rules('max:255'),
            Text::make(__('Internal code AudioBook'), 'internal_code')
                ->sortable()
                ->rules('max:8', 'min:7', 'nullable'),
            Text::make(__('Internal code eBook'), 'internal_code_ebook')
                ->sortable()
                ->rules('max:8', 'min:7', 'nullable'),
            Text::make(__('ISBN'), 'isbn')
                ->sortable()
                ->rules('max:13', 'min:13', 'nullable'),
            Text::make(__('Title'), 'name')
                ->sortable()
                ->rules('required', 'max:255'),
            BelongsToMany::make(__('Authors'), 'authors', Author::class),
            Text::make(__('Authors'), function () {
                return $this->getAuthorsString();
            })->onlyOnIndex(),
            Trix::make(__('Resume'), 'description')
                ->rules('required')->alwaysShow(),
            Tags::make(__('Keywords')),
            BelongsToMany::make(__('Categories'), 'categories', Category::class),
            BelongsToMany::make(__('Reader'), 'readers', Reader::class),
            BelongsTo::make(__('Language'), 'language', Language::class)
                ->hideFromIndex(),
            Text::make(__('Recording Studio'), 'recording_studio')
                ->sortable()
                ->rules('required', 'max:255'),
            Currency::make(__('Price'), 'price')
                ->sortable()
                ->rules('required', 'max:255'),
            Files::make(__('Extract'), 'audio_books/extracts')
                ->setFileName(function($originalFilename, $extension, $model){
                    return md5($originalFilename) . '.' . $extension;
                }
            ),
            HasMany::make(__('Chapters'), 'chapters', Chapter::class),
            HasMany::make(__('Bonuses'), 'bonuses', Bonus::class),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [
            (new Status)->onlyOnDetail()
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new AudiobookAuthor,
            new RecordingStudio,
            new AudiobookTags,
            new AudiobookPublication,
            new AudiobookPrice
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            new DownloadExcel(),
            (new CreateMultipleChaptersRedirection())->showOnTableRow(),
        ];
    }
}
