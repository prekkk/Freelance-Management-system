<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\CategoryJobType;
use App\Models\Job;

class AccountController extends Controller
{
    //This method shows user registration page
    public function registration(){
        return view('front.account.registration');

    }

    //This method shows user login page
    public function processRegistration(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|same:confirm_password',
            'confirm_password' => 'required',
        ]);
        if ($validator->passes()){

            $user = new User();
            $user -> name = $request->name;
            $user -> email = $request->email;
            $user -> password = Hash::make($request->password);
            $user -> confirm_password = $request->name;
            $user -> save();

            session()->flash('success','You have registered successfully.');
            return response()->json([
                'status' => true,
                'errors' => []
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        
    }
    //This method shows user login page
    public function login(){
        return view('front.account.login');
        
    }
    public function authenticate(request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if($validator -> passes()){
            if(Auth::attempt(['email'=> $request->email, 'password'=> $request->password])){
                return redirect()->route('account.profile');

            }
            else{
                return redirect()-> route('account.login')->with('error','Either Email/Password is incorrect');
            }

        }else {
            return redirect()->route('account.login')
            ->withErrors($validator)
            ->withInput($request->only('email'));

        }

    }
    public function profile(){

        
        $id = Auth::user()->id;
        $user = User::where('id',$id)->first();
        return view('front.account.profile',[
            'user' => $user
        ]);
    }

    public function updateProfile(Request $request){

        $id = Auth::user()->id;

        $validator = Validator::make($request->all(),[
            'name' => 'required|min:5|max:20',
            'email' => 'required|email|unique:table:users,email,'.$id.',id'
        ]);
        if ($validator->passes()){

            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->designation = $request->designation;
            $user->save();

            session()->flash('success','Profile updated successfully.');

            return response()->json([
                'status' => true,
                'errors' => []

            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()

            ]);
        }

    }



    public function logout(){
        Auth::logout();
        return redirect()->route('account.login');
    }

    
    public function updateProfilePic(Request $request){

        $id = Auth::user()->id;

        $validator = Validator::make($request->all(),[
            'image' => 'required|image'
        ]);
        if ($validator->passes()){
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = $id.'-'.time().'.'.$ext;
            $image->move(public_path('/profilePic/'),$imageName);

            User::where('id',$id)->update(['image' => $imageName]);

Session()->flash('success','Profile picture updated successfully.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function createJob(){

    $categories = Category::orderBy('name','ASC')->where('status',1)->get();
    $jobTypes = CategoryJobType::orderBy('name','ASC')->where('status',1)->get();

        return view('front.account.job.create',[
        'categories' => $categories,
        'jobTypes' => $jobTypes,
        ]);

    }
    public function saveJob(Request $request){
        $rules = [
            'title' => 'required|min:5|max:100',
            'category' => 'required',
            'jobType' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',

        ];
        $validator = Validator::make($request->all(),$rules);

        if ($validator->passes()){

            $job = new Job();
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->jobType;
            $job->user_id = Auth::user()->id;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsibilities = $request->responsibilities;
            $job->qualifications = $request->qualifications;
            $job->keywords = $request->keywords;
            $job->save();

            session()->flash('success','Job posted successfully.');

            return response()->json([
                'status' => true,
                'errors' => []

            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->erros()

            ]);
        }

    }

    public function myJobs(){

    $jobs = Job::where('user_id', Auth::user()->id)->with('CategoryJobType')->orderBy('created_at','DESC')->paginate(10);
        return view('front.account.job.my-jobs',[
            'jobs' => $jobs
        ]);
    }

    public function editJob(Request $request, $id){

    $categories = Category::orderBy('name','ASC')->where('status',1)->get();
    $jobTypes = CategoryJobType::orderBy('name','ASC')->where('status',1)->get();

    $user = Auth::user();

    $job = Job::where('user_id', $user->id)->where('id', $id)->first();

    if($job == null){
        abort(404);
    }
        return view('front.account.job.edit',[
        'categories' => $categories,
        'jobTypes' => $jobTypes,
        'job' => $job,
        ]);

    }

    public function updateJob(Request $request, $id){
        $rules = [
            'title' => 'required|min:5|max:100',
            'category' => 'required',
            'jobType' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',

        ];
        $validator = Validator::make($request->all(),$rules);

        if ($validator->passes()){

            $job = Job::find($id);
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->jobType;
            $job->user_id = Auth::user()->id;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsibilities = $request->responsibilities;
            $job->qualifications = $request->qualifications;
            $job->keywords = $request->keywords;
            $job->save();

            session()->flash('success','Job updated successfully.');

            return response()->json([
                'status' => true,
                'errors' => []

            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->erros()

            ]);
        }

    }
    public function deleteJob(Request $request){
        $job = Job::where([
            'user_id' => Auth::user()->id,
            'id' => $request->jobId
        ])->first();

        if($job == null){
            session()->flash('error','Either job deleted or not found.');
            return response()->json([
                'status' => true
            ]);
        }
        Job::where('id',$request->jobId)->delete();
        session()->flash('success','Job deleted succesfully.');
            return response()->json([
                'status' => true
            ]);
    }
}
 