@extends('front.layouts.app')

@section('main')
<section class="section-3 py-5 bg-2">
    <div class="container">     
        <div class="row">
            <div class="col-6 col-md-10">
                <h2>Find Top Freelancers</h2>  
            </div>
            <div class="col-6 col-md-2">
                <div class="align-end">
                    <select name="sort" id="sort" class="form-control">
                        <option value="1" {{ (Request::get('sort') == '1') ? 'selected' : '' }}>Latest</option>
                        <option value="0" {{ (Request::get('sort') == '0') ? 'selected' : '' }}>Oldest</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row pt-5">
            <div class="col-md-4 col-lg-3 sidebar mb-4">
                <form action="{{ route('freelancers') }}" name="searchForm" id="searchForm">
                    <div class="card border-0 shadow p-4">
                        {{-- <div class="mb-4">
                            <h2>Name</h2>
                            <input value="{{ Request::get('name') }}" type="text" name="name" id="name" placeholder="Name" class="form-control">
                        </div> --}}

                        <div class="mb-4">
                            <h2>Location</h2>
                            <input value="{{ Request::get('location') }}" type="text" name="location" id="location" placeholder="Location" class="form-control">
                        </div>

                        <div class="mb-4">
                            <h2>Designation</h2>
                            <input value="{{ Request::get('designation') }}" type="text" name="designation" id="designation" placeholder="Designation" class="form-control">
                        </div>

                        {{-- You can add more search filters as needed --}}

                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="{{ route("freelancers") }}" class="btn btn-secondary mt-3">Reset</a>
                    </div>
                </form>
            </div>
            <div class="col-md-8 col-lg-9">
                <div class="freelancer_listing_area">                    
                    <div class="freelancer_lists">
                        <div class="row">
                            @if ($freelancers->isNotEmpty())
                                @foreach ($freelancers as $freelancer)
                                <div class="col-md-4">
                                    <div class="card border-0 p-3 shadow mb-4">
                                        <div class="card-body">
                                            <h3 class="border-0 fs-5 pb-2 mb-0">{{ $freelancer->name }}</h3>
                                            <p>{{ $freelancer->designation }}</p>
                                            <p>{{ $freelancer->email }}</p>
                                            <p>{{ $freelancer->location }}</p>
                                            {{-- Display image
                                            <img src="{{ $freelancer->image_url }}" alt="{{ $freelancer->name }}" class="img-fluid"> --}}
                                            {{-- You can add more freelancer details as needed --}}
                                            <div class="d-grid mt-3">
                                                <div class="row">
                                                    <div class="col">
                                                        {{-- Add button to view freelancer details --}}
                                                        <a href="{{ route('freelancer.show', ['id' => $freelancer->id]) }}" class="btn btn-primary">View Details</a>
                                                    </div>
                                                    <div class="col">
                                                        {{-- Add button to add feedback --}}
                                                        <a href="{{ route('feedback.create', ['freelancer_id' => $freelancer->id]) }}" class="btn btn-primary">Add Feedback</a>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                            <div class="col-md-12">Freelancers not found</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script>
    $("#searchForm").submit(function(e){
        e.preventDefault();

        var url = '{{ route("freelancers") }}';

        var location = $("#location").val();
        var designation = $("#designation").val();
        var sort = $("#sort").val();

        // Function to get the checked values of checkboxes for filtering
        function getCheckedFilters(checkboxName) {
            var checkedValues = [];
            $("input:checkbox[name='" + checkboxName + "']:checked").each(function() {
                checkedValues.push($(this).val());
            });
            return checkedValues;
        }

        // Add filters to URL parameters
        url += '?' + $.param({
            location: location,
            designation: designation,
            sort: sort,
            // If you have checkboxes for filtering, add them here
            // job_type: getCheckedFilters('job_type')
        });

        window.location.href = url;
    });

    $("#sort").change(function(){
        $("#searchForm").submit();
    });
</script>
@endsection
