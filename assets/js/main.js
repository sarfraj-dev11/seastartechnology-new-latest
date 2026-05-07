// seastartechnology — main.js

document.addEventListener('DOMContentLoaded', function () {

  // ── Mobile Menu ──────────────────────────────────────────
  const hamburger = document.getElementById('hamburger');
  const mainNav = document.getElementById('main-nav');
  const overlay = document.getElementById('nav-overlay');
  const header = document.getElementById('site-header');

  if (hamburger) {
    hamburger.addEventListener('click', () => {
      hamburger.classList.toggle('open');
      mainNav.classList.toggle('open');
      overlay.classList.toggle('show');
      document.body.classList.toggle('nav-open');
    });
    overlay.addEventListener('click', closeMenu);
  }

  function closeMenu() {
    hamburger && hamburger.classList.remove('open');
    mainNav && mainNav.classList.remove('open');
    overlay && overlay.classList.remove('show');
    document.body.classList.remove('nav-open');
  }

  // Close on nav link click
  document.querySelectorAll('.main-nav a').forEach(a => a.addEventListener('click', closeMenu));

  // ── Sticky Header ────────────────────────────────────────
  if (header) {
    window.addEventListener('scroll', () => {
      header.classList.toggle('scrolled', window.scrollY > 60);
    });
  }

  // ── Category Filter (products.php) ───────────────────────
  const filterBtns = document.querySelectorAll('.filter-btn');
  const productCards = document.querySelectorAll('#products-grid .product-card');
  const noResults = document.getElementById('no-results');

  if (filterBtns.length) {
    // Apply URL param on load
    const urlCat = new URLSearchParams(window.location.search).get('cat');
    if (urlCat && urlCat !== 'All') applyFilter(urlCat);

    filterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        applyFilter(btn.dataset.cat);
      });
    });
  }

  function applyFilter(cat) {
    let visible = 0;
    productCards.forEach(card => {
      const show = cat === 'All' || card.dataset.category === cat;
      card.style.display = show ? '' : 'none';
      if (show) visible++;
    });
    if (noResults) noResults.style.display = visible === 0 ? 'block' : 'none';
  }

  // ── Sticky Mobile CTA (product-details.php) ───────────────
  const stickyCta = document.getElementById('sticky-cta');
  if (stickyCta) {
    window.addEventListener('scroll', () => {
      stickyCta.classList.toggle('visible', window.scrollY > 400);
    });
  }

  // ── Contact Form AJAX ─────────────────────────────────────
  // const contactForm = document.getElementById('contact-form');
  // if (contactForm) {
  //   contactForm.addEventListener('submit', async function (e) {
  //     e.preventDefault();
  //     const btn = document.getElementById('submit-btn');
  //     btn.disabled = true;
  //     btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

  //     const data = new FormData(contactForm);
  //     try {
  //       const res  = await fetch('contact.php', {
  //         method: 'POST',
  //         headers: { 'X-Requested-With': 'XMLHttpRequest' },
  //         body: data
  //       });
  //       const json = await res.json();
  //       showFormAlert(json.success, json.message);
  //       if (json.success) contactForm.reset();
  //     } catch {
  //       showFormAlert(false, 'An error occurred. Please call us directly.');
  //     }
  //     btn.disabled = false;
  //     btn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Message';
  //   });
  // }

  // function showFormAlert(success, msg) {
  //   let el = document.querySelector('.form-alert');
  //   if (!el) {
  //     el = document.createElement('div');
  //     contactForm.prepend(el);
  //   }
  //   el.className = 'form-alert form-alert--' + (success ? 'success' : 'error');
  //   el.innerHTML = `<i class="fas fa-${success ? 'check-circle' : 'exclamation-circle'}"></i> ${msg}`;
  //   el.scrollIntoView({ behavior: 'smooth', block: 'center' });
  // }

  // ── Trust Logos Ticker (clone for seamless loop) ──────────
  const track = document.querySelector('.trust-logos-track');
  // Items are duplicated in PHP, CSS animation handles the loop.

  // ── Smooth Scroll ─────────────────────────────────────────
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      const target = document.querySelector(a.getAttribute('href'));
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  // ── Admin: manage-products inline edit toggle ─────────────
  document.querySelectorAll('.edit-row-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const row = document.getElementById('edit-' + btn.dataset.id);
      if (row) row.classList.toggle('hidden');
    });
  });

});

document.addEventListener("DOMContentLoaded", function () {

  const form = document.getElementById("contact-form");

  form.addEventListener("submit", function (e) {

    let isValid = true;

    // Remove previous errors
    document.querySelectorAll(".validation-error").forEach(el => el.remove());

    /*
    |--------------------------------------------------------------------------
    | INPUT VALUES
    |--------------------------------------------------------------------------
    */
    let name = document.getElementById("name").value.trim();
    let email = document.getElementById("email").value.trim();
    let phone = document.getElementById("phone").value.trim();
    let message = document.getElementById("message").value.trim();

    /*
    |--------------------------------------------------------------------------
    | NAME VALIDATION
    |--------------------------------------------------------------------------
    */
    let nameRegex = /^[A-Za-z\s]+$/;

    if (name === "") {

      showError("name", "Full Name is required");
      isValid = false;

    } else if (!nameRegex.test(name)) {

      showError("name", "Only letters allowed");
      isValid = false;
    }

    /*
    |--------------------------------------------------------------------------
    | EMAIL VALIDATION
    |--------------------------------------------------------------------------
    */
    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (email === "") {

      showError("email", "Email is required");
      isValid = false;

    } else if (!emailRegex.test(email)) {

      showError("email", "Enter valid email");
      isValid = false;
    }

    /*
    |--------------------------------------------------------------------------
    | PHONE VALIDATION
    |--------------------------------------------------------------------------
    */
    let cleanPhone = phone.replace(/\D/g, '');

    if (phone !== "" && !/^[0-9]+$/.test(cleanPhone)) {

      showError("phone", "Only numbers allowed");
      isValid = false;

    }

    /*
    |--------------------------------------------------------------------------
    | MESSAGE VALIDATION
    |--------------------------------------------------------------------------
    */
    if (message === "") {

      showError("message", "Message is required");
      isValid = false;
    }

    /*
    |--------------------------------------------------------------------------
    | CAPTCHA VALIDATION
    |--------------------------------------------------------------------------
    */
    if (typeof grecaptcha !== "undefined") {

      let captchaResponse = grecaptcha.getResponse();

      if (captchaResponse.length === 0) {

        document.getElementById("captchaError_contact").style.display = "block";
        isValid = false;

      } else {

        document.getElementById("captchaError_contact").style.display = "none";
      }
    }

    /*
    |--------------------------------------------------------------------------
    | STOP FORM SUBMIT
    |--------------------------------------------------------------------------
    */
    if (!isValid) {

      e.preventDefault();
      return false;
    }

  });

});

/*
|--------------------------------------------------------------------------
| SHOW ERROR FUNCTION
|--------------------------------------------------------------------------
*/
function showError(fieldId, message) {

  let field = document.getElementById(fieldId);

  let error = document.createElement("div");

  error.className = "validation-error";

  error.style.color = "red";
  error.style.fontSize = "13px";
  error.style.marginTop = "5px";

  error.innerText = message;

  field.parentNode.appendChild(error);
}


/*
|--------------------------------------------------------------------------
| SHOW ERROR FUNCTION
|--------------------------------------------------------------------------
*/
function showError(fieldId, message) {

  let field = document.getElementById(fieldId);

  let error = document.createElement("div");

  error.className = "validation-error";

  error.style.color = "red";
  error.style.fontSize = "13px";
  error.style.marginTop = "5px";

  error.innerText = message;

  field.parentNode.appendChild(error);
}

