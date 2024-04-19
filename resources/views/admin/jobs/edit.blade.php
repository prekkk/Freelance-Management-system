@extends('front.layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route("admin.jobs") }}">Jobs</a></li>
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

                <form action="{{ route('admin.jobs.update', $job->id) }}" method="post" id="editJobForm" name="editJobForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card border-0 shadow mb-4">
                        <div class="card-body card-form p-4">
                            <h3 class="fs-4 mb-4">Edit Job Details</h3>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="title" class="form-label">Title<span class="req">*</span></label>
                                    <input value="{{ $job->title }}" type="text" id="title" name="title" class="form-control" placeholder="Job Title">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="category" class="form-label">Category<span class="req">*</span></label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Select a Category</option>
                                        @foreach ($categories as $category)
                                            <option {{ ($job->category_id == $category->id) ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="jobType" class="form-label">Job Type<span class="req">*</span></label>
                                    <select name="jobType" id="jobType" class="form-control">
                                        <option value="">Select Job Type</option>
                                        @foreach ($jobTypes as $jobType)
                                            <option {{ ($job->job_type_id == $jobType->id) ? 'selected' : '' }} value="{{ $jobType->id }}">{{ $jobType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="vacancy" class="form-label">Vacancy<span class="req">*</span></label>
                                    <input value="{{ $job->vacancy }}" type="number" id="vacancy" name="vacancy" class="form-control" min="1" placeholder="Vacancy">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="salary" class="form-label">Salary</label>
                                    <input value="{{ $job->salary }}" type="text" id="salary" name="salary" class="form-control" placeholder="Salary">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="location" class="form-label">Location<span class="req">*</span></label>
                                    <input value="{{ $job->location }}" type="text" id="location" name="location" class="form-control" placeholder="Location">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-check">
                                        <input {{ ($job->isFeatured == 1) ? 'checked' : '' }} class="form-check-input" type="checkbox" value="1" id="isFeatured" name="isFeatured">
                                        <label class="form-check-label" for="isFeatured">Featured</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-check">
                                        <input {{ ($job->status == 1) ? 'checked' : '' }} class="form-check-input" type="checkbox" value="1" id="status-active" name="status">
                                        <label class="form-check-label" for="status-active">Active</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <label for="description" class="form-label">Description<span class="req">*</span></label>
                                    <textarea class="form-control" id="description" name="description" rows="5" placeholder="Description">{{ $job->description }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <label for="benefits" class="form-label">Benefits</label>
                                    <textarea class="form-control" id="benefits" name="benefits" rows="5" placeholder="Benefits">{{ $job->benefits }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <label for="responsibility" class="form-label">Responsibility</label>
                                    <textarea class="form-control" id="responsibility" name="responsibility" rows="5" placeholder="Responsibility">{{ $job->responsibility }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <label for="qualifications" class="form-label">Qualifications</label>
                                    <textarea class="form-control" id="qualifications" name="qualifications" rows="5" placeholder="Qualifications">{{ $job->qualifications }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="keywords" class="form-label">Keywords</label>
                                    <input type="text" id="keywords" name="keywords" class="form-control" placeholder="Keywords">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="image" class="form-label">Job Image</label>
                                    <input type="file" id="image" name="image" class="form-control">
                                </div>
                            </div>

                        </div>
                        <div class="card-footer p-4">
                            <button type="submit" class="btn btn-primary">Update Job</button>
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
$("#editJobForm").submit(function(e){
    e.preventDefault();
    $("button[type='submit']").prop('disabled',true);

    $.ajax({
        url: '{{ route("admin.jobs.update",$job->id) }}',
        type: 'PUT',
        dataType: 'json',
        data: $("#editJobForm").serializeArray(),
        success: function(response) {
            $("button[type='submit']").prop('disabled',false);

            if(response.status == true) {

                $("#title").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')

                $("#category").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')

                $("#jobType").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')

                $("#vacancy").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')

                $("#location").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')


                $("#description").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')

                // $("#company_name").removeClass('is-invalid')
                //     .siblings('p')
                //     .removeClass('invalid-feedback')
                //     .html('')

                 window.location.href="{{ route('account.myJobs') }}";

            } else {
                var errors = response.errors;

                if (errors.title) {
                    $("#title").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html(errors.title)
                } else {
                    $("#title").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                }

                if (errors.category) {
                    $("#category").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html(errors.category)
                } else {
                    $("#category").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                }

                if (errors.jobType) {
                    $("#jobType").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html(errors.jobType)
                } else {
                    $("#jobType").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('')
                }

                if (errors.vacancy) {
                    $("#vacancy").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html(errors.vacancy)
                } else {
                    $("#vacancy").removeClass('is-invalid')
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

                if (errors.description) {
                    $("#description").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html(errors.description)
                } else {
                    $("#description").removeClass('is-invalid')
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

