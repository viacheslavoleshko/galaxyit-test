<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Filament\Models\Contracts\HasName;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements HasName, HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, InteractsWithMedia;

    public const PROTECTED_ROLES =  ['driver', 'manager' ,'admin'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'birth_date',
        'email',
        'salary',
        'avatar',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'datetime',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function ($user) {
            $user->firstname = Str::lower($user->firstname);
            $user->lastname = Str::lower($user->lastname);
        });

        static::retrieved(function ($user) {
            $user->firstname = Str::ucfirst($user->firstname);
            $user->lastname = Str::ucfirst($user->lastname);
        });
    }

    public function getFilamentName(): string
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatars')->singleFile();
    }
}
