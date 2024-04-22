<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Freelancer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'designation',
        'location',
        'rewards',
        '_token', // Add _token to the fillable array
    ];

    /**
     * Get the feedbacks associated with the freelancer.
     */
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    /**
     * Calculate the total number of positive feedbacks for the freelancer.
     */
    public function positiveFeedbacksCount()
    {
        return $this->feedbacks()->where('feedback_type', 'Positive')->count();
    }

    /**
     * Calculate the reward points based on the number of positive feedbacks.
     */
    public function calculateRewardPoints()
    {
        return floor($this->positiveFeedbacksCount() / 3);
    }

    /**
     * Get the total reward points for the freelancer.
     */
    public function getTotalRewardPointsAttribute()
    {
        return $this->calculateRewardPoints();
    }
}