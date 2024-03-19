<?php

namespace App\Http\Controllers;
use App\Models\Freelancer;

use Illuminate\Http\Request;

class FreelancersController extends Controller
{
    // This method will show the freelancers page
    public function index(Request $request)
    {
        // Fetch all freelancers
        $freelancers = Freelancer::where('status', 1);

        // You can apply similar filtering logic here based on your requirements
        // For example, filtering by category, location, etc.

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

        // return view('front.freelancerDetail', ['freelancer' => $freelancer]);
    }
}
