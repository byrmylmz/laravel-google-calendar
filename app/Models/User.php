<?php

namespace App\Models;

use App\Http\Controllers\GoogleAccountController;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\GoogleAccount;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Google accounts
     */

     public function googleAccounts()
     {
         return $this->hasMany(GoogleAccount::class);
     }

     public function events()
     {
         // Or use: https://github.com/staudenmeir/eloquent-has-many-deep
         return Event::whereHas('calendar', function ($calendarQuery) {
             $calendarQuery->whereHas('googleAccount', function ($accountQuery) {
                 $accountQuery->whereHas('user', function ($userQuery) {
                     $userQuery->where('id', $this->id);
                 });
             });
         });
     }

}
