<?php
namespace App\Services;

use App\Models\Freelancer;
use App\Models\Feedback;

class FreelancerService
{
    public static function calculateRewardPoints(Freelancer $freelancer)
    {
        // Count the number of positive feedbacks for the freelancer
        $positiveFeedbacksCount = $freelancer->feedback()->where('feedback_type', 'Positive')->count();

        // Calculate reward points based on the number of positive feedbacks
        $rewardPoints = floor($positiveFeedbacksCount / 3);

        // Update the freelancer's reward points
        $freelancer->update(['rewards' => $rewardPoints]);
    }
}
