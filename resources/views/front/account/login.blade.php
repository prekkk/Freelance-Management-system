@extends('front.layouts.app')

@section('main')
<section class="section-5">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                @if(Session::has('success'))
                <div class="alert alert-success">
                    <p class="mb-0 pb-0">{{ Session::get('success') }}</p>
                </div>
                @endif

                @if(Session::has('error'))
                <div class="alert alert-danger">
                    <p class="mb-0 pb-0">{{ Session::get('error') }}</p>
                </div>
                @endif
                
                <div class="card shadow border-0 p-5">
                    <h1 class="h3 mb-4 text-center">Login</h1>
                    <form action="{{ route('account.authenticate') }}" method="post">
                        @csrf
                        {{-- <input type="hidden" name="selected_role" value="{{ session('selected_role') }}"> --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">Email*</label>
                            <input type="text" value="{{ old('email') }}" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="example@example.com">
                            @error('email')
                            <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div> 
                        <div class="mb-3">
                            <label for="password" class="form-label">Password*</label>
                            <input type="password" name="password" id="password" class="form-control  @error('password') is-invalid @enderror" placeholder="Enter Password">
                            @error('password')
                            <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div> 
                        <div class="d-grid">
                            <button class="btn btn-primary btn-lg" type="submit">Login</button>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <a href="forgot-password.html">Forgot Password?</a>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <p>Do not have an account? <a href="{{ route('account.registration') }}">Register</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
