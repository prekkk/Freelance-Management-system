@extends('front.layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route("home") }}">Home</a></li>
                        <li class="breadcrumb-item active">Post Freelancer</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include('front.account.sidebar')
            </div>
            <div class="col-lg-9">
                @include('front.message')

                {{-- <form action="{{ route('account.saveFreelancer') }}" method="post" id="createFreelancerForm" enctype="multipart/form-data"> --}}
                    @csrf
                    <div class="card border-0 shadow mb-4 ">
                        <div class="card-body card-form p-4">
                            <h3 class="fs-4 mb-1">Freelancer Details</h3>
                            <div class="mb-4">
                                <label for="name" class="mb-2">Name<span class="req">*</span></label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Name">
                            </div>
                            <div class="mb-4">
                                <label for="designation" class="mb-2">Designation<span class="req">*</span></label>
                                <input type="text" id="designation" name="designation" class="form-control" placeholder="Designation">
                            </div>
                            <div class="mb-4">
                                <label for="email" class="mb-2">Email<span class="req">*</span></label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="Email">
                            </div>
                            <div class="mb-4">
                                <label for="location" class="mb-2">Location<span class="req">*</span></label>
                                <input type="text" id="location" name="location" class="form-control" placeholder="Location">
                            </div>
                            <div class="mb-4">
                                <label for="mobile" class="mb-2">Mobile<span class="req">*</span></label>
                                <input type="text" id="mobile" name="mobile" class="form-control" placeholder="Mobile">
                            </div>
                            <div class="mb-4">
                                <label for="short_description" class="mb-2">Short Description<span class="req">*</span></label>
                                <textarea class="form-control" id="short_description" name="short_description" rows="5" placeholder="Short Description"></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="profile_picture" class="mb-2">Profile Picture</label>
                                <input type="file" id="profile_picture" name="profile_picture" class="form-control">
                            </div>
                        </div>
                        <div class="card-footer p-4">
                            <button type="submit" class="btn btn-primary">Save Freelancer</button>
                        </div>               
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script type="text/javascript">
$("#createFreelancerForm").submit(function(e){
    e.preventDefault();

    var formData = new FormData(this); // Create FormData object from the form

    $.ajax({
        url: $(this).attr('action'), // Use the form action attribute
        type: 'POST',
        dataType: 'json',
        processData: false, 
        contentType: false,
        data: formData,
        success: function(response) {
            if(response.status == true) {
                $("#name, #designation, #email, #location, #mobile, #short_description").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('');

                // Redirect to the appropriate page if needed
                window.location.href = "{{ route('account.profile') }}";
            } else {
                var errors = response.errors;

                // Loop through errors and display them
                $.each(errors, function(key, value) {
                    $("#" + key).addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(value);
                });
            }
        }
    });
});
</script>
@endsection

