<?php

namespace App\Models;


use App\Models\Calendar;
use App\Concerns\Synchronizable;
use App\Jobs\SynchronizeGoogleCalendars;
use App\Jobs\WatchGoogleCalendars;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class GoogleAccount extends Model
{
    use Synchronizable;

    protected $fillable = [
        'google_id', 'name', 'token',
    ];

    protected $casts = [
        'token' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function calendars()
    {
        return $this->hasMany(Calendar::class);
    }

    public function synchronize()
    {
        SynchronizeGoogleCalendars::dispatch($this);
    }

    public function watch()
    {
        WatchGoogleCalendars::dispatch($this);
    }
}