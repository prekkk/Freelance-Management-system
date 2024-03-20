<!-- resources/views/feedback_form.blade.php -->

@extends('front.layouts.app')

@section('main')
    <section class="section-3 py-5 bg-2">
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <h2>Leave Feedback</h2>
                    <form action="{{ route('feedback.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Your Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Your Message</label>
                            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Feedback</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
