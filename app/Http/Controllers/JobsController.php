<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobType;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    //this method will show jobs page
    public function index(Request $request){

       $categories = Category::where('status',1)->get();
       $JobTypes = JobType::where('status',1)->get();

       $jobs = Job::where('status',1);

       //Search using keyword
       if(!empty($request->keyword)){
        $jobs = $jobs->where(function($query) use ($request){
            $query->orWhere('title','like','%'.$request->keyword.'%');
            $query->orWhere('keywords','like','%'.$request->keyword.'%');

        });
       }

       // Search using location
       if(!empty($request->location)){
        $jobs = $jobs->where('location', $request->location);

       }

       // Search using category
       if(!empty($request->category)){
        $jobs = $jobs->where('category_id', $request->category);

       }
       $jobTypeArray = [];
       // Search using Job Type
       if(!empty($request->jobType)){

        //1,2,3
        $jobTypeArray  = explode(',',$request->jobType);
        $jobs = $jobs->whereIn('job_type_id', $jobTypeArray);

       }

       $jobs = $jobs->with(['jobType','category']);
       
       if($request->sort == '0'){
        $jobs = $jobs->orderBy('created_at','ASC');
       }
       else{
        $jobs = $jobs->orderBy('created_at','DESC');
       }
       

       $jobs = $jobs->paginate(9);

        return view('front.jobs',[
        'categories' => $categories,
        'JobTypes' => $JobTypes,
        'jobs' => $jobs,
        'jobTypeArray' => $jobTypeArray ?? []
        ]);
    }
}
