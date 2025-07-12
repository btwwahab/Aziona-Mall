@extends('frontend.layout.master')

@section('title', 'Login/Register')

@section('content')
    <!--=============== MAIN ===============-->
    <main class="main">
        <!--=============== BREADCRUMB ===============-->
        <section class="breadcrumb">
            <ul class="breadcrumb__list flex container">
                <li><a href="index.html" class="breadcrumb__link">Home</a></li>
                <li><span class="breadcrumb__link">></span></li>
                <li><span class="breadcrumb__link">Login / Register</span></li>
            </ul>
        </section>

        <!--=============== LOGIN-REGISTER ===============-->
        <section class="login-register section--lg">
            <div class="login-register__container container grid">
                <div class="login">
                    <h3 class="section__title">Login</h3>
                    <form id="login-form" action="{{ route('user-login') }}" method="POST" class="form grid">
                        @csrf

                        <div class="form-group">
                            <input type="email" id="login-email" name="email" placeholder="Your Email"
                                class="form__input form-control" required />
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="password" id="login-password" name="password" placeholder="Your Password"
                                class="form-control form__input" autocomplete="off" />
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form__btn">
                            <button type="submit" class="btn">Login</button>
                        </div>
                    </form>
                </div>
                <div class="register">
                    <h3 class="section__title">Create an Account</h3>
                    <form id="register-form" action="{{ route('user-register') }}" method="POST" class="form grid">
                        @csrf
                        <div class="form-group">
                            <input type="text" id="register-name" name="register_name" placeholder="Username"
                                class="form-control form__input" />
                            @error('register_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="email" id="register-email" name="register_email" placeholder="Your Email"
                                class="form-control form__input" />
                            @error('register_email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="password" id="register-password" name="register_password" placeholder="Your Password"
                                class="form-control form__input" autocomplete="off" />
                            @error('register_password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="password" id="register-password-confirm" name="register_password_confirmation"
                                placeholder="Confirm Password" class="form-control form__input" autocomplete="off" />
                            @error('register_password_confirmation')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form__btn">
                            <button type="submit" class="btn">Submit & Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
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

            $("#register-form").validate({
                rules: {
                  register_name: {
                        required: true,
                        minlength: 3
                    },
                    register_email: {
                        required: true,
                        email: true
                    },
                    register_password: {
                        required: true,
                        minlength: 8
                    },
                    register_password_confirmation: {
                        required: true,
                        equalTo: "#register-password"
                    }
                },
                messages: {
                  register_name: {
                        required: "Please enter your username",
                        minlength: "Username must be at least 3 characters long"
                    },
                    register_email: {
                        required: "Please enter your email address",
                        email: "Please enter a valid email address"
                    },
                    register_password: {
                        required: "Please enter your password",
                        minlength: "Password must be at least 6 characters long"
                    },
                    register_password_confirmation: {
                        required: "Please confirm your password",
                        equalTo: "Passwords do not match"
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
        </script>
    @endpush
