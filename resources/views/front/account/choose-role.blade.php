@extends('front.layouts.app')

@section('main')
<section class="section-5">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow border-0 p-5">
                    <h1 class="h3 mb-4 text-center">Join as an Employer or Freelancer</h1>
                    <form action="{{ route('account.authenticate') }}" method="post">
                        @csrf
                        <input type="hidden" name="selected_role" value="{{ session('selected_role') }}">
                        <!-- Add a hidden input field to store the selected role -->
                        <div class="row justify-content-center align-items-center mb-4">
                            <div class="col-md-6 text-center">
                                <button type="submit" class="btn btn-primary btn-lg" name="role" value="employer">
                                    <i class="fas fa-user-tie fa-3x mb-3"></i><br>
                                    I’m an Employer, Hiring for my work.
                                </button>
                            </div>
                            <div class="col-md-6 text-center">
                                <button type="submit" class="btn btn-primary btn-lg" name="role" value="freelancer">
                                    <i class="fas fa-user fa-3x mb-3"></i><br>
                                    I’m a Freelancer, Looking for Work.
                                </button>
                            </div>
                        </div>
                    </form>
                    {{-- <div class="mt-3 text-center">
                        <p>Already have an account? <a href="{{ route('account.login') }}">Log In</a></p>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
