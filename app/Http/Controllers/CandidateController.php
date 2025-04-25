<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Requests\ApplyRequest;
use App\UseCases\Services\CandidateService;

class CandidateController extends Controller
{

    public $candidateService;

    public function __construct(CandidateService $candidateService)
    {
        $this->candidateService = $candidateService;
    }

    public function create(): View
    {
        return view('apply.create');
    }

    public function store(ApplyRequest $request)
    {
        $validatedData = $request->validated();
        $createdCandidate = $this->candidateService->create($validatedData);

        return redirect()->route('apply.create')->with('success', 'Заявка відправлена!');
    }
}
