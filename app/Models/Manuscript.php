<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use App\Models\Traits\ManageMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Manuscript extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, ManageMedia;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'adress', 'phone', 'have_pseudonym', 'pseudonym', 'presentation', 'contract', 'free_broadcast', 'title', 'genres', 'sign_number', 'summary', 'characters_summary', 'plot', 'additionnal_information', 'cgu_accepted',
    ];

    protected $casts = [
        'have_pseudonym' => 'boolean',
        'contract' => 'boolean',
        'free_broadcast' => 'boolean',
        'cgu_accepted' => 'boolean',
    ];

    public function registerMediaCollections() : void
    {
        $this
            ->addMediaCollection($this->table.'/file')
            ->useDisk('gcs')
            ->singleFile();
    }
}
