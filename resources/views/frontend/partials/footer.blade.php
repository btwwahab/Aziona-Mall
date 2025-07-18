<!--=============== NEWSLETTER ===============-->
<section class="newsletter section home__newsletter">
  <div class="newsletter__container container grid">
    <h3 class="newsletter__title flex">
      <img
        src="{{asset('assets/img/icon-email.svg')}}"
        alt=""
        class="newsletter__icon"
      />
      Sign in to Newsletter
    </h3>
    <p class="newsletter__description">
      ...and receive $25 coupon for first shopping.
    </p>
    <form action="" class="newsletter__form">
      <input
        type="text"
        placeholder="Enter Your Email"
        class="newsletter__input"
      />
      <button type="submit" class="newsletter__btn">Subscribe</button>
    </form>
  </div>
</section>
</main>
<!--=============== FOOTER ===============-->
<footer class="footer container">
  <div class="footer__container grid">
    <div class="footer__content">
      <a href="index.html" class="footer__logo">
        <img class="nav__logo-img" src="{{ asset('assets/img/logo-3.png') }}" alt="website logo" />
      </a>
      <h4 class="footer__subtitle">Contact</h4>
      <p class="footer__description">
        <span>Address:</span> 13 Tlemcen Road, Street 32, Beb-Wahren
      </p>
      <p class="footer__description">
        <span>Phone:</span> +01 2222 365 /(+91) 01 2345 6789
      </p>
      <p class="footer__description">
        <span>Hours:</span> 10:00 - 18:00, Mon - Sat
      </p>
      <div class="footer__social">
        <h4 class="footer__subtitle">Follow Me</h4>
        <div class="footer__links flex">
          <a href="#">
            <img
              src="{{asset('assets/img/icon-facebook.svg')}}"
              alt=""
              class="footer__social-icon"
            />
          </a>
          <a href="#">
            <img
              src="{{asset('assets/img/icon-twitter.svg')}}"
              alt=""
              class="footer__social-icon"
            />
          </a>
          <a href="#">
            <img
              src="{{asset('assets/img/icon-instagram.svg')}}"
              alt=""
              class="footer__social-icon"
            />
          </a>
          <a href="#">
            <img
              src="{{asset('assets/img/icon-pinterest.svg')}}"
              alt=""
              class="footer__social-icon"
            />
          </a>
          <a href="#">
            <img
              src="{{asset('assets/img/icon-youtube.svg')}}"
              alt=""
              class="footer__social-icon"
            />
          </a>
        </div>
      </div>
    </div>
    <div class="footer__content">
      <h3 class="footer__title">Address</h3>
      <ul class="footer__links">
        <li><a href="#" class="footer__link">About Us</a></li>
        <li><a href="#" class="footer__link">Delivery Information</a></li>
        <li><a href="#" class="footer__link">Privacy Policy</a></li>
        <li><a href="#" class="footer__link">Terms & Conditions</a></li>
        <li><a href="#" class="footer__link">Contact Us</a></li>
        <li><a href="#" class="footer__link">Support Center</a></li>
      </ul>
    </div>
    <div class="footer__content">
      <h3 class="footer__title">My Account</h3>
      <ul class="footer__links">
        <li><a href="#" class="footer__link">Sign In</a></li>
        <li><a href="#" class="footer__link">View Cart</a></li>
        <li><a href="#" class="footer__link">My Wishlist</a></li>
        <li><a href="#" class="footer__link">Track My Order</a></li>
        <li><a href="#" class="footer__link">Help</a></li>
        <li><a href="#" class="footer__link">Order</a></li>
      </ul>
    </div>
    <div class="footer__content">
      <h3 class="footer__title">Secured Payed Gateways</h3>
      <img
        src="{{asset('assets/img/payment-method.png')}}"
        alt=""
        class="payment__img"
      />
    </div>
  </div>
  <div class="footer__bottom">
    <p class="copyright">&copy; 2025 Aziona Mall by Aziona. All right reserved</p>
    <span class="designer">Designed by Aziona</span>
  </div>
</footer>


