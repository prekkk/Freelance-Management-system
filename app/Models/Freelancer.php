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
        '_token', // Add _token to the fillable array
    ];

    /**
     * Get the feedbacks associated with the freelancer.
     */
    public function feedback()
    {
        return $this->hasMany(Feedback::class);
    }
}
