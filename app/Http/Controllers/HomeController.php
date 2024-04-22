<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class HomeController extends Controller
{
    // This method will show our home page
    public function index(Request $request)
    {
        // Get the selected locale from the request
        $locale = $request->get('locale');

        // Check if the selected locale is supported
        if (array_key_exists($locale, config('app.supported_locales'))) {
            // Set the application locale
            App::setLocale($locale);
            // Store the selected locale in the session
            session()->put('locale', $locale);
        }

        // Fetch categories with their respective job counts
        $categories = Category::withCount('jobs')
            ->where('status', 1)
            ->orderBy('name', 'ASC')
            ->take(8)
            ->get();

        $newCategories = Category::where('status', 1)
            ->orderBy('name', 'ASC')
            ->get();

        $featuredJobs = Job::where('status', 1)
            ->orderBy('created_at', 'DESC')
            ->with('jobType')
            ->where('isFeatured', 1)
            ->take(6)
            ->get();

        $latestJobs = Job::where('status', 1)
            ->with('jobType')
            ->orderBy('created_at', 'DESC')
            ->take(6)
            ->get();

        return view('front.home', [
            'categories' => $categories,
            'featuredJobs' => $featuredJobs,
            'latestJobs' => $latestJobs,
            'newCategories' => $newCategories
        ]);
    }
}