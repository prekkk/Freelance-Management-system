<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\CategoryJobType;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    //this method will show jobs page
    public function index(){

       $categories = Category::where('status',1)->get();
       $JobTypes = CategoryJobType::where('status',1)->get();

       $jobs = Job::where('status',1)->with('jobType')->orderBy('created_at','DESC')->paginate(9);

        return view('front.jobs',[
        'categories' => $categories,
        'JobTypes' => $JobTypes,
        'jobs' => $jobs,
        ]);
    }
}
