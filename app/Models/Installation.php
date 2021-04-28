<?php

namespace App\Models;

use App\Models\Traits\Eventable;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Installation extends Model
{
    use GeneratesUuid;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'user_id',
    ];

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    /**
     * Get the user that owns the device.
     *
     * @return
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Set locale attribute to lower.
     *
     * @param mixed $value
     *
     * @return string
     */
    public function setLocaleAttribute($value)
    {
        $this->attributes['locale'] = strtolower($value);
    }

    /**
     * Update installation.
     *
     * @param string $uuid
     * @param array $data
     * @param \App\Models\User $user
     *
     * @return self
     */
    public static function updateFromRequest($uuid, array $data, User $user = null) : Installation
    {
        $installation = Installation::whereUuid($uuid)->firstOrFail();
        $installation->fill($data);

        DB::transaction(function () use ($installation, $user) {
            if ($user) {
                $installation->user()->associate($user);
            }

            $installation->save();
        });

        return $installation;
    }

}
