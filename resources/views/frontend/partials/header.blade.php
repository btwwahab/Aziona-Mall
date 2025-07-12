<!--=============== HEADER ===============-->
<header class="header">
    {{-- <div class="header__top">
        <div class="header__container container">
            <div class="header__contact">
                <span>(+01) - 2345 - 6789</span>
                <span>Our location</span>
            </div>
            <p class="header__alert-news">
                Super Values Deals - Save more coupons
            </p>
            <a href="login-register.html" class="header__top-action">
                Log In / Sign Up
            </a>
        </div>
    </div> --}}

    <nav class="nav container">
        <a href="index.html" class="nav__logo">
            <img class="nav__logo-img" src="{{ asset('assets/img/logo-3.png') }}" alt="website logo" />
        </a>
        <div class="nav__menu" id="nav-menu">
            <div class="nav__menu-top">
                <a href="index.html" class="nav__menu-logo">
                    <img src="{{ asset('assets/img/logo.svg') }}" alt="">
                </a>
                <div class="nav__close" id="nav-close">
                    <i class="fi fi-rs-cross-small"></i>
                </div>
            </div>
            <ul class="nav__list">
                <li class="nav__item">
                    <a href="{{ route('home') }}" class="nav__link {{ request()->routeIs('home') ? 'active-link' : '' }}">Home</a>
                </li>
                <li class="nav__item">
                    <a href="{{ route('shop') }}" class="nav__link {{ request()->routeIs('shop') ? 'active-link' : '' }}">Shop</a>
                </li>
                <li class="nav__item">
                    <a href="{{ route('compare') }}" class="nav__link {{ request()->routeIs('compare') ? 'active-link' : '' }}">Compare</a>
                </li>
                @if (Auth::check())
                    <li class="nav__item">
                        <a href="{{ route('account') }}" class="nav__link {{ request()->routeIs('account') ? 'active-link' : '' }}">My Account</a>
                    </li>
                @else
                    <li class="nav__item">
                        <a href="{{ route('login-signup') }}" class="nav__link {{ request()->routeIs('login-signup') ? 'active-link' : '' }}">Login</a>
                    </li>
                @endif
            </ul>
            
            <div class="header__search">
                <form method="GET" action="{{ route('shop') }}" class="header__search">
                    <input type="text"
                           name="q"
                           value="{{ request()->get('q') }}"
                           placeholder="Search For Items..."
                           class="form__input" />
                
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                
                    <button type="submit" class="search__btn">
                        <img src="{{ asset('assets/img/search.png') }}" alt="search icon" />
                    </button>
                </form>
                
            </div>
        </div>
        <div class="header__user-actions">
            <a href="{{ route('wishlist.index') }}" class="header__action-btn" title="Wishlist">
                <img src="{{ asset('assets/img/icon-heart.svg') }}" alt="" />
                <span class="count" id="wishlistCount">{{ $wishlistCount }}</span>
            </a>
            <a href="{{ route('cart.index') }}" class="header__action-btn" title="Cart">
                <img src="{{ asset('assets/img/icon-cart.svg') }}" alt="" />
                <span class="count" id="cartCount">{{ $cartCount }}</span>
            </a>
            <div class="header__action-btn nav__toggle" id="nav-toggle">
                <img src="{{ asset('assets/img/menu-burger.svg') }}" alt="">
            </div>
        </div>
    </nav>
</header>
