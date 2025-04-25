<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Filament\Models\Contracts\HasName;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use App\Jobs\SendUserDeletedNotification;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements HasName, HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, InteractsWithMedia, SoftDeletes;

    public const PROTECTED_ROLES =  ['driver', 'manager' ,'admin'];
    public static bool $bypassRoleAssignment = false;
    
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
            $user->password = $user->password ??= Hash::make('password');
        });

        static::retrieved(function ($user) {
            $user->firstname = Str::ucfirst($user->firstname);
            $user->lastname = Str::ucfirst($user->lastname);
        });

        static::created(function ($user) {

            if (self::$bypassRoleAssignment) {
                return;
            }

            if ($user->roles()->count() === 0) {
                $driverRole = Role::where('name', 'driver')->first();
                if ($driverRole) {
                    $user->assignRole($driverRole);
                }
            }
        });

        static::deleting(function ($user) {
            $user->bus()->update(['user_id' => null]);
            info('Deleted user: ' . $user->firstname . ' ' . $user->lastname);
            SendUserDeletedNotification::dispatch($user)->delay(now()->addDay());
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

    public function bus(): HasOne
    {
        return $this->hasOne(Bus::class);
    }
}
