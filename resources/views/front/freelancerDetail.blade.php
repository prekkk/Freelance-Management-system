@extends('front.layouts.app')

@section('main')
    <section class="section-4 bg-2">
        <div class="container pt-5">
            <div class="row">
                <div class="col">
                    <!-- Breadcrumb navigation -->
                </div>
            </div>
        </div>
        <div class="container freelancer_details_area">
            <div class="row pb-5">
                <div class="col-md-8">
                    <!-- Display error or success messages -->
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="card shadow border-0">
                        <div class="freelancer_details_header">
                            <!-- Freelancer details -->
                            <div class="single_freelancer white-bg d-flex justify-content-between">
                                <div class="freelancer_left d-flex align-items-center">
                                    <img src="{{ asset($freelancer->profile_picture) }}" alt="Freelancer Image"
                                        class="img-fluid mb-3">
                                    <div class="freelancer_content">
                                        <h4>{{ $freelancer->name }}</h4>
                                        <p>{{ $freelancer->designation }}</p>
                                        <p>{{ $freelancer->location }}</p>
                                        <!-- Other freelancer details -->
                                    </div>
                                </div>
                                <div class="freelancer_right">
                                    <!-- Button for saving freelancer -->
                                    <form action="{{ route('saveFreelancer') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="freelancer_id" value="{{ $freelancer->id }}">
                                        <button type="submit" class="btn btn-primary">Save Freelancer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="descript_wrap white-bg">
                            <!-- Display short description, availability status, etc. -->
                            <div class="single_wrap">
                                <h4>Short Description</h4>
                                <p>{{ $freelancer->short_description }}</p>
                            </div>
                            <!-- Display feedback from other users -->
                            <div class="single_wrap">
                                <h4>Feedback</h4>
                                <div class="feedback_list">
                                    {{-- @if ($freelancer->feedback()->count() > 0)
                                    <ul>
                                        @foreach ($freelancer->feedback as $feedback)
                                            <li>{{ $feedback->message }}</li>
                                            <!-- Display other feedback details -->
                                        @endforeach
                                    </ul>
                                @else
                                    <p>No feedback available</p>
                                @endif --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <button id="payment-button" class="btn btn-primary">Pay with Khalti</button>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
    <script>
        var config = {
            // Replace the publicKey with yours
            "publicKey": "test_public_key_611ddfe3f66b45d4bd94016a182e657c",
            "productIdentity": "1234567890",
            "productName": "Neha Budha",
            "productUrl": "http://127.0.0.1:8000/freelancer/detail/{{ $freelancer->id }}",
            "paymentPreference": [
                "KHALTI",
                "EBANKING",
                "MOBILE_BANKING",
                "CONNECT_IPS",
                "SCT",
            ],
            "eventHandler": {
                onSuccess(payload) {
                    // Hit merchant API for initiating verification
                    console.log(payload);
                    if (payload.idx) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        $.ajax({
                            url: '{{ route('ajax.khalti.verify_job') }}',
                            type: 'post',
                            data: payload,
                            _token: csrfToken,
                            success: function(response) {

                            }
                        });

                    }
                },
                onError(error) {
                    console.log(error);
                },
                onClose() {
                    console.log('Widget is closing');
                }
            }
        };

        var checkout = new KhaltiCheckout(config);
        var btn = document.getElementById("payment-button");
        btn.onclick = function() {
            // Minimum transaction amount must be 10, i.e 1000 in paisa.
            checkout.show({
                amount: 1000
            });
        }
    </script>
@endsection
