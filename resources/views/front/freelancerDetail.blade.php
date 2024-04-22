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
                                        <img src="{{ asset($freelancer->profile_picture) }}" alt="Freelancer Image" class="img-fluid mb-3" style='max-width:200px; height:200px;'>
                                    @endif
                                    <div class="freelancer_content">
                                        <h4>{{ $freelancer->name }}</h4>
                                        <p>{{ $freelancer->designation }}</p>
                                        <p>{{ $freelancer->location }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="descript_wrap mt-4">
                            <!-- Display short description -->
                            <div class="single_wrap">
                                <h4>Short Description</h4>
                                <p>{{ $freelancer->short_description }}</p>
                            </div>
                            <!-- Display feedback from other users -->
                            <div class="single_wrap">
                                <h4>Feedback</h4>
                                <div class="feedback_list">
                                    @if ($freelancer->feedbacks()->count() > 0)
                                        <ul>
                                            @foreach ($freelancer->feedbacks as $feedback)
                                                <li>{{ $feedback->message }}</li>
                                                <!-- Display other feedback details -->
                                            @endforeach
                                        </ul>
                                    @else
                                        <p>No feedback available</p>
                                    @endif
                                </div>
                            </div>
                            <!-- Save freelancer button -->
                            <div class="text-end mt-4">
                                <form action="{{ route('account.saveFreelancer') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="freelancer_id" value="{{ $freelancer->id }}">
                                    <button type="submit" class="btn btn-primary btn-sm">Save Freelancer</button>
                                </form>
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
