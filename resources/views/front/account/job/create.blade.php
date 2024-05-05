@extends('front.layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route("home") }}">Home</a></li>
                        <li class="breadcrumb-item active">Post Job</li>
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

                <form action="{{ route('account.saveJob') }}" method="post" id="createJobForm">
                    @csrf
                    <div class="card border-0 shadow mb-4 ">
                        <div class="card-body card-form p-4">
                            <h3 class="fs-4 mb-1">Job Details</h3>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="title" class="mb-2">Title<span class="req">*</span></label>
                                    <input type="text" id="title" name="title" class="form-control" placeholder="Job Title">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="category" class="mb-2">Category<span class="req">*</span></label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Select a Category</option>
                                        @if ($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="jobType" class="mb-2">Job Type<span class="req">*</span></label>
                                    <select name="jobType" id="jobType" class="form-control">
                                        <option value="">Select Job Type</option>
                                        @if ($jobTypes->isNotEmpty())
                                            @foreach ($jobTypes as $jobType)
                                                <option value="{{ $jobType->id }}">{{ $jobType->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="vacancy" class="mb-2">Vacancy<span class="req">*</span></label>
                                    <input type="number" id="vacancy" name="vacancy" class="form-control" min="1" placeholder="Vacancy">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="salary" class="mb-2">Salary</label>
                                    <input type="text" id="salary" name="salary" class="form-control" placeholder="Salary">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="location" class="mb-2">Location<span class="req">*</span></label>
                                    <input type="text" id="location" name="location" class="form-control" placeholder="Location">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="mb-2">Description<span class="req">*</span></label>
                                <textarea class="form-control" id="description" name="description" rows="5" placeholder="Description"></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="benefits" class="mb-2">Benefits</label>
                                <textarea class="form-control" id="benefits" name="benefits" rows="5" placeholder="Benefits"></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="responsibility" class="mb-2">Responsibility</label>
                                <textarea class="form-control" id="responsibility" name="responsibility" rows="5" placeholder="Responsibility"></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="qualifications" class="mb-2">Qualifications</label>
                                <textarea class="form-control" id="qualifications" name="qualifications" rows="5" placeholder="Qualifications"></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="keywords" class="mb-2">Keywords</label>
                                <input type="text" id="keywords" name="keywords" class="form-control" placeholder="Keywords">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="image" class="mb-2">Job Image</label>
                                    <input type="file" id="image" name="image" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-4">
                            <button type="submit" class="btn btn-primary">Save Job</button>
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
$("#createJobForm").submit(function(e){
    e.preventDefault();

    var formData = new FormData(this); // Create FormData object from the form

    // Manually append file inputs to FormData
    var imageFile = $('#image')[0].files[0];
    formData.append('image', imageFile);

    $.ajax({
        url: '{{ route("account.saveJob") }}',
        type: 'POST',
        dataType: 'json',
        processData: false, 
        contentType: false,
        data: formData,
        success: function(response) {
            // $("button[type='submit']").prop('disabled',false);

            if(response.status == true) {

                $("#title, #category, #jobType, #vacancy, #location, #description").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('');

                window.location.href="{{ route('account.myJobs') }}";

            } else {
                var errors = response.errors;

                if (errors.title) {
                    $("#title").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors.title);
                } else {
                    $("#title").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html('');
                }

                if (errors.category) {
                    $("#category").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors.category);
                } else {
                    $("#category").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html('');
                }

                if (errors.jobType) {
                    $("#jobType").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors.jobType);
                } else {
                    $("#jobType").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html('');
                }

                if (errors.vacancy) {
                    $("#vacancy").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors.vacancy);
                } else {
                    $("#vacancy").removeClass('is-invalid')
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

                if (errors.description) {
                    $("#description").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors.description);
                } else {
                    $("#description").removeClass('is-invalid')
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
