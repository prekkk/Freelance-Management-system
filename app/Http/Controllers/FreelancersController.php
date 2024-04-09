<?php

namespace App\Http\Controllers;
use App\Models\Freelancer;
use App\Models\SavedFreelancer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FreelancersController extends Controller
{
    public function show($id)
    {
        // Fetch the freelancer data
        $freelancer = Freelancer::findOrFail($id);
        
        // Pass the freelancer data to the Blade view
        return view('freelancer_detail', ['freelancer' => $freelancer]);
    }
    // This method will show the freelancers page
    public function index(Request $request)
    {
        // Fetch all freelancers
        $freelancers = Freelancer::where('status', 1);

        // Apply filtering based on location
        if ($request->has('location')) {
            $freelancers->where('location', $request->location);
        }

        // Apply filtering based on designation
        if ($request->has('designation')) {
            $freelancers->where('designation', $request->designation);
        }

        // Paginate the results
        $freelancers = $freelancers->paginate(9);

        return view('front.freelancers', [
            'freelancers' => $freelancers,
        ]);
    }



   // This method will show freelancer detail page
   public function detail($id)
   {
       $freelancer = Freelancer::where([
           'id' => $id,
           'status' => 1
       ])->first();

       if ($freelancer == null) {
           abort(404);
       }

       // You can fetch additional data related to the freelancer here

       return view('front.freelancerDetail', ['freelancer' => $freelancer]);
   }

   public function save(Request $request)
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


    // This method will show the list of freelancers for admin
    public function freelancerlist()
    {
        // Fetch all freelancers
        $freelancers = Freelancer::paginate(10);

        return view('admin.freelancers.index', ['freelancers' => $freelancers]);
    }
    // This method will show the form to edit a freelancer
    public function edit($id)
    {
        $freelancer = Freelancer::findOrFail($id);

        return view('admin.freelancers.edit', ['freelancer' => $freelancer]);
    }

   // This method will update the freelancer data
public function update(Request $request, $id)
{
    Log::debug($request->all());

    $freelancer = Freelancer::findOrFail($id);

    // Validate the request data including the reward points field
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

