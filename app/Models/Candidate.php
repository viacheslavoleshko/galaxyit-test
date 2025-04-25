<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'firstname',
        'lastname',
        'birth_date',
        'email',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function ($candidate) {
            $candidate->firstname = Str::lower($candidate->firstname);
            $candidate->lastname = Str::lower($candidate->lastname);
        });

        static::retrieved(function ($user) {
            $user->firstname = Str::ucfirst($user->firstname);
            $user->lastname = Str::ucfirst($user->lastname);
        });
    }
}
