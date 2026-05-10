<?php

namespace App\Http\Controllers;

use App\Http\Requests\Feedback\StoreFeedbackRequest;
use App\Services\Feedback\FeedbackSubmissionService;

class FeedbackController extends Controller
{
    public function store(StoreFeedbackRequest $request, FeedbackSubmissionService $feedbackSubmission)
    {
        $feedbackSubmission->store($request);

        return back()->with('success', 'Terima kasih atas feedback Anda!');
    }
}
