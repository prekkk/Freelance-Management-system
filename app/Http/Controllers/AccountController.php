<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\JobType;
use App\Models\Job;
use App\Models\SavedJob;
use App\Models\JobApplication;

class AccountController extends Controller
{
    //This method shows user registration page
    public function registration()
    {
        return view('front.account.registration');
    }

    //This method shows user login page
    public function processRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|same:confirm_password',
            'confirm_password' => 'required',
        ]);
        if ($validator->passes()) {
            $hashedPassword = Hash::make($request->password);
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->confirm_password = $request->name;
            $user->save();

            session()->flash('success', 'You have registered successfully.');
            return response()->json([
                'status' => true,
                'errors' => []
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    //This method shows user login page
    public function login()
    {
        return view('front.account.login');
    }
    public function authenticate(request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->passes()) {
            $hashedPassword = Hash::make($request->password);
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect()->route('account.profile');
            } else {
                return redirect()->route('account.login')->with('error', 'Either Email/Password is incorrect');
            }
        } else {
            return redirect()->route('account.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }
    public function profile()
    {


        $id = Auth::user()->id;
        $user = User::where('id', $id)->first();
        return view('front.account.profile', [
            'user' => $user
        ]);
    }

    public function updateProfile(Request $request)
    {

        $id = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|max:20',
            'email' => 'required|email'
        ]);
        if ($validator->passes()) {

            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->designation = $request->designation;
            $user->save();

            session()->flash('success', 'Profile updated successfully.');

            return redirect('/account/profile');
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()

            ]);
        }
    }



    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login');
    }


    public function updateProfilePic(Request $request)
    {

        $id = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'image' => 'required|image'
        ]);
        if ($validator->passes()) {
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = $id . '-' . time() . '.' . $ext;
            $image->move(public_path('/profilePic/'), $imageName);

            User::where('id', $id)->update(['image' => $imageName]);

            Session()->flash('success', 'Profile picture updated successfully.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function createJob()
    {

        $categories = Category::orderBy('name', 'ASC')->where('status', 1)->get();
        $jobTypes = JobType::orderBy('name', 'ASC')->where('status', 1)->get();

        return view('front.account.job.create', [
            'categories' => $categories,
            'jobTypes' => $jobTypes,
        ]);
    }
    public function saveJob(Request $request)
    {

        $rules = [
            'title' => 'required|min:5|max:100',
            'category' => 'required',
            'jobType' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',

        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

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

            session()->flash('success', 'Job posted successfully.');

        // Redirect to a route or URL that displays the success message
        return redirect()->route('account.createJob');

    } else {
        // Redirect back with errors
        return redirect()->back()->withErrors($validator)->withInput();
    }
    }

    public function myJobs()
    {

        $jobs = Job::where('user_id', Auth::user()->id)->with('jobType')->orderBy('created_at', 'DESC')->paginate(10);
        return view('front.account.job.my-jobs', [
            'jobs' => $jobs
        ]);
    }

    public function editJob(Request $request, $id)
    {

        $categories = Category::orderBy('name', 'ASC')->where('status', 1)->get();
        $jobTypes = JobType::orderBy('name', 'ASC')->where('status', 1)->get();

        $user = Auth::user();

        $job = Job::where('user_id', $user->id)->where('id', $id)->first();

        if ($job == null) {
            abort(404);
        }
        return view('front.account.job.edit', [
            'categories' => $categories,
            'jobTypes' => $jobTypes,
            'job' => $job,
        ]);
    }

    public function updateJob(Request $request, $id)
    {
        $rules = [
            'title' => 'required|min:5|max:100',
            'category' => 'required',
            'jobType' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',

        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

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

            session()->flash('success', 'Job updated successfully.');

            return response()->json([
                'status' => true,
                'errors' => []

            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->erros()

            ]);
        }
    }
    public function deleteJobs(Request $request)
    {
        $job = Job::where([
            'user_id' => Auth::user()->id,
            'id' => $request->jobId
        ])->first();

        if ($job == null) {
            session()->flash('error', 'Either job deleted or not found.');
            return response()->json([
                'status' => true
            ]);
        }
        Job::where('id', $request->jobId)->delete();
        session()->flash('success', 'Job deleted succesfully.');
        return response()->json([
            'status' => true
        ]);
    }
    public function myJobApplications()
    {
        $jobApplications = JobApplication::where('user_id', Auth::user()->id)
            ->with(['job', 'job.jobType', 'job.applications'])
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return view('front.account.job.my-job-applications', [
            'jobApplications' => $jobApplications
        ]);
    }
    public function removeJobs(Request $request)
    {
        $jobApplication = JobApplication::where(
            [
                'id' => $request->id,
                'user_id' => Auth::user()->id
            ]
        )->first();

        if ($jobApplication == null) {
            session()->flash('error', 'Job application not found');
            return response()->json([
                'status' => false,
            ]);
        }

        JobApplication::find($request->id)->delete();
        session()->flash('success', 'Job application removed successfully.');

        return response()->json([
            'status' => true,
        ]);
    }
    public function savedJobs()
    {
        // $jobApplications = JobApplication::where('user_id',Auth::user()->id)
        //         ->with(['job','job.jobType','job.applications'])
        //         ->paginate(10);

        $savedJobs = SavedJob::where([
            'user_id' => Auth::user()->id
        ])->with(['job', 'job.jobType', 'job.applications'])
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return view('front.account.job.saved-jobs', [
            'savedJobs' => $savedJobs
        ]);
    }
    public function removeSavedJob(Request $request)
    {
        $savedJob = SavedJob::where(
            [
                'id' => $request->id,
                'user_id' => Auth::user()->id
            ]
        )->first();

        if ($savedJob == null) {
            session()->flash('error', 'Job not found :(');
            return response()->json([
                'status' => false,
            ]);
        }

        SavedJob::find($request->id)->delete();
        session()->flash('success', 'Job removed successfully.');

        return response()->json([
            'status' => true,
        ]);
    }
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            session()->flash('updatePasswordError',"Validation error");
            return redirect('/account/profile');
        }

        if (Hash::check($request->old_password, Auth::user()->password) == false) {
            session()->flash('updatePasswordError', 'Your old password is incorrect.');
            return redirect('/account/profile');
        }


        $user = User::find(Auth::user()->id);
        $user->password = Hash::make($request->new_password);
        $user->save();

        session()->flash('success', 'Password updated successfully.');
        return response()->json([
            'status' => true
        ]);
    }
    public function saveFreelancer(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'freelancer_id' => 'required|exists:freelancers,id',
            // Add more validation rules as needed
        ]);

        // Retrieve the freelancer ID from the request
        $freelancerId = $request->input('freelancer_id');

        // Perform any necessary logic to save the freelancer
        // For example, you can save the freelancer ID to the user's profile, etc.

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Freelancer saved successfully');
    }
}
