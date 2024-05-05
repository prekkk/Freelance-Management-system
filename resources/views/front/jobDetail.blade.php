@extends('front.layouts.app')

@section('main')
    <section class="section-4 bg-2">
        <div class="container pt-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class="rounded-3 p-3">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('jobs') }}"><i class="fa fa-arrow-left"
                                        aria-hidden="true"></i>&nbsp;Back to Jobs</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container job_details_area">
            <div class="row pb-5">
                <div class="col-md-8">
                    @include('front.message')
                    <div class="card shadow border-0">
                        <div class="job_details_header">
                            <div class="single_jobs white-bg d-flex justify-content-between">
                                <div class="jobs_left d-flex align-items-center">
                                    <img src="{{ asset($job->image) }}" alt="Job Image" class="img-fluid mb-3"
                                        style='max-width:200px; height:200px;'>
                                    <div class="jobs_conetent">
                                        <a href="#">
                                            <h4>{{ $job->title }}</h4>
                                        </a>
                                        <div class="links_locat d-flex align-items-center">
                                            <div class="location">
                                                <p><i class="fa fa-map-marker"></i>&nbsp;{{ $job->location }}</p>
                                            </div>
                                            <div class="location">
                                                <p><i
                                                        class="fa fa-clock-o"></i>&nbsp;{{ optional($job->jobType)->name ?: 'N/A' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="jobs_right">
                                    <div class="apply_now {{ $count == 1 ? 'saved-job' : '' }}">
                                        <a class="heart_mark " href="javascript:void(0);"
                                            onclick="saveJob({{ $job->id }})"><i class="fa fa-heart-o"
                                                aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="descript_wrap white-bg">
                            <div class="single_wrap">
                                <h4>Job description</h4>
                                {!! nl2br($job->description) !!}
                            </div>
                            @if (!empty($job->responsibilities))
                                <div class="single_wrap">
                                    <h4>Responsibility</h4>
                                    {!! nl2br($job->responsibilities) !!}
                                </div>
                            @endif

                            @if (!empty($job->qualifications))
                                <div class="single_wrap">
                                    <h4>Qualifications</h4>
                                    {!! nl2br($job->qualifications) !!}
                                </div>
                            @endif
                            @if (!empty($job->benefits))
                                <div class="single_wrap">
                                    <h4>Benefits</h4>
                                    {!! nl2br($job->benefits) !!}
                                </div>
                            @endif

                            @if (!empty($job->keywords))
                                <div class="single_wrap">
                                    <h4>Keywords</h4>
                                    {!! nl2br($job->keywords) !!}
                                </div>
                            @endif

                            <div class="border-bottom"></div>
                            <div class="pt-3 text-end">
                                @if (Auth::check())
                                    <a href="" onclick="saveJob({{ $job->id }});"
                                        class="btn btn-secondary">Save</a>
                                @else
                                    <a href="{{ route('account.login') }}" class="btn btn-secondary disabled">Login to
                                        Save</a>
                                @endif

                                @if (Auth::check())
                                    <a href="" onclick="applyJob({{ $job->id }})"
                                        class="btn btn-primary">Apply</a>
                                @else
                                    <a href="{{ route('account.login') }}" class="btn btn-primary disabled">Login to
                                        Apply</a>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if (Auth::user() && Auth::user()->id == $job->user_id)
                        <div class="card shadow border-0 mt-4">
                            <div class="job_details_header">
                                <div class="single_jobs white-bg d-flex justify-content-between">
                                    <div class="jobs_left d-flex align-items-center">
                                        <div class="jobs_conetent">
                                            <h4>Applicants</h4>
                                        </div>
                                    </div>
                                    <div class="jobs_right"></div>
                                </div>
                            </div>
                            <div class="descript_wrap white-bg">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Applied Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($applications->isNotEmpty())
                                            @foreach ($applications as $application)
                                                <tr>
                                                    <td>{{ $application->user->name }}</td>
                                                    <td>{{ $application->user->email }}</td>
                                                    <td>{{ $application->user->mobile }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($application->applied_date)->format('d M, Y') }}
                                                    </td>
                                                    <td>
                                                        <div class="action-dots float-end">
                                                            <button class="btn" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li>
                                                                    <form
                                                                        action="{{ route('account.acceptApplication', $application->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        <button type="submit" class="dropdown-item"><i
                                                                                class="fa fa-check"
                                                                                aria-hidden="true"></i>&nbsp;Accept</button>
                                                                    </form>
                                                                </li>
                                                                <li>
                                                                    <form
                                                                        action="{{ route('account.rejectApplication', $application->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        <button type="submit" class="dropdown-item"><i
                                                                                class="fa fa-times"
                                                                                aria-hidden="true"></i>&nbsp;Reject</button>
                                                                    </form>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5">Applicants not found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="card shadow border-0">
                        <div class="job_sumary">
                            <div class="summery_header pb-1 pt-4">
                                <h3>Job Summary</h3>
                            </div>
                            <div class="job_content pt-3">
                                <ul>
                                    <li>Published on:
                                        <span>{{ \Carbon\Carbon::parse($job->created_at)->format('d M, Y') }}</span></li>
                                    <li>Vacancy: <span>{{ $job->vacancy }}</span></li>
                                    @if (!empty($job->salary))
                                        <li>Salary: <span>{{ $job->salary }}</span></li>
                                    @endif
                                    <li>Location: <span>{{ $job->location }}</span></li>
                                    <li>Job Nature: <span>{{ optional($job->jobType)->name ?: 'N/A' }}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('customJs')
    <script type="text/javascript">
        function applyJob(id) {
            if (confirm("Are you sure you want to apply on this job?")) {
                $.ajax({
                    url: '{{ route('applyJob') }}',
                    type: 'post',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}' // Add the CSRF token here
                    },
                    dataType: 'json',
                    success: function(response) {
                        window.location.href = "{{ url()->current() }}";
                    }
                });
            }
        }

        function saveJob(id) {
            $.ajax({
                url: '{{ route('account.saveJob') }}',
                type: 'post',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}' // Add the CSRF token here
                },
                dataType: 'json',
                success: function(response) {
                    window.location.href = "{{ url()->current() }}";
                }
            });
        }
    </script>
@endsection
