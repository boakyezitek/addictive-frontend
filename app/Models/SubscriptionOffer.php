<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;
use App\Transformers\V1\SubscriptionOfferTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionOffer extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'advantages'];

    protected $casts = [
        'advantages' => 'collection',
    ];

    public static function transformer() : TransformerAbstract
    {
        return new SubscriptionOfferTransformer();
    }

    public function formatAdvantages()
    {
        $sections = [];
        foreach(json_decode($this->advantages) as $key => $section){
            $item = ['position' => $key, 'content' => $section];
            array_push($sections, $item);
        }
        return $sections;
    }

    public function formatTitle()
    {
    	preg_match('/<div>(.*?)<strong>(.*?)<\/strong>(.*?)<\/div>/', $this->title, $result);
        if(count($result) == 4){
            unset($result[0]);
            $text = implode('', $result);
            $start = strlen($result[1]);
            $end = $start + strlen($result[2]);
        } else {
            $start = 0;
            $end = 0;
            preg_match('/<div>(.*?)<\/div>/', $this->title, $result);
            $text = count($result) == 2 ? $result[1] : $this->title;
        }

        return ['text' => $text, 'start' => $start, 'end' => $end];
    }
}
