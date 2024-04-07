<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use App\Models\Freelancer;

class FeedbackController extends Controller
{
    /**
     * Show the feedback form.
     *
     * @return \Illuminate\View\View
     */
    public function create($freelancer_id)
    {
        // Get the freelancer based on the ID
        $freelancer = Freelancer::findOrFail($freelancer_id);

        return view('front.feedback', ['freelancer' => $freelancer]);
    }

    /**
     * Store the feedback data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
{
    // Validate the request data
    $request->validate([
        'message' => 'required|string|max:1000',
        'freelancer_id' => 'required|exists:freelancers,id', // Ensure the freelancer_id is provided and exists in the freelancers table
    ]);

    // Create a new Feedback instance
    $feedback = new Feedback();
    $feedback->message = $request->message;
    $feedback->freelancer_id = $request->freelancer_id; // Assign the freelancer_id
    $feedback->save();

    // Redirect the user back to the feedback form with a success message
    return redirect()->route('feedback.create', ['freelancer_id' => $request->freelancer_id])->with('success', 'Thank you for your feedback!');
}
}
