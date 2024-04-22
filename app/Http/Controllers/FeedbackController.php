<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use App\Models\Freelancer;
use App\Events\FeedbackCreated;
use App\Services\FreelancerService;


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
            'message' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5', // Validate rating
            'feedback_type' => 'required|in:Positive,Neutral,Negative', // Validate feedback_type
            'freelancer_id' => 'required|integer|exists:freelancers,id', // Ensure the freelancer_id is provided and exists in the freelancers table
        ]);

        // Create a new Feedback instance
        $feedback = new Feedback();
        $feedback->message = $request->message;
        $feedback->rating = $request->rating;
        $feedback->feedback_type = $request->feedback_type;
        $feedback->freelancer_id = $request->freelancer_id; // Assign the freelancer_id
        $feedback->save();

        // Call FreelancerService to calculate reward points
        FreelancerService::calculateRewardPoints($request->freelancer_id);

        // Dispatch the FeedbackCreated event
        event(new FeedbackCreated($feedback));

        // Redirect the user back to the feedback form with a success message
        return redirect()->route('feedback.create', ['freelancer_id' => $request->freelancer_id])->with('success', 'Thank you for your feedback!');
    }
}