@extends('front.layouts.app')

@section('main')
    <section class="section-5 bg-2">
        <!-- Other HTML content -->
        <div class="col-lg-9">
            <!-- Other HTML content -->

            <!-- Form to save a freelancer -->
            <form action="{{ route('account.saveFreelancer') }}" method="POST">
                @csrf
                <!-- Add other form fields for freelancer details as needed -->
                <button type="submit" class="btn btn-primary">Save Freelancer</button>
            </form>

            <div class="table-responsive">
                <table class="table ">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Designation</th>
                            <th scope="col">Location</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-0">
                        @if ($savedFreelancers->isNotEmpty())
                            @foreach ($savedFreelancers as $savedFreelancer)
                                <tr class="active">
                                    <td>{{ $savedFreelancer->freelancer->name }}</td>
                                    <td>{{ $savedFreelancer->freelancer->designation }}</td>
                                    <td>{{ $savedFreelancer->freelancer->location }}</td>
                                    <td>
                                        <button class="btn btn-danger"
                                            onclick="removeSavedFreelancer({{ $savedFreelancer->id }})">Remove</button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4">No saved freelancers found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Other HTML content -->
    </section>
@endsection

@section('customJs')
    <script type="text/javascript">
        function removeSavedFreelancer(id) {
            if (confirm("Are you sure you want to remove this saved freelancer?")) {
                $.ajax({
                    url: '{{ route('account.removeSavedFreelancer') }}',
                    type: 'post', 
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Reload the page or update the list of saved freelancers
                        window.location.reload(); // Reload the page
                    }
                });

            }
        }
    </script>
@endsection
