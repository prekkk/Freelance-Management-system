@extends('front.layouts.app')

@section('main')
    <section class="section-3 py-5 bg-2">
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    @if(Session::has('success'))
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <h2>Leave Feedback</h2>
                    <form action="{{ route('feedback.store') }}" method="POST" onsubmit="submitFeedback(event)">
                        @csrf
                        <!-- Add a hidden input field for freelancer_id -->
                        <input type="hidden" id="freelancer_id" name="freelancer_id" value="{{ $freelancer->id }}">
                        <!-- Other form fields for feedback -->
                        <div class="mb-3">
                            <label for="message" class="form-label">Your Message</label>
                            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating</label>
                            <select class="form-control" id="rating" name="rating" required>
                                <option value="">Select Rating</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="feedback_type" class="form-label">Feedback Type</label>
                            <select class="form-control" id="feedback_type" name="feedback_type" required>
                                <option value="">Select Feedback Type</option>
                                <option value="Positive">Positive</option>
                                <option value="Neutral">Neutral</option>
                                <option value="Negative">Negative</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Feedback</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
