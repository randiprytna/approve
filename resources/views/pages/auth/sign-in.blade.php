<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
</head>
<body>
    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
                <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                    @include('components.error-message')
                    <div class="w-lg-500px p-10">
                        <form class="form w-100" action="{{ route('signin') }}" method="POST">
                            @csrf
                            <div class="text-center mb-11">
                                <h1 class="text-gray-900 fw-bolder mb-3">Sign In</h1>
                                <div class="text-gray-500 fw-semibold fs-6">Your Social Campaigns</div>
                            </div>
                            <div class="row g-3 mb-9">
                                <div class="col-md-6">
                                    <a href="#" class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100">
                                        <img alt="Logo" src="{{ asset('assets/media/svg/brand-logos/google-icon.svg') }}" class="h-15px me-3" />Sign in with Google
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="#" class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100">
                                        <img alt="Logo" src="{{ asset('assets/media/svg/brand-logos/apple-black.svg') }}" class="theme-light-show h-15px me-3" />
                                        <img alt="Logo" src="{{ asset('assets/media/svg/brand-logos/apple-black-dark.svg') }}" class="theme-dark-show h-15px me-3" />Sign in with Apple
                                    </a>
                                </div>
                            </div>
                            <div class="separator separator-content my-14">
                                <span class="w-125px text-gray-500 fw-semibold fs-7">Or with email</span>
                            </div>
                            <div class="fv-row mb-8">
                                <input type="text" placeholder="Email" name="email" autocomplete="off" class="form-control bg-transparent" />
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="fv-row mb-3">
                                <input type="password" placeholder="Password" name="password" autocomplete="off" class="form-control bg-transparent" />
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                                <div></div>
                                <a href="#" class="link-primary">Forgot Password ?</a>
                            </div>
                            <div class="d-grid mb-10">
                                <button type="submit" class="btn btn-primary me-10" id="kt_button_1">
                                    <span class="indicator-label">
                                        Submit
                                    </span>
                                    <span class="indicator-progress">
                                        Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                            <div class="text-gray-500 text-center fw-semibold fs-6">Not a Member yet? 
                                <a href="#" class="link-primary">Sign up</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="d-flex flex-center flex-wrap px-5">
                    <div class="d-flex fw-semibold text-primary fs-base">
                        <a href="#" class="px-5">Terms</a>
                        <a href="#" class="px-5">Plans</a>
                        <a href="#" class="px-5">Contact Us</a>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2" style="background-image: url({{ asset('assets/media/misc/auth-bg.png') }})">
                <div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100">
                    <a href="#" class="mb-0 mb-lg-12">
                        <img alt="Logo" src="{{ asset('assets/media/logos/custom-1.png') }}" class="h-60px h-lg-75px" />
                    </a>
                    <img class="d-none d-lg-block mx-auto w-275px w-md-50 w-xl-500px mb-10 mb-lg-20" src="{{ asset('assets/media/misc/auth-screens.png') }}" alt="" />
                    <h1 class="d-none d-lg-block text-white fs-2qx fw-bolder text-center mb-7">Fast, Efficient and Productive</h1>
                    <div class="d-none d-lg-block text-white fs-base text-center">In this kind of post, 
                        <a href="#" class="opacity-75-hover text-warning fw-bold me-1">the blogger</a> introduces a person they’ve interviewed 
                        <br />and provides some background information about 
                        <a href="#" class="opacity-75-hover text-warning fw-bold me-1">the interviewee</a> and their 
                        <br />work following this is a transcript of the interview.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
	<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <script>
        $(document).ready(function() {
            function enableSignInButton() {
                var emailValid = $('input[name="email"]').hasClass('is-valid');
                var passwordValid = $('input[name="password"]').hasClass('is-valid');

                if (emailValid && passwordValid) {
                    $('button[type="submit"]').removeAttr('disabled');
                } else {
                    $('button[type="submit"]').attr('disabled', 'disabled');
                }
            }
            $('input[name="email"]').on('input', function() {
                var emailVal = $(this).val().trim();
                if (emailVal === '') {
                    $(this).addClass('is-invalid').removeClass('is-valid');
                    $(this).next('.invalid-feedback').text('Email address is required');
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next('.invalid-feedback').text('');
                    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailPattern.test(emailVal)) {
                        $(this).addClass('is-invalid').removeClass('is-valid');
                        $(this).next('.invalid-feedback').text('The value is not a valid email address');
                    } else {
                        $(this).addClass('is-valid').removeClass('is-invalid');
                        $(this).next('.invalid-feedback').text('');
                    }
                }
                enableSignInButton();
            });
            $('input[name="password"]').on('input', function() {
                var passwordVal = $(this).val().trim();
                if (passwordVal === '') {
                    $(this).addClass('is-invalid').removeClass('is-valid');
                    $(this).next('.invalid-feedback').text('The password is required');
                } else {
                    $(this).addClass('is-valid').removeClass('is-invalid');
                    $(this).next('.invalid-feedback').text('');
                }
                enableSignInButton();
            });
            $('form').submit(function(event) {
                var emailField = $('input[name="email"]');
                var passwordField = $('input[name="password"]');
                var emailVal = emailField.val().trim();
                var passwordVal = passwordField.val().trim();
                if (emailVal === '' || !emailPattern.test(emailVal)) {
                    emailField.addClass('is-invalid').removeClass('is-valid');
                    emailField.next('.invalid-feedback').text(emailVal === '' ? 'Email address is required' : 'The value is not a valid email address');
                    event.preventDefault();
                }
                if (passwordVal === '') {
                    passwordField.addClass('is-invalid').removeClass('is-valid');
                    passwordField.next('.invalid-feedback').text('The password is required');
                    event.preventDefault();
                }
            });
        });
    </script>
</body>
</html>