<!-- resources/views/admin/freelancers/index.blade.php -->

@extends('front.layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Home</a></li>
                        <li class="breadcrumb-item active">Freelancers</li>
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
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fs-4 mb-1">Freelancers</h3>
                            </div>
                            <div style="margin-top: -10px;">
                                {{-- <a href="{{ route("account.createJob") }}" class="btn btn-primary">Post a Job</a> --}}
                            </div>
                            
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Designation</th>
                                        <th scope="col">Location</th>
                                        <th scope="col">Reward Points</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($freelancers->isNotEmpty())
                                        @foreach ($freelancers as $freelancer)
                                            <tr>
                                                <td>{{ $freelancer->id }}</td>
                                                <td>{{ $freelancer->name }}</td>
                                                <td>{{ $freelancer->designation }}</td>
                                                <td>{{ $freelancer->location }}</td>
                                                <td>{{ $freelancer->rewards }}</td>
                                                <td>
                                                    <div class="action-dots">
                                                        <a href="{{ route("admin.freelancers.edit", $freelancer->id) }}" class="btn btn-sm btn-info">Edit</a>
                                                        <button class="btn btn-sm btn-danger" onclick="deleteFreelancer({{ $freelancer->id }})">Delete</button>

                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5">No freelancers found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div>
                            {{ $freelancers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
@section('customJs')
<script type="text/javascript">
    function deleteFreelancer(id){
        console.log('Delete button clicked for freelancer ID:', id); // Log the ID for debugging
        if(confirm("Are you sure you want to delete?")){
            $.ajax({
                url: '{{ route("admin.freelancers.destroy", ":id") }}'.replace(':id', id), // Replace ":id" with the actual ID
                type: 'delete',
                dataType: 'json',
                success: function(response) {
                    window.location.href = "{{ route('admin.freelancers.freelancerlist') }}"; // Redirect to freelancers index
                }
            });
        }
    }
</script>

@endsection
