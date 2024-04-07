<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = ['message', 'freelancer_id'];

    // Remove the 'name' field from the validation rules
    public static $rules = [
        'message' => 'required|string|max:1000',
        'freelancer_id' => 'required|exists:freelancers,id',
    ];
}
