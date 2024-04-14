<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\FreelancersController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\admin\JobController;
use App\Http\Controllers\admin\JobApplicationController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\KhaltiPaymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Set Nepali as the default locale
$locale = App::currentLocale();
App::setLocale('ne');

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [JobsController::class, 'index'])->name('jobs');
Route::get('/freelancers', [FreelancersController::class, 'index'])->name('freelancers');
Route::get('/freelancer/detail/{id}', [FreelancersController::class, 'detail'])->name('freelancer.show');
Route::post('/freelancer/save', [FreelancersController::class, 'save'])->name('saveFreelancer');
Route::get('/jobs/detail/{id}', [JobsController::class, 'detail'])->name('jobDetail');
Route::get('/feedback/create/{freelancer_id}', [FeedbackController::class, 'create'])->name('feedback.create');
Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
Route::post('/apply-job', [JobsController::class, 'applyJob'])->name('applyJob');
Route::post('/save-job', [JobsController::class, 'saveJob'])->name('saveJob');
// Route for Khalti payment verification
Route::post('/ajax/khalti/verify_job', [KhaltiPaymentController::class, 'verifyJob'])->name('ajax.khalti.verify_job');

Route::group(['prefix' => 'admin', 'middleware' => 'checkRole'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/users/{id}', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/jobs', [JobController::class, 'index'])->name('admin.jobs');
    Route::get('/jobs/edit/{id}', [JobController::class, 'edit'])->name('admin.jobs.edit');
    Route::put('/jobs/{id}', [JobController::class, 'update'])->name('admin.jobs.update');
    Route::delete('/jobs', [JobController::class, 'destroy'])->name('admin.jobs.destroy');
    Route::get('/job-applications', [JobApplicationController::class, 'index'])->name('admin.jobApplications');
    Route::delete('/job-applications', [JobApplicationController::class, 'destroy'])->name('admin.jobApplications.destroy');
    Route::get('/admin/freelancers', [FreelancersController::class, 'freelancerlist'])->name('admin.freelancers.freelancerlist');
    Route::get('/freelancers/{id}/edit', [FreelancersController::class, 'edit'])->name('admin.freelancers.edit');
    Route::put('/freelancers/{id}', [FreelancersController::class, 'update'])->name('admin.freelancers.update');
    Route::delete('/freelancers/{id}', [FreelancersController::class, 'destroy'])->name('admin.freelancers.destroy');
});


Route::group(['prefix' => 'account'], function () {

    // Guest Route
    Route::group(['middleware' => 'guest'], function () {
        Route::get('/register', [AccountController::class, 'registration'])->name('account.registration');
        Route::post('/process-register', [AccountController::class, 'processRegistration'])->name('account.processRegistration');
        Route::get('/login', [AccountController::class, 'login'])->name('account.login');
        Route::post('/authenticate', [AccountController::class, 'authenticate'])->name('account.athenticate');
    });
    // Authenticated Routes

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::get('/logout', [AccountController::class, 'logout'])->name('account.logout');
        Route::put('/update-profile', [AccountController::class, 'updateProfile'])->name('account.updateProfile');
        Route::post('/update-profile-pic', [AccountController::class, 'updateProfilePic'])->name('account.updateProfilePic');
        Route::post('/create-job', [AccountController::class, 'createJob'])->name('account.createJob');
        Route::post('/save-job', [AccountController::class, 'saveJob'])->name('account.saveJob');
        Route::get('/my-jobs', [AccountController::class, 'myJobs'])->name('account.myJobs');
        Route::get('/my-jobs/edit/{jobId}', [AccountController::class, 'editJob'])->name('account.editJob');
        Route::post('/update-job/{jobId}', [AccountController::class, 'updateJob'])->name('account.updateJob');
        Route::post('/delete-job', [AccountController::class, 'deleteJob'])->name('account.deleteJob');
        Route::get('/my-job-applications', [AccountController::class, 'myJobApplications'])->name('account.myJobApplications');

        Route::post('/remove-job-application', [AccountController::class, 'removeJobs'])->name('account.removeJobs');
        Route::get('/saved-jobs', [AccountController::class, 'savedJobs'])->name('account.savedJobs');
        Route::post('/remove-saved-job', [AccountController::class, 'removeSavedJob'])->name('account.removeSavedJob');
        Route::post('/update-password', [AccountController::class, 'updatePassword'])->name('account.updatePassword');
        Route::post('/save-freelancer', [AccountController::class, 'saveFreelancer'])->name('account.saveFreelancer');
    });
   
    Route::get('/change-language/{locale}', function ($locale) {
        if (! in_array($locale, ['en', 'ne'])) {
            abort(400);
        }
        // Check if the requested locale is supported
        if (\array_key_exists($locale, config('app.supported_locales', []))) {
            // Set the application locale
            \Illuminate\Support\Facades\App::setLocale($locale);
            // Store the selected locale in the session
            session()->put('locale', $locale);
        }
        // Redirect back to the previous page or homepage
        return redirect()->back();
    })->name('changeLanguage');
    
});
