<!DOCTYPE html>
<!--  Last Published: Sat Jan 11 2025 06:27:08 GMT+0000 (Coordinated Universal Time)  -->
<html data-wf-page="6780ec5a92222feda1dd8a6d" data-wf-site="6744125b4028f712efbe2a96">

<head>
    <meta charset="utf-8">
    <title>Forget Password</title>
    <meta content="Forget Password" property="og:title">
    <meta content="https://cdn.prod.website-files.com/6744125b4028f712efbe2a96/67820d51bcc1aee9221799ee_equest-og.png"
        property="og:image">
    <meta content="Forget Password" property="twitter:title">
    <meta content="https://cdn.prod.website-files.com/6744125b4028f712efbe2a96/67820d51bcc1aee9221799ee_equest-og.png"
        property="twitter:image">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <link href="{{ asset('home/css/normalize.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('home/css/webflow.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('home/css/eq-site-new-032.webflow.css') }}" rel="stylesheet" type="text/css">
    <script type="text/javascript">
        ! function(o, c) {
            var n = c.documentElement,
                t = " w-mod-";
            n.className += t + "js", ("ontouchstart" in o || o.DocumentTouch && c instanceof DocumentTouch) && (n
                .className += t + "touch")
        }(window, document);
    </script>
    <link href="{{ asset('home/images/favicon.png') }}" rel="shortcut icon" type="image/x-icon">
    <link href="i{{ asset('home/mages/webclip.png') }}" rel="apple-touch-icon">
    <style>
        a.w-webflow-badge {
            display: none !important;
        }
    </style>
</head>

<body>
    <main id="top" class="page-wrapper">
        <header class="header">
            <div class="w-layout-blockcontainer container-default w-container">
                <div data-animation="over-left" data-collapse="medium" data-duration="400" data-easing="ease"
                    data-easing2="ease" role="banner" class="navbar-container w-nav">
                    <div class="navbar-wrapper">
                        <a href="{{ route('index') }}" class="navbar-brand w-nav-brand"><img loading="lazy"
                                src="{{ asset('home/images/Equest-logo.svg') }}" alt="Brand Logo"
                                class="brand-logo"></a>
                        <nav role="navigation" class="nav-menu-wrapper w-nav-menu">
                            @include('home.common.nav_links')
                        </nav>
                        <div class="menu-button w-nav-button">
                            <div class="w-icon-nav-menu"></div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <main class="main-wrapper">
            <section class="home-about-section">
                <div class="section-gap is-registerform">
                    <div class="w-layout-blockcontainer container-default w-container">
                        <div class="home-about-wrapper">
                            <div id="w-node-_4504abfe-e50f-4aac-bd0f-ccdd7bb449e2-a1dd8a6d"
                                class="home-about-image-block is-login"><img
                                    src="{{ asset('home/images/home-about-image.jpg') }}" loading="lazy"
                                    sizes="(max-width: 479px) 100vw, (max-width: 681px) 95vw, (max-width: 991px) 647px, (max-width: 1279px) 440px, (max-width: 1439px) 542.5px, 646.890625px"
                                    srcset="{{ asset('home/images/home-about-image-p-500.jpg') }} 500w, {{ asset('home/images/home-about-image.jpg') }} 647w"
                                    alt="Home About Image" class="home-about-image h-100"></div>
                            <div id="w-node-_4504abfe-e50f-4aac-bd0f-ccdd7bb449e9-a1dd8a6d"
                                class="home-about-content-block is-login">
                                <div class="contact-us-form-block">
                                    <div class="section-title-wrapper center is-low">
                                        <div class="overflow-hidden">
                                            <h2 data-w-id="b95a6cd3-528c-a887-18c6-53b0075de7a1"
                                                style="-webkit-transform:translate3d(0, 180%, 0) scale3d(1, 1, 1) rotateX(null) rotateY(-45deg) rotateZ(0) skew(-10deg, 10deg);-moz-transform:translate3d(0, 180%, 0) scale3d(1, 1, 1) rotateX(null) rotateY(-45deg) rotateZ(0) skew(-10deg, 10deg);-ms-transform:translate3d(0, 180%, 0) scale3d(1, 1, 1) rotateX(null) rotateY(-45deg) rotateZ(0) skew(-10deg, 10deg);transform:translate3d(0, 180%, 0) scale3d(1, 1, 1) rotateX(null) rotateY(-45deg) rotateZ(0) skew(-10deg, 10deg);transform-style:preserve-3d"
                                                class="section-title is-credintail">Add New Password</h2>
                                        </div>
                                        <div class="overflow-hidden">
                                            <p data-w-id="b95a6cd3-528c-a887-18c6-53b0075de7a4"
                                                style="-webkit-transform:translate3d(0, 180%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(-45deg) rotateZ(0) skew(-10deg, 10deg);-moz-transform:translate3d(0, 180%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(-45deg) rotateZ(0) skew(-10deg, 10deg);-ms-transform:translate3d(0, 180%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(-45deg) rotateZ(0) skew(-10deg, 10deg);transform:translate3d(0, 180%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(-45deg) rotateZ(0) skew(-10deg, 10deg);transform-style:preserve-3d"
                                                class="section-title-except">Please fill details below.</p>
                                        </div>
                                    </div>
                                    <div class="contact2_form-block w-form">
                                        <form id="wf-form-Registration-Form" name="wf-form-Registration-Form"
                                            data-name="Registration Form" class="contact2_form" method="POST"
                                            action="{{ route('forgotPassword.change') }}"
                                            data-wf-page-id="6780ec5a92222feda1dd8a6d"
                                            data-wf-element-id="b95a6cd3-528c-a887-18c6-53b0075de7a7">
                                            @csrf
                                            <div class="form_field-wrapper"><label for="email-3"
                                                    class="form_field-label">User ID</label><input
                                                    class="form-input-field w-input" disabled
                                                    placeholder="Enter your user ID" value="{{ $user_id }}"
                                                    type="number" required="">
                                                <input type="hidden" class="d-none" value="{{ $ref_id }}"
                                                    name="ref_id">
                                                <input type="hidden" class="d-none" value="{{ $user_id }}"
                                                    name="user_id">
                                                @if ($errors->has('error'))
                                                    <div style="color: red">
                                                        {{ $errors->first('error') }}
                                                    </div>
                                                @endif

                                            </div>

                                            <input type="hidden" class="d-none" value="{{ $ref_id }}">
                                            <div id="w-node-b95a6cd3-528c-a887-18c6-53b0075de7ac-a1dd8a6d"
                                                class="form_field-wrapper"><label for="email-2"
                                                    class="form_field-label">Password</label><input
                                                    class="form-input-field w-input" maxlength="256"
                                                    data-name="Email 2" placeholder="Enter your email"
                                                    type="password" name="password" required="">
                                                @error('password')
                                                    <div style="color: red">
                                                        {{ $message }}
                                                    </div>
                                                @enderror

                                            </div>
                                            <div id="w-node-b95a6cd3-528c-a887-18c6-53b0075de7ac-a1dd8a6d"
                                                class="form_field-wrapper"><label for="email-2"
                                                    class="form_field-label">Confirm Password</label><input
                                                    class="form-input-field w-input" maxlength="256"
                                                    data-name="Email 2" placeholder="Confirm Password"
                                                    type="password" name="confirm_password" required="">
                                                @error('confirm_password')
                                                    <div style="color: red">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                                <div id="w-node-b95a6cd3-528c-a887-18c6-53b0075de7b0-a1dd8a6d"
                                                    class="_14-px-text">Still Need Help? <a
                                                        href="c{{ route('contactUs') }}">Contact Us</a>
                                                </div>
                                            </div>
                                            <div id="w-node-b95a6cd3-528c-a887-18c6-53b0075de7b2-a1dd8a6d"
                                                class="register-margin-top is-login"><input type="submit"
                                                    data-wait="Please wait..."
                                                    id="w-node-b95a6cd3-528c-a887-18c6-53b0075de7b3-a1dd8a6d"
                                                    class="submit-button w-button" value="Reset Password"></div>
                                        </form>
                                        <div class="form_message-success-wrapper w-form-done">
                                            <div class="form_message-success">
                                                <div class="success-text">Thank you! Your submission has been received!
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form_message-error-wrapper w-form-fail">
                                            <div class="form_message-error">
                                                <div class="error-text">Oops! Something went wrong while submitting the
                                                    form.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <section data-wf--footer--variant="base" class="footer-section-wrapper">
            <div class="footer-section">
                <div class="footer-main-wrapper">
                    <div class="footer-cta-block">
                        <div class="w-layout-blockcontainer container-default w-container">
                            <div class="cta-block">
                                <h2 class="cta-title">Let’s Make Something Amazing Together</h2>
                                <div class="button-wrapper">
                                    <a href="contact-us.html" class="circle-link-block w-inline-block">
                                        <div class="circle-link-icon w-embed"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="44" height="44" viewbox="0 0 44 44" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M32.0686 11.3967C32.7758 12.1039 32.7758 13.2506 32.0686 13.9579L12.147 33.8795C11.4397 34.5868 10.293 34.5868 9.58574 33.8795C8.87848 33.1723 8.87848 32.0256 9.58574 31.3183L29.5074 11.3967C30.2146 10.6894 31.3614 10.6894 32.0686 11.3967Z"
                                                    fill="#4D54CE"></path>
                                                <path
                                                    d="M31.2306 9.58054C31.739 9.72888 32.4297 9.98897 32.9533 10.5125C33.4769 11.0361 33.7371 11.7269 33.8855 12.2353C34.0494 12.7973 34.1577 13.433 34.2323 14.0724C34.3822 15.356 34.4194 16.8626 34.4085 18.2635C34.3974 19.6752 34.3366 21.0308 34.2792 22.0299C34.2504 22.5305 34.2221 22.9443 34.2011 23.2341C34.1906 23.3775 34.1722 23.6122 34.1658 23.693L34.1656 23.6952C34.0816 24.692 33.2054 25.4329 32.2088 25.3489C31.2122 25.2648 30.4723 24.3888 30.5562 23.3922C30.5618 23.3205 30.5786 23.1086 30.5886 22.9715C30.6085 22.6971 30.6353 22.3018 30.663 21.8216C30.7185 20.8594 30.776 19.5674 30.7864 18.235C30.7969 16.8918 30.759 15.5574 30.6346 14.4925C30.5721 13.9577 30.4468 13.3967 30.3615 13.1044C30.0692 13.0191 29.5083 12.8937 28.9735 12.8313C27.9084 12.707 26.574 12.6689 25.2308 12.6794C23.8984 12.6899 22.6065 12.7474 21.6441 12.8028C21.164 12.8305 20.7687 12.8574 20.4945 12.8773C20.3574 12.8873 20.1462 12.9039 20.0745 12.9095C19.0779 12.9935 18.2011 12.2537 18.1171 11.2571C18.0331 10.2604 18.7729 9.38429 19.7697 9.30028L19.7737 9.29995C19.8563 9.29345 20.0893 9.27507 20.2319 9.26471C20.5216 9.24365 20.9355 9.21548 21.436 9.18667C22.435 9.12919 23.7906 9.0685 25.2025 9.05745C26.6034 9.04648 28.11 9.08375 29.3935 9.23361C30.033 9.30826 30.6687 9.41651 31.2306 9.58054Z"
                                                    fill="#4D54CE"></path>
                                            </svg></div><img
                                            src="{{ asset('home/images/contact-us-circular-text.svg') }}"
                                            loading="lazy" data-w-id="1e83ad58-3581-5f38-e940-99c6d1baba95"
                                            alt="Circular Text" class="circular-text">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="footer-upper-block">
                        <div class="w-layout-blockcontainer container-default w-container">
                            <div class="footer-main-block">
                                <div id="w-node-_1e83ad58-3581-5f38-e940-99c6d1baba99-d1baba8b"
                                    class="footer-about-wrapper">
                                    <a href="{{ route('index') }}" class="footer-brand w-inline-block"><img width="86"
                                            loading="lazy" alt="Brand Logo"
                                            src="{{ asset('home/images/Equest-logo-white.svg') }}"
                                            class="footer-brand-logo"></a>
                                    <p class="footer-about-paragraph">Ready to Start Your Entrepreneurial Journey?
                                        Explore Our Courses Today and Build the Future You’ve Always Dreamed Of!</p>
                                    <a data-w-id="1e83ad58-3581-5f38-e940-99c6d1baba9e" href="courses.html"
                                        class="primary-button footer w-inline-block">
                                        <div class="button-icon-wrapper two">
                                            <div class="button-icon w-embed"><svg xmlns="http://www.w3.org/2000/svg"
                                                    width="16" height="16" viewbox="0 0 16 16"
                                                    fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M11.567 4.43306C11.8111 4.67714 11.8111 5.07286 11.567 5.31694L4.692 12.1919C4.44792 12.436 4.0522 12.436 3.80812 12.1919C3.56404 11.9479 3.56404 11.5521 3.80812 11.3081L10.6831 4.43306C10.9272 4.18898 11.3229 4.18898 11.567 4.43306Z"
                                                        fill="white"></path>
                                                    <path
                                                        d="M11.2777 3.80614C11.4531 3.85733 11.6915 3.94709 11.8722 4.12777C12.0529 4.30847 12.1427 4.54685 12.1939 4.72229C12.2504 4.91624 12.2878 5.13563 12.3136 5.3563C12.3653 5.79925 12.3781 6.31917 12.3744 6.80262C12.3706 7.28981 12.3496 7.75762 12.3297 8.10244C12.3198 8.27519 12.3101 8.418 12.3028 8.518C12.2992 8.5675 12.2928 8.6485 12.2906 8.67637L12.2906 8.67712C12.2616 9.02112 11.9592 9.27681 11.6152 9.24781C11.2713 9.21881 11.016 8.9165 11.0449 8.57256C11.0469 8.54781 11.0527 8.47469 11.0561 8.42737C11.063 8.33269 11.0722 8.19625 11.0818 8.03056C11.1009 7.6985 11.1208 7.25262 11.1244 6.79281C11.128 6.32926 11.1149 5.86875 11.072 5.50125C11.0504 5.31669 11.0072 5.12309 10.9777 5.02222C10.8769 4.99279 10.6833 4.94952 10.4987 4.92797C10.1312 4.88507 9.67068 4.87195 9.20712 4.87557C8.74731 4.87917 8.3015 4.89904 7.96937 4.91815C7.80368 4.92769 7.66725 4.93698 7.57262 4.94385C7.52531 4.94729 7.45243 4.95303 7.42768 4.95498C7.08375 4.98394 6.78118 4.72865 6.75218 4.38471C6.72318 4.04075 6.9785 3.73841 7.3225 3.70942L7.32387 3.7093C7.35237 3.70706 7.43281 3.70072 7.482 3.69714C7.582 3.68987 7.72481 3.68015 7.89756 3.67021C8.24231 3.65037 8.71012 3.62943 9.19737 3.62562C9.68081 3.62183 10.2007 3.63469 10.6437 3.68641C10.8644 3.71217 11.0837 3.74953 11.2777 3.80614Z"
                                                        fill="white"></path>
                                                </svg></div>
                                            <div class="button-bg footer"></div>
                                        </div>
                                        <div class="button-text">Enroll Now</div>
                                        <div class="button-icon-wrapper one">
                                            <div class="button-icon w-embed"><svg xmlns="http://www.w3.org/2000/svg"
                                                    width="16" height="16" viewbox="0 0 16 16"
                                                    fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M11.567 4.43306C11.8111 4.67714 11.8111 5.07286 11.567 5.31694L4.692 12.1919C4.44792 12.436 4.0522 12.436 3.80812 12.1919C3.56404 11.9479 3.56404 11.5521 3.80812 11.3081L10.6831 4.43306C10.9272 4.18898 11.3229 4.18898 11.567 4.43306Z"
                                                        fill="white"></path>
                                                    <path
                                                        d="M11.2777 3.80614C11.4531 3.85733 11.6915 3.94709 11.8722 4.12777C12.0529 4.30847 12.1427 4.54685 12.1939 4.72229C12.2504 4.91624 12.2878 5.13563 12.3136 5.3563C12.3653 5.79925 12.3781 6.31917 12.3744 6.80262C12.3706 7.28981 12.3496 7.75762 12.3297 8.10244C12.3198 8.27519 12.3101 8.418 12.3028 8.518C12.2992 8.5675 12.2928 8.6485 12.2906 8.67637L12.2906 8.67712C12.2616 9.02112 11.9592 9.27681 11.6152 9.24781C11.2713 9.21881 11.016 8.9165 11.0449 8.57256C11.0469 8.54781 11.0527 8.47469 11.0561 8.42737C11.063 8.33269 11.0722 8.19625 11.0818 8.03056C11.1009 7.6985 11.1208 7.25262 11.1244 6.79281C11.128 6.32926 11.1149 5.86875 11.072 5.50125C11.0504 5.31669 11.0072 5.12309 10.9777 5.02222C10.8769 4.99279 10.6833 4.94952 10.4987 4.92797C10.1312 4.88507 9.67068 4.87195 9.20712 4.87557C8.74731 4.87917 8.3015 4.89904 7.96937 4.91815C7.80368 4.92769 7.66725 4.93698 7.57262 4.94385C7.52531 4.94729 7.45243 4.95303 7.42768 4.95498C7.08375 4.98394 6.78118 4.72865 6.75218 4.38471C6.72318 4.04075 6.9785 3.73841 7.3225 3.70942L7.32387 3.7093C7.35237 3.70706 7.43281 3.70072 7.482 3.69714C7.582 3.68987 7.72481 3.68015 7.89756 3.67021C8.24231 3.65037 8.71012 3.62943 9.19737 3.62562C9.68081 3.62183 10.2007 3.63469 10.6437 3.68641C10.8644 3.71217 11.0837 3.74953 11.2777 3.80614Z"
                                                        fill="white"></path>
                                                </svg></div>
                                            <div class="button-bg footer"></div>
                                        </div>
                                    </a>
                                </div>
                                <div class="footer-column">
                                    <div class="footer-heading">Quick Links</div>
                                    <div class="footer-link-block">
                                        <div class="footer-link-wrapper">
                                            <a href="{{ route('index') }}" aria-current="page"
                                                class="footer-link w--current">Home</a>
                                            <a href="{{ route('aboutUs') }}" class="footer-link">About</a>
                                            <a href="{{ route('courses') }}" class="footer-link">Courses</a>
                                            <a href="{{ route('marketplace.user.index') }}"
                                                class="footer-link">Marketplace</a>
                                        </div>
                                        <div class="footer-link-wrapper">
                                            <a href="{{ route('ourTeam') }}" class="footer-link">Our Team</a>
                                            <a href="{{ route('contactUs') }}" class="footer-link">Contact Us</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="footer-column">
                                <div class="footer-heading">Other Pages</div>
                                <div class="footer-link-wrapper">
                                    <a href="{{ route('tNc') }}" class="footer-link">Terms and Conditions</a>
                                    <a href="{{ route('pp') }}" class="footer-link">Privacy Policy </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer-lower-block">
                    <div class="w-layout-blockcontainer container-default w-container">
                        <div class="footer-copyright-text">Copyright © 2024 <a
                                href="https://dash.equest.lk/register/163402#" target="_blank" class="cc-text">Equest
                                Institute of Higher Education</a>| Developed by <a href="https://solluton.com/"
                                target="_blank" class="footer-copyright-link">Solluton</a>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </section>
    </main>
    <a href="#top" class="scroll-to-top w-inline-block"><img src="{{ asset('home/images/scroll-to-top.svg') }}"
            loading="lazy" alt="Scroll to Top Icon" class="scroll-to-top-icon"></a>
    <script src="https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.5.1.min.dc5e7f18c8.js?site=6744125b4028f712efbe2a96"
        type="text/javascript" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous">
    </script>
    <script src="{{ asset('home/js/webflow.js') }}" type="text/javascript"></script>
</body>

</html>
