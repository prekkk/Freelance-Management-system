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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:1000',
        ]);

        // Create a new Feedback instance
        $feedback = new Feedback();
        $feedback->name = $request->name;
        $feedback->email = $request->email;
        $feedback->message = $request->message;
        $feedback->save();

        // Optionally, you can add a success message here
        // to display to the user

        // Redirect the user back to the feedback form with a success message
        return redirect()->route('feedback.create')->with('success', 'Thank you for your feedback!');
    }
}
