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
            ->orderBy('vacancy', 'DESC')
            ->with('jobType')
            // ->where('isFeatured', 1)
            ->take(6)
            ->get();

        $latestJobs = Job::where('status', 1)
            ->with('jobType')
            ->orderBy('created_at', 'DESC')
            ->take(6)
            ->get();


            $categoryImages = [
                asset('assets/images/categories/Care_Taking.png'),
                asset('assets/images/categories/cleaning.png'),
                asset('assets/images/categories/Events_Management.png'),
                asset('assets/images/categories/Home_Repairs_and_Maintenance.png'),
                asset('assets/images/categories/Maid_Jobs.png'),
                asset('assets/images/categories/personal.png'),
                asset('assets/images/categories/Remote_Jobs.png'),
                asset('assets/images/categories/tutoring.png'),
                
            ];
         
            // Define carousel images
        $carouselImages = [
            asset('assets/images/images1.png'),
            asset('assets/images/images2.png'),
            asset('assets/images/images3.png'),
            asset('assets/images/images4.png'),
            asset('assets/images/images5.png'),
            asset('assets/images/images6.png'),
            asset('assets/images/images7.png'),
            asset('assets/images/images8.png'),
            // Add more image URLs if needed
        ];
        return view('front.home', [
            'categories' => $categories,
            'featuredJobs' => $featuredJobs,
            'latestJobs' => $latestJobs,
            'newCategories' => $newCategories,
            'carouselImages' => $carouselImages,
            'categoryImages' => $categoryImages,
        ]);
    }
}