<?php

namespace App\Models;

use App\Models\Traits\Eventable;
use Spatie\EloquentSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;
use App\Models\Interfaces\Transformable;
use Spatie\EloquentSortable\SortableTrait;
use App\Models\HomeTemplates\BonusTemplate;
use App\Models\HomeTemplates\DialogTemplate;
use App\Models\HomeTemplates\FeatureTemplate;
use App\Transformers\V1\HomeSectionTransformer;
use App\Models\HomeTemplates\CollectionTemplate;
use App\Models\HomeTemplates\CurrentlyPlayedTemplate;

class HomeSection extends Model implements Sortable, Transformable
{

    use SortableTrait, Eventable;

    protected $fillable = ['template', 'order', 'additional_information', 'home_sectionable_type', 'home_sectionable_type', 'is_rating'];

    public const TEMPLATES = [
        CollectionTemplate::class,
        BonusTemplate::class,
        DialogTemplate::class,
        FeatureTemplate::class,
        CurrentlyPlayedTemplate::class
    ];

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];

    public function homeSectionable()
    {
        return $this->morphTo();
    }

    public function getTemplateClass() : string
    {
        $templates = collect(self::TEMPLATES);
        $template = $templates->filter(function($templateClass) {
            return $this->template === $templateClass::$key;
        })->first();

        return $template;
    }

    public static function transformer(): TransformerAbstract
    {
        return new HomeSectionTransformer();
    }
}
