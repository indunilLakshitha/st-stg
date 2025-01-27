<ul role="list" class="nav-menu w-list-unstyled">
    <li class="nav-mobile-brand">
        <a href="#" class="navbar-brand w-nav-brand"><img loading="lazy"
                src="{{ asset('home/images/Equest-logo.svg') }}" alt="Brand Logo" class="brand-logo"></a>
    </li>
    <li class="nav-menu-list">
        <div class="nav-menu-link-wrapper">
            <a href="{{ route('index') }}" class="nav-menu-link">Home</a>
        </div>
    </li>
    <li class="nav-menu-list">
        <div class="nav-menu-link-wrapper">
            <a href="{{ route('aboutUs') }}" aria-current="page" class="nav-menu-link w--current">About Us</a>
        </div>
    </li>
    <li class="nav-menu-list">
        <div class="nav-menu-link-wrapper">
            <a href="{{ route('courses') }}" class="nav-menu-link">Courses</a>
        </div>
    </li>
    <li class="nav-menu-list">
        <div class="nav-menu-link-wrapper">
            <a href="{{ route('ourTeam') }}" class="nav-menu-link">Our Team</a>
        </div>
    </li>
    <li class="nav-menu-list">
        <div class="nav-menu-link-wrapper">
            <a href="{{ route('contactUs') }}" class="nav-menu-link">Contact Us</a>
        </div>
    </li>
</ul>
<div class="nav-button-block">
    <a href="{{ route('courses') }}" class="nav-menu-button w-button">Enroll Now</a>
</div>
