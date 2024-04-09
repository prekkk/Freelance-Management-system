@extends('front.layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route("admin.freelancers.freelancerlist") }}">Freelancers</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include('admin.sidebar')
            </div>
            <div class="col-lg-9">
                @include('front.message')
                <div class="card border-0 shadow mb-4">
                    <div class="card-body card-form">
                        <form action="{{ route('admin.freelancers.update', $freelancer->id) }}" method="post" id="freelancerForm" name="freelancerForm">
                            @csrf
                            @method('PUT')
                            <div class="card-body  p-4">
                                <h3 class="fs-4 mb-1">Freelancer / Edit</h3>
                                <div class="mb-4">
                                    <label for="name" class="mb-2">Name*</label>
                                    <input type="text" name="name" id="name" placeholder="Enter Name" class="form-control" value="{{ $freelancer->name }}">
                                    <p></p>
                                </div>
                                <div class="mb-4">
                                    <label for="designation" class="mb-2">Designation</label>
                                    <input type="text" name="designation" id="designation"  placeholder="Designation" class="form-control" value="{{ $freelancer->designation }}">
                                </div>
                                <div class="mb-4">
                                    <label for="location" class="mb-2">Location</label>
                                    <input type="text" name="location" id="location"  placeholder="Location" class="form-control" value="{{ $freelancer->location }}">
                                </div>
                                <div class="mb-4">
                                    <label for="reward_points" class="mb-2">Reward Points</label>
                                    <input type="number" name="reward_points" id="reward_points" placeholder="Reward Points" class="form-control" value="{{ $freelancer->rewards }}">
                                </div>                                
                            </div>
                            <div class="card-footer  p-4">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>                          
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script type="text/javascript">
$("#freelancerForm").submit(function(e){
    e.preventDefault();

    $.ajax({
        url: $(this).attr('action'),
        type: 'PUT',
        dataType: 'json',
        data: $(this).serializeArray(),
        success: function(response) {

            if(response.status == true) {

                $("#name").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')

                $("#designation").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')

                $("#location").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')

                window.location.href="{{ route('admin.freelancers.freelancerlist') }}";

            } else {
                var errors = response.errors;

                if (errors.name) {
                    $("#name").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html(errors.name)
                } else {
                    $("#name").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                }

                if (errors.designation) {
                    $("#designation").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html(errors.designation)
                } else {
                    $("#designation").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                }

                if (errors.location) {
                    $("#location").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html(errors.location)
                } else {
                    $("#location").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                }
            }

        }
    });
});
</script>
@endsection
