<?php

namespace App\Listeners;

use App\Events\FeedbackCreated;
use App\Models\Freelancer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;


class FeedbackCreatedListener
{
    /**
     * Handle the event.
     */
    public function handle(FeedbackCreated $event): void
    {
        // Retrieve the feedback object from the event
        $feedback = $event->feedback;

        // Retrieve the freelancer associated with the feedback
        $freelancer = $feedback->freelancer;

        // Count the number of positive feedbacks for the freelancer
        $positiveFeedbackCount = $freelancer->feedbacks()->where('feedback_type', 'Positive')->count();

        // Check if the freelancer has received three positive feedbacks
        if ($positiveFeedbackCount === 3) {
            // Increment the reward points of the freelancer by one
            $freelancer->reward_points += 1;
            $freelancer->save();
        }
    }
}
