<!-- resources/views/front/account/freelancer/edit-freelancer.blade.php -->
@extends('front.layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route("home") }}">Home</a></li>
                        <li class="breadcrumb-item active">Edit Freelancer</li>
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

                <form action="{{ route('account.updateFreelancer', $freelancer->id) }}" method="post" id="editFreelancerForm" enctype="multipart/form-data">
                    @csrf
                    <div class="card border-0 shadow mb-4 ">
                        <div class="card-body card-form p-4">
                            <h3 class="fs-4 mb-1">Edit Freelancer Details</h3>
                            <div class="mb-4">
                                <label for="name" class="mb-2">Name<span class="req">*</span></label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Name" value="{{ $freelancer->name }}">
                            </div>
                            <div class="mb-4">
                                <label for="designation" class="mb-2">Designation<span class="req">*</span></label>
                                <input type="text" id="designation" name="designation" class="form-control" placeholder="Designation" value="{{ $freelancer->designation }}">
                            </div>
                            <div class="mb-4">
                                <label for="email" class="mb-2">Email<span class="req">*</span></label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="Email" value="{{ $freelancer->email }}">
                            </div>
                            <div class="mb-4">
                                <label for="location" class="mb-2">Location<span class="req">*</span></label>
                                <input type="text" id="location" name="location" class="form-control" placeholder="Location" value="{{ $freelancer->location }}">
                            </div>
                            <div class="mb-4">
                                <label for="short_description" class="mb-2">Short Description<span class="req">*</span></label>
                                <textarea class="form-control" id="short_description" name="short_description" rows="5" placeholder="Short Description">{{ $freelancer->short_description }}</textarea>
                            </div>
                            <div class="mb-4">
                                <label for="profile_picture" class="mb-2">Profile Picture</label>
                                <input type="file" id="profile_picture" name="profile_picture" class="form-control">
                            </div>
                        </div>
                        <div class="card-footer p-4">
                            <button type="submit" class="btn btn-primary">Update Freelancer</button>
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
$("#editFreelancerForm").submit(function(e){
    e.preventDefault();

    var formData = new FormData(this); // Create FormData object from the form

    // Manually append file inputs to FormData
    var profilePictureFile = $('#profile_picture')[0].files[0];
    formData.append('profile_picture', profilePictureFile);

    $.ajax({
        url: '{{ route("account.updateFreelancer", $freelancer->id) }}',
        type: 'POST',
        dataType: 'json',
        processData: false, 
        contentType: false,
        data: formData,
        success: function(response) {
            if(response.status == true) {

                $("#name, #designation, #email, #location, #short_description").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('');

                window.location.href="{{ route('account.myFreelancers') }}";

            } else {
                var errors = response.errors;

                if (errors.name) {
                    $("#name").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors.name);
                } else {
                    $("#name").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html('');
                }

                if (errors.designation) {
                    $("#designation").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors.designation);
                } else {
                    $("#designation").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html('');
                }

                if (errors.email) {
                    $("#email").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors.email);
                } else {
                    $("#email").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html('');
                }

                if (errors.location) {
                    $("#location").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors.location);
                } else {
                    $("#location").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html('');
                }

                if (errors.short_description) {
                    $("#short_description").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors.short_description);
                } else {
                    $("#short_description").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html('');
                }

            }

        }
    });
});
</script>
@endsection
