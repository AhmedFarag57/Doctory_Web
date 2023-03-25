<?php

namespace App\Models;

use App\Notifications\MessageSent;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    // Table name
    protected $table = 'users';

    // The guarded -> Primary Key
    protected $guarded = ['id'];

    // The Token
    const USER_TOKEN = '';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'ssn',
        'isDoctor',
        'blocked',

        //'blocked',
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
     *
     * Relation with Chat
     */
    public function chats() : HasMany {
        return $this->hasMany(Chat::class, 'created_by');
    }

    /**
     *
     * Relation with Doctor
     */
    public function doctor() : HasOne {
        return $this->hasOne(Doctor::class, 'user_id');
    }

    /**
     *
     * Relation with Patient
     */
    public function patient() : HasOne {
        return $this->hasOne(Patient::class, 'user_id');
    }

    /**
     * Onesignal
     */
    public function routeNotificationForOneSignal() : array {
        return ['tags' => ['key' => 'userId', 'relation' => '=', 'value' => (string)($this->id)]];
    }
    /**
     * Onesignal
     */
    public function sendNewMessageNotification(array $data) : void {
        return;
        //$this->notify(new MessageSent($data));
    }


}
