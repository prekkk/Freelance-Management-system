<?php

namespace App\Http\Controllers\admin;
use App\Models\JobApplication;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Job;

class JobApplicationController extends Controller
{
    public function index()
{
    // Fetch all jobs
    $jobs = Job::orderByDesc('created_at')->get();

    // Filter out jobs with accepted applications
    $jobsWithoutAcceptedApplications = $jobs->filter(function ($job) {
        foreach ($job->applications as $application) {
            if ($application->status === 'accepted') {
                return false;
            }
        }
        return true;
    });

    return view('admin.job-applications.list', compact('jobsWithoutAcceptedApplications'));
}

    public function destroy(Request $request)
    {
        $id = $request->id;

        $jobApplication = JobApplication::find($id);

        if ($jobApplication == null) {
            session()->flash('error', 'Either job application deleted or not found.');
            return response()->json([
                'status' => false
            ]);
        }

        $jobApplication->delete();
        session()->flash('success', 'Job application deleted successfully.');
        return response()->json([
            'status' => true
        ]);
    }

    public function updateStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;

        $jobApplication = JobApplication::find($id);

        if ($jobApplication == null) {
            session()->flash('error', 'Job application not found.');
            return response()->json([
                'status' => false
            ]);
        }

        $jobApplication->status = $status;
        $jobApplication->save();

        session()->flash('success', 'Job application status updated successfully.');
        return response()->json([
            'status' => true
        ]);
    }

    public function acceptApplication(Request $request)
    {
        $id = $request->id;

        $jobApplication = JobApplication::find($id);

        if ($jobApplication == null) {
            session()->flash('error', 'Job application not found.');
            return response()->json([
                'status' => false
            ]);
        }

        $jobApplication->status = 'accepted';
        $jobApplication->save();

        session()->flash('success', 'Job application accepted successfully.');
    return Redirect::route('account.myJobs'); 
}

    public function rejectApplication(Request $request)
    {
        $id = $request->id;

        $jobApplication = JobApplication::find($id);

        if ($jobApplication == null) {
            session()->flash('error', 'Job application not found.');
            return response()->json([
                'status' => false
            ]);
        }

        $jobApplication->status = 'rejected';
        $jobApplication->save();

        session()->flash('success', 'Job application rejected successfully.');
    return Redirect::route('account.myJobs'); 
}

}
