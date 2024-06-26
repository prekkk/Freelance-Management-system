@extends('front.layouts.app')

@section('main')

<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Account Settings</li>
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

                <form action="{{ route('account.updateJob', ['jobId' => $id]) }}" method="post" id="editJobForm" name="editJobForm">
                    @csrf
                    <div class="card border-0 shadow mb-4">
                        <div class="card-body card-form p-4">
                            <h3 class="fs-4 mb-4">Edit Job Details</h3>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="title" class="mb-2">Title<span class="req">*</span></label>
                                    <input value="{{ $job->title }}" type="text" id="title" name="title" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="category" class="mb-2">Category<span class="req">*</span></label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Select a Category</option>
                                        @foreach($categories as $category)
                                            <option {{ ($job->category_id == $category->id) ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="jobType" class="mb-2">Job Type<span class="req">*</span></label>
                                    <select name="jobType" id="jobType" class="form-select">
                                        <option value="">Select Job Type</option>
                                        @if ($jobTypes->isNotEmpty())
                                            @foreach ($jobTypes as $jobType)
                                            <option {{ ($job->job_type_id == $jobType->id) ? 'selected' : '' }} value="{{ $jobType->id }}">{{ $jobType->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="vacancy" class="mb-2">Vacancy<span class="req">*</span></label>
                                    <input value="{{ $job->vacancy }}" type="number" min="1" id="vacancy" name="vacancy" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="salary" class="mb-2">Salary</label>
                                    <input value="{{ $job->salary }}" type="text" id="salary" name="salary" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="location" class="mb-2">Location<span class="req">*</span></label>
                                    <input value="{{ $job->location }}" type="text" id="location" name="location" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="description" class="mb-2">Description<span class="req">*</span></label>
                                    <textarea class="form-control textarea" name="description" id="description" cols="5" rows="5" placeholder="Description">{{ $job->description }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="benefits" class="mb-2">Benefits</label>
                                    <textarea class="form-control textarea" name="benefits" id="benefits" cols="5" rows="5" placeholder="Benefits">{{ $job->benefits }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="responsibilities" class="mb-2">Responsibility</label>
                                    <textarea class="form-control textarea" name="responsibilities" id="responsibilities" cols="5" rows="5" placeholder="Responsibility">{{ $job->responsibilities }}</textarea>
                                </div>
                            </div>                            

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="qualifications" class="mb-2">Qualifications</label>
                                    <textarea class="form-control textarea" name="qualifications" id="qualifications" cols="5" rows="5" placeholder="Qualifications">{{ $job->qualifications }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="keywords" class="mb-2">Keywords</label>
                                    <input value="{{ $job->keywords }}" type="text" id="keywords" name="keywords" class="form-control">
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
            url: '{{ route("account.updateJob", $job->id) }}',
            type: 'post',
            dataType: 'json',
            data: $("#editJobForm").serializeArray(),
            success: function(response){
                $("button[type='submit']").prop('disabled',false);
                if(response.status == true){
                    $("#title").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html('')
                
                    $("#category").removeClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html('')

                    $("#jobType").removeClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html('')

                    $("#vacancy").removeClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html('')

                    $("#location").removeClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html('')

                    $("#description").removeClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html('')
                }
               window.location.href="{{ route('account.myJobs') }}";
        
                }
else{
    var errors = response.errors;
    if(errors.title){
                    $("#title").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html('errors.title')
                } else{
                    $("#title").removeClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html('')
                }
                if(errors.category){
                    $("#category").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html('errors.category')
                } else{
                    $("#category").removeClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html('')
                }
                if(errors.jobType){
                    $("#jobType").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html('errors.jobType')
                } else{
                    $("#jobType").removeClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html('')
                }
                if(errors.vacancy){
                    $("#vacancy").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html('errors.vacancy')
                } else{
                    $("#vacancy").removeClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html('')
                }
                if(errors.location){
                    $("#location").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html('errors.location')
                } else{
                    $("#location").removeClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html('')
                }
                if(errors.description){
                    $("#description").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html('errors.description')
                } else{
                    $("#description").removeClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html('')
                }
}
            });
        });
</script>
@endsection