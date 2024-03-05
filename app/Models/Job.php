<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Job extends Model
{
    use HasFactory;

    public function jobType(){
        return $this->belongTo(CategoryJobType::class);
    }

    public function category(){
        return $this->belongTo(Category::class);
    }
}
