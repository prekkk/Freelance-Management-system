@extends('front.layouts.app')

@section('main')
<section class="section-4 bg-2">
    <div class="container pt-5">
        <div class="row">
            <div class="col">
                <!-- Breadcrumb navigation -->
            </div>
        </div>
    </div>
    <div class="container freelancer_details_area">
        <div class="row pb-5">
            <div class="col-md-8">
                <!-- Display error or success messages -->
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="card shadow border-0">
                    <div class="card-body">
                        <div class="freelancer_details_header">
                            <!-- Freelancer details -->
                            <div class="single_freelancer d-flex justify-content-between align-items-center">
                                <div class="freelancer_left">
                                    <!-- Fetch and display the image -->
                                    @if ($freelancer->profile_picture)
                                        <img src="{{ asset($freelancer->profile_picture) }}" alt="Freelancer Image" class="img-fluid mb-3" style="max-width: 50px; height: 50px;">
                                    @endif
                                </div>
                                <div class="freelancer_content">
                                    <!-- Display name with custom font and adjusted font size -->
                                    <h4 style="font-family: 'Arial', sans-serif; font-size: 24px; margin-top: 20px;">{{ $freelancer->name }}</h4>
                                    <!-- Display mobile number with adjusted font size -->
                                    <p style="font-family: 'Arial', sans-serif; font-size: 18px;">Mobile: {{ $freelancer->mobile }}</p>
                                    <!-- Display designation with adjusted font size -->
                                    <p style="font-family: 'Arial', sans-serif; font-size: 18px;">Designation: {{ $freelancer->designation }}</p>
                                    <!-- Display address with adjusted font size -->
                                    <p style="font-family: 'Arial', sans-serif; font-size: 18px;">Address: {{ $freelancer->address }}</p>
                                    <!-- Display location with adjusted font size -->
                                    <p style="font-family: 'Arial', sans-serif; font-size: 18px;">Location: {{ $freelancer->location }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="descript_wrap mt-4">
                            <!-- Display short description -->
                            <div class="single_wrap">
                                <h4 style="font-family: 'Arial', sans-serif; font-size: 20px;">Short Description</h4>
                                <p style="font-family: 'Arial', sans-serif; font-size: 18px;">{{ $freelancer->short_description }}</p>
                            </div>
                            <!-- Display feedback from other users -->
                            <div class="single_wrap">
                                <h4 style="font-family: 'Arial', sans-serif; font-size: 20px;">Feedback</h4>
                                <div class="feedback_list">
                                    @if ($freelancer->feedbacks()->count() > 0)
                                        <ul>
                                            @foreach ($freelancer->feedbacks as $feedback)
                                                <li style="font-family: 'Arial', sans-serif; font-size: 18px;">{{ $feedback->message }}</li>
                                                <!-- Display other feedback details -->
                                            @endforeach
                                        </ul>
                                    @else
                                        <p style="font-family: 'Arial', sans-serif; font-size: 18px;">No feedback available</p>
                                    @endif
                                </div>
                            </div>
                            <!-- Save freelancer button (for employers) -->
                            <div class="border-bottom"></div>
                            <div class="pt-3 text-end">
                                @if (Auth::check() && Auth::user()->role == 'employer')
                                    <a href="" onclick="saveFreelancer({{ $freelancer->id }});" class="btn btn-secondary">Save</a>  
                                    <a href="" onclick="hireFreelancer({{ $freelancer->id }})" class="btn btn-primary">Hire</a>
                                @else
                                    <a href="{{ route('account.login') }}" class="btn btn-secondary disabled">Login to Save</a>
                                    <a href="{{ route('account.login') }}" class="btn btn-primary disabled">Login to Apply</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')

@endsection
