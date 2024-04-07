<?php

namespace App\Http\Controllers;
use App\Models\Freelancer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FreelancersController extends Controller
{
    // This method will show the list of freelancers for admin
    public function index()
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
    $freelancer = Freelancer::findOrFail($id);

    $request->validate([
        'name' => 'required',
        'designation' => 'required',
        'location' => 'required',
    ]);

    $data = $request->except(['_token']); // Exclude _token field from request data

    $freelancer->update($data);

    return redirect()->route('admin.freelancers.index')
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
}