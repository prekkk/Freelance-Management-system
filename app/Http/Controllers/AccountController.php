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
use App\Models\Freelancer;
use App\Models\SavedJob;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Storage;
// use Infobip\Configuration;
// use Infobip\Api\SmsApi; 
// use Infobip\Model\SmsDestination;
// use Infobip\Model\SmsTextualMessage;
// use Infobip\Model\SmsAdvancedTextualRequest;
// require_once __DIR__ . '/vendor/autoload.php';

class AccountController extends Controller
{
    //This method shows user registration page
    public function registration()
    {
        return view('front.account.registration');
    }

    public function processRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|same:confirm_password',
            'confirm_password' => 'required',

        ]);

        if ($validator->passes()) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);

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


    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        // Attempt to authenticate the user
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Check if a role was selected
            if ($request->has('selected_role') && in_array($request->input('selected_role'), ['employer', 'freelancer'])) {
                // Store the selected role in the session
                session(['selected_role' => $request->input('selected_role')]);
            }

            // Redirect to the appropriate page based on the role
            $role = session('selected_role', ''); // Retrieve the selected role from the session
            if ($role === 'employer') {
                // Redirect to the employer dashboard
                return redirect()->route('employer.dashboard');
            } elseif ($role === 'freelancer') {
                // Redirect to the freelancer dashboard
                return redirect()->route('freelancer.dashboard');
            } else {
                // Default redirection if no role is specified
                return redirect()->route('account.profile');
            }
        } else {
            // Authentication failed, redirect back to login page with error message
            return redirect()->route('account.login')->with('error', 'Invalid credentials.');
        }
    }

    public function chooseRole()
    {
        // Retrieve the selected role from the session or request
        $selectedRole = session('selected_role'); // Assuming you stored it in the session

        // Pass the selected role to the view
        return view('front.account.choose-role', compact('selectedRole'));
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
            'email' => 'required|email',
            'mobile' => 'required|digits:10',
        ]);

        if ($validator->passes()) {
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->address = $request->address;
            $user->save();

            // Flash success message
            session()->flash('success', 'Profile updated successfully.');

            return response()->json([
                'status' => true,
            ]);
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
            'title' => 'required|min:2|max:100',
            'category' => 'required',
            'jobType' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',
            'responsibilities' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $imagePath = null;

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('public/job_images');
                $imagePath = Storage::url($path);
            }

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
            $job->responsibilities = $request->responsibility;
            $job->qualifications = $request->qualifications;
            $job->keywords = $request->keywords;
            $job->image = $imagePath;
            $job->save();

            return response()->json([
                'status' => true,
                'message' => 'Job posted successfully.',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
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
            'id'    => $id
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
            'responsibilities' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $job = Job::find($id);

            if (!$job) {
                // Handle case where job with the given ID is not found
                return redirect()->back()->with('error', 'Job not found.');
            }

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

            return redirect()->route('account.myJobs');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }


    public function deleteJobs(Request $request)
    {
        $job = Job::where([
            'user_id' => Auth::user()->id,
            'id' => $request->jobId
        ])->first();

        if ($job == null) {
            return redirect()->route('account.myJobs')->with('error', 'Either job deleted or not found.');
        }

        $job->delete();

        return redirect()->route('account.myJobs')->with('success', 'Job deleted successfully.');
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
            session()->flash('updatePasswordError', "Validation error");
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

    public function createFreelancer()
    {
        return view('front.account.freelancers.create');
    }

    public function saveFreelancer(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'designation' => 'nullable',
            'location' => 'nullable',
            'mobile' => 'nullable',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            // Handle image upload if needed
            $imagePath = null;
            if ($request->hasFile('profile_picture')) {
                $path = $request->file('profile_picture')->store('public/freelancer_images');
                $imagePath = Storage::url($path);
            }
            // Create freelancer instance and save to database
            $freelancer = new Freelancer();
            $freelancer->name = $request->name;
            $freelancer->designation = $request->designation;
            $freelancer->email = $request->email;
            $freelancer->location = $request->location;
            $freelancer->mobile = $request->mobile;
            $freelancer->description = $request->description;
            $freelancer->profile_picture = $imagePath;
            $freelancer->save();
            return response()->json([
                'status' => true,
                'message' => 'Skill Posted successfully.',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
    }

    // public function myFreelancers()
    // {
    //     $freelancers = Freelancer::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(10);
    //     return view('front.account.freelancer.my-freelancers', [
    //         'freelancers' => $freelancers
    //     ]);
    // }

    // public function editFreelancer(Request $request, $id)
    // {
    //     $freelancer = Freelancer::where('user_id', Auth::user()->id)->find($id);

    //     if (!$freelancer) {
    //         abort(404);
    //     }

    //     return view('front.account.freelancer.edit', [
    //         'freelancer' => $freelancer,
    //     ]);
    // }
    // public function updateFreelancer(Request $request, $id)
    // {
    //     $rules = [
    //         'name' => 'required|min:2|max:100',
    //         'designation' => 'nullable|max:255',
    //         'email' => 'required|email|max:255',
    //         'location' => 'nullable|max:50',
    //         'mobile' => 'required|min:10|max:10',
    //         'description' => 'nullable|max:100',

    //     ];

    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->passes()) {
    //         $freelancer = Freelancer::where('user_id', Auth::user()->id)->find($id);

    //         if (!$freelancer) {
    //             return redirect()->back()->with('error', 'Freelancer not found.');
    //         }

    //         $freelancer->name = $request->name;
    //         $freelancer->designation = $request->designation;
    //         $freelancer->email = $request->email;
    //         $freelancer->location = $request->location;
    //         $freelancer->mobile = $request->mobile;
    //         $freelancer->description = $request->description;
    //         $freelancer->save();

    //         session()->flash('success', 'Freelancer updated successfully.');

    //         return redirect()->route('account.editFreelancer', $id);
    //     } else {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }
    // }

    // public function deleteFreelancer(Request $request)
    // {
    //     $freelancer = Freelancer::where([
    //         'user_id' => Auth::user()->id,
    //         'id' => $request->freelancerId
    //     ])->first();

    //     if (!$freelancer) {
    //         session()->flash('error', 'Freelancer not found.');
    //         return redirect()->route('account.myFreelancers');
    //     }

    //     $freelancer->delete();
    //     session()->flash('success', 'Freelancer deleted successfully.');
    //     return redirect()->route('account.myFreelancers');
    // }
}
