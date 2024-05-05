@extends('front.layouts.app')

@section('main')
    <section class="section-0 lazy d-flex bg-image-style dark align-items-center " class=""
        data-bg="{{ asset('assets/images/banner5.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-12 col-xl-8">
                    <h1>Find your dream job</h1>
                    {{-- <h1>{{ __('messages.find_dream_job') }}</h1> --}}
                    <p>Thounsands of jobs available.</p>
                    {{-- <h1>{{ __('messages.thousands_of_jobs_availabl --}}
                        
                    <div class="banner-btn mt-5"><a href="#" class="btn btn-primary mb-4 mb-sm-0">Explore Now</a></div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-1 py-5 ">
        <div class="container">
            <div class="card border-0 shadow p-5">
                <form action="{{ route('jobs') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3 mb-3 mb-sm-3 mb-lg-0">
                            <input type="text" class="form-control" name="keyword" id="keyword" placeholder="Keywords">
                        </div>
                        <div class="col-md-3 mb-3 mb-sm-3 mb-lg-0">
                            <input type="text" class="form-control" name="location" id="location"
                                placeholder="Location">
                        </div>
                        <div class="col-md-3 mb-3 mb-sm-3 mb-lg-0">
                            <select name="category" id="category" class="form-control">
                                <option value="">Select a Category</option>
                                @if ($newCategories->isNotEmpty())
                                    @foreach ($newCategories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class=" col-md-3 mb-xs-3 mb-sm-3 mb-lg-0">
                            <div class="d-grid gap-2">
                                {{-- <a href="jobs.html" class="btn btn-primary btn-block">Search</a> --}}
                                <button type="submit" class="btn btn-primary btn-block">Search</button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="carousel-section py-5">
        <div class="container">
            <h2>Our Work</h2>
            <div class="row pt-5">
            <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @php
                        $chunks = array_chunk($carouselImages, 4); // Split the images array into chunks of 4
                    @endphp
                    @foreach ($chunks as $chunk)
                        <div class="carousel-item{{ $loop->first ? ' active' : '' }}">
                            <div class="row justify-content-center">
                                @foreach ($chunk as $image)
                                    <div class="col-md-3">
                                        <div class="carousel-image-container">
                                            <img src="{{ $image }}" alt="Image" class="carousel-image rounded-rectangle">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                </div>
                
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>     
    

    <section class="section-2 bg-2 py-5">
        <div class="container">
            <h2>Popular Categories</h2>
            <div class="row pt-5">
                @if ($categories->isNotEmpty())
                    @foreach ($categories as $category)
                        @php
                            // Count the number of jobs for the current category
                            $jobsCount = $category->jobs->count();
                            // Constructing the image URL based on the category name
                            $imageName = strtolower(str_replace(' ', '_', $category->name));
                            $imageUrl = asset("assets/images/categories/{$imageName}.png");
                        @endphp
                        <div class="col-lg-4 col-xl-3 col-md-6">
                            <div class="single_catagory">
                                <a href="{{ route('jobs') . '?category=' . $category->id }}">
                                    <img src="{{ $imageUrl }}" alt="{{ $category->name }}" class="category-image" style="max-width: 30%; height: auto;">
                                    <h4 class="pb-2">{{ $category->name }}</h4>
                                </a>
                                <p class="mb-0"><span>{{ $jobsCount }}</span> Available vacancy</p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>     
              

    <section class="section-3  py-5">
        <div class="container">
            <h2>Featured Jobs</h2>
            <div class="row pt-5">
                <div class="job_listing_area">
                    <div class="job_lists">
                        <div class="row">
                            @if ($featuredJobs->isNotEmpty())
                                @foreach ($featuredJobs as $featuredJob)
                                    <div class="col-md-4">
                                        <div class="card border-0 p-3 shadow mb-4">
                                            <div class="card-body">
                                                <h3 class="border-0 fs-5 pb-2 mb-0">{{ $featuredJob->title }}</h3>

                                                <p>{{ Str::words(strip_tags($featuredJob->description), 5) }}</p>

                                                <div class="bg-light p-3 border">
                                                    <p class="mb-0">
                                                        <span class="fw-bolder"><i class="fa fa-map-marker"></i></span>
                                                        <span class="ps-1">{{ $featuredJob->location }}</span>
                                                    </p>
                                                    <p class="mb-0">
                                                        <span class="fw-bolder"><i class="fa fa-clock-o"></i></span>
                                                        @if (!is_null($featuredJob->jobType))
                                                            <span class="ps-1">{{ $featuredJob->jobType->name }}</span>
                                                        @else
                                                            <span class="ps-1">N/A</span>
                                                        @endif

                                                        @if (!is_null($featuredJob->salary))
                                                            <p class="mb-0">
                                                                <span class="fw-bolder"><i class="fa fa-usd"></i></span>
                                                                <span class="ps-1">{{ $featuredJob->salary }}</span>
                                                            </p>
                                                        @endif
                                                </div>

                                                <div class="d-grid mt-3">
                                                    <a href="{{ route('jobDetail', $featuredJob->id) }}"
                                                        class="btn btn-primary btn-lg">Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-3 bg-2 py-5">
        <div class="container">
            <h2>Latest Jobs</h2>
            <div class="row pt-5">
                <div class="job_listing_area">
                    <div class="job_lists">
                        <div class="row">
                            @if ($latestJobs->isNotEmpty())
                                @foreach ($latestJobs as $latestJob)
                                    <div class="col-md-4">
                                        <div class="card border-0 p-3 shadow mb-4">
                                            <div class="card-body">
                                                <h3 class="border-0 fs-5 pb-2 mb-0">{{ $latestJob->title }}</h3>

                                                <p>{{ Str::words(strip_tags($latestJob->description), 5) }}</p>

                                                <div class="bg-light p-3 border">
                                                    <p class="mb-0">
                                                        <span class="fw-bolder"><i class="fa fa-map-marker"></i></span>
                                                        <span class="ps-1">{{ $latestJob->location }}</span>
                                                    </p>
                                                    {{-- <p class="mb-0">
                                                <span class="fw-bolder"><i class="fa fa-clock-o"></i></span>
                                                <span class="ps-1">{{ $latestJob->jobType->name }}</span>
                                            </p> --}}
                                                    @if (!is_null($latestJob->salary))
                                                        <p class="mb-0">
                                                            <span class="fw-bolder" style="color: green;">&#128182;</span>
                                                            <span class="ps-1">{{ $latestJob->salary }}</span>
                                                        </p>
                                                    @endif
                                                </div>

                                                <div class="d-grid mt-3">
                                                    <a href="{{ route('jobDetail', $latestJob->id) }}"
                                                        class="btn btn-primary btn-lg">Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

     <!-- New section for reasons to choose the application -->
     <section class="section-4 bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-4">Why JobJuntion ?</h2>
            <div class="row">
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 text-center">
                        <img src="{{ asset('assets/images/transparent_pricing.png') }}" class="card-img-top" alt="Transparent Pricing">
                        <div class="card-body">
                            <h5 class="card-title">Open Pricing</h5>
                            <p class="card-text">See the prices and get prior estimates.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 text-center">
                        <img src="{{ asset('assets/images/reward.png') }}" class="card-img-top" alt="reward">
                        <div class="card-body">
                            <h5 class="card-title">Reward System</h5>
                            <p class="card-text">Reward points to dedicated workers for better promotion.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 text-center">
                        <img src="{{ asset('assets/images/online-payment.png') }}" class="card-img-top" alt="online-payment">
                        <div class="card-body">
                            <h5 class="card-title">Online Payment</h5>
                            <p class="card-text">Opportunity for online payment if preferred. </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 text-center">
                        <img src="{{ asset('assets/images/categories.png') }}" class="card-img-top" alt="categories">
                        <div class="card-body">
                            <h5 class="card-title">Variety of categories</h5>
                            <p class="card-text">Wide range of categories to choose from.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
       $(document).ready(function() {
    // Initialize the Bootstrap carousel
    $('#carouselExampleControls').carousel();
});
    </script>
@endsection

<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/6602f3591ec1082f04db9168/1hptod5uv';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
    <!--End of Tawk.to Script-->


