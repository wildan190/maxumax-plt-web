<?php

namespace App\Services\Feedback;

use App\Http\Requests\Feedback\StoreFeedbackRequest;
use App\Models\Feedback;

class FeedbackSubmissionService
{
    public function store(StoreFeedbackRequest $request): void
    {
        $data = $request->validated();

        $imagePaths = [];
        if ($request->hasFile('images')) {
            $images = array_slice($request->file('images'), 0, 2);
            foreach ($images as $image) {
                $imagePaths[] = $image->store('feedback', 'public');
            }
        }

        if ($imagePaths !== []) {
            $data['images'] = $imagePaths;
        }

        Feedback::create($data);
    }
}
