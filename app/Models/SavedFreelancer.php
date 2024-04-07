<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedFreelancer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // Assuming you have a user_id column for identifying the user who saved the freelancer
        'freelancer_id', // Assuming you have a freelancer_id column for identifying the saved freelancer
    ];

    // Define relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define relationship with the Freelancer model
    public function freelancer()
    {
        return $this->belongsTo(Freelancer::class);
    }
}
