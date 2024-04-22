<?php

namespace App\Http\Controllers;
use App\Models\Freelancer;
use App\Models\SavedFreelancer;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FreelancersController extends Controller
{
    // Method to display the details of a freelancer
    public function show($id)
    {
        // Fetch the freelancer data
        $freelancer = Freelancer::findOrFail($id);
        
        // Pass the freelancer data to the Blade view
        return view('freelancer_detail', ['freelancer' => $freelancer]);
    }
    
    // Method to display the list of freelancers
    public function index(Request $request)
    {
        $freelancers = Freelancer::query();

        if ($request->has('location')) {
            $freelancers->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->has('designation')) {
            $freelancers->where('designation', 'like', '%' . $request->designation . '%');
        }

        // Apply sorting
        if ($request->has('sort')) {
            $freelancers->orderBy('created_at', $request->sort == '1' ? 'desc' : 'asc');
        }

        $freelancers = $freelancers->paginate(9);

        return view('front.freelancers', compact('freelancers'));
    }
    
    // Method to display the details of a freelancer
    public function detail($id)
    {
        // Fetch the freelancer data
        $freelancer = Freelancer::findOrFail($id);
    
        // Fetch the feedback associated with the freelancer
        $feedback = Feedback::where('freelancer_id', $id)->get();
    
        // Calculate the reward points (assuming 3 positive feedbacks = 1 reward point)
        $positiveFeedbackCount = $feedback->where('feedback_type', 'Positive')->count();
        $rewardPoints = floor($positiveFeedbackCount / 3);
    
        // Pass the freelancer data, feedback, and reward points to the view
        return view('front.freelancerDetail', [
            'freelancer' => $freelancer,
            'feedback' => $feedback,
            'rewardPoints' => $rewardPoints
        ]);
    }
    
    // Method to save a freelancer
    public function save(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'email' => 'required|email|unique:freelancers,email',
            'location' => 'required|string|max:255',
            'short_description' => 'required|string|max:1000',
            'profile_picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Maximum file size of 2MB
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $profilePicture = $request->file('profile_picture');
            $imageName = time().'.'.$profilePicture->extension();
            $profilePicture->move(public_path('uploads/freelancers'), $imageName);
            $validatedData['profile_picture'] = 'uploads/freelancers/'.$imageName;
        }

        // Save the freelancer
        Freelancer::create($validatedData);

        return redirect()->back()->with('success', 'Freelancer saved successfully');
    }

    // Method to display the list of freelancers for admin
    public function freelancerlist()
    {
        // Fetch all freelancers
        $freelancers = Freelancer::paginate(10);

        return view('admin.freelancers.index', ['freelancers' => $freelancers]);
    }
    
    // Method to display the form to edit a freelancer
    public function edit($id)
    {
        $freelancer = Freelancer::findOrFail($id);

        return view('admin.freelancers.edit', ['freelancer' => $freelancer]);
    }

    // Method to update the freelancer data
    public function update(Request $request, $id)
    {
        Log::debug($request->all());

        $freelancer = Freelancer::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'designation' => 'required',
            'location' => 'required',
            'rewards' => 'nullable|integer|min:0', // Add validation for reward points
        ]);

        // Retrieve all the data except for _token from the request
        $data = $request->except(['_token']);

        // Update the freelancer data
        $freelancer->update($data);

        return redirect()->route('admin.freelancers.freelancerlist')
                         ->with('success', 'Freelancer updated successfully');
    }

    // Method to delete a freelancer
    public function destroy($id)
    {
        try {
            $freelancer = Freelancer::findOrFail($id);
            $freelancer->delete();

            return redirect()->route('admin.freelancers.index')
                             ->with('success', 'Freelancer deleted successfully');
        } catch (\Exception $e) {
            // Log the error or handle it appropriately
            Log::error('Error deleting freelancer: ' . $e->getMessage());
            
            return redirect()->route('admin.freelancers.index')
                             ->with('error', 'Failed to delete freelancer');
        }
    }
    
    // Method to remove a saved freelancer
    public function removeSavedFreelancer(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'id' => 'required|exists:saved_freelancers,id',
        ]);

        // Find the saved freelancer by ID and delete it
        $savedFreelancer = SavedFreelancer::findOrFail($request->id);
        $savedFreelancer->delete();

        // Return a JSON response indicating success
        return response()->json(['success' => true]);
    }
}