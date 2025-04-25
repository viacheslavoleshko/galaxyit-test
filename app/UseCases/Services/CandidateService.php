<?php

namespace App\UseCases\Services;

use App\Models\Candidate;


class CandidateService
{
    public function create(array $validatedData): Candidate
    {
        $candidate = Candidate::create([
            'firstname' => $validatedData['firstname'],
            'lastname' => $validatedData['lastname'],
            'birth_date' => $validatedData['birth_date'],
            'email' => $validatedData['email'],
        ]);

        return $candidate;
    }
}