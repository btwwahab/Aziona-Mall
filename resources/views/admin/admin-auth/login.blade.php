@extends('admin.admin-auth.layout')
@section('title', 'Admin-Sing In')

@section('content')
    <div class="d-flex flex-column h-100 ">
        <div class="d-flex flex-column flex-grow-1">
            <div class="row h-100">
                <div class="col-xxl-7">
                    <div class="row justify-content-center h-100">
                        <div class="col-lg-6 py-lg-5">
                            <div class="d-flex flex-column h-100 justify-content-center">
                                <div class="auth-logo mb-4">
                                    <a href="index.html" class="logo-dark">
                                        <img src="{{ asset('admin-assets/images/logo-dark.png') }}" height="24"
                                            alt="logo dark">
                                    </a>

                                    <a href="index.html" class="logo-light">
                                        <img src="{{ asset('admin-assets/images/logo-dark.png') }}" height="24"
                                            alt="logo light">
                                    </a>
                                </div>

                                <h2 class="fw-bold fs-24">Sign In</h2>

                                <p class="text-muted mt-1 mb-4">Enter your email address and password to access admin panel.
                                </p>

                                <div class="">

                                    <form id="login-form" action="{{ route('admin.login') }}" class="authentication-form" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            {{-- <label  class="form-label"
                                                for="example-email">Email</label>
                                            <input type="email" name="email" name="example-email"
                                                class="form-control bg-" placeholder="Enter your email">

                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror --}}
                                            <div class="form-group">
                                                <input type="email" id="login-email" name="email" placeholder="Your Email"
                                                    class="form__input form-control" required />
                                                @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            {{-- <a href="auth-password.html"
                                                class="float-end text-muted text-unline-dashed ms-1">Reset password</a>
                                            <label class="form-label" for="example-password">Password</label>
                                            <input type="password" name="password" class="form-control"
                                                placeholder="Enter your password">

                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror --}}
                                            <div class="form-group">
                                                <input type="password" id="login-password" name="password" placeholder="Your Password"
                                                    class="form-control form__input" autocomplete="off" />
                                                @error('password')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="remember"
                                                    name="remember">
                                                <label class="form-check-label" for="remember">Remember me</label>
                                            </div>
                                        </div>

                                        <div class="mb-1 text-center d-grid">
                                            <button type="submit" class="btn btn-soft-primary login-button" type="submit">Sign
                                                In</button>
                                        </div>
                                    </form>

                                    {{-- <p class="mt-3 fw-semibold no-span">OR sign with</p>

                                    <div class="d-grid gap-2">
                                        <a href="javascript:void(0);" class="btn btn-soft-dark"><i
                                                class="bx bxl-google fs-20 me-1"></i> Sign in with Google</a>
                                        <a href="javascript:void(0);" class="btn btn-soft-primary"><i
                                                class="bx bxl-facebook fs-20 me-1"></i> Sign in with Facebook</a>
                                    </div> --}}
                                </div>

                                {{-- <p class="text-danger text-center">Don't have an account? <a href="auth-signup.html"
                                        class="text-dark fw-bold ms-1">Sign Up</a></p> --}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-5 d-none d-xxl-flex">
                    <div class="card h-100 mb-0 ">
                        <div class="d-flex flex-column h-100">
                            <img src="{{ asset('admin-assets/images/login-sidebar.jpg') }}" alt=""
                                class="w-100 h-100">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $("#login-form").validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 8
                }
            },
            messages: {
                email: {
                    required: "Please enter your email address",
                    email: "Please enter a valid email address"
                },
                password: {
                    required: "Please enter your password",
                    minlength: "Password must be at least 8 characters long"
                }
            },
            errorPlacement: function(error, element) {
                error.addClass("invalid-feedback");
                element.closest(".form-group").append(error);
            },
            highlight: function(element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function(element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
            }
        });
    });
</script>
@endpush