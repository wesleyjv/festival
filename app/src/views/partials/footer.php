</main>

<style>
    .site-footer {
        background: #1a252f;
        color: rgba(255, 255, 255, 0.88);
        margin-top: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .site-footer__inner {
        max-width: 1100px;
        margin: 0 auto;
        padding: 48px 20px 32px;
    }
    .site-footer__brand {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        margin-bottom: 40px;
    }
    .site-footer__logo {
        display: flex;
        align-items: center;
        gap: 14px;
        text-decoration: none;
        color: #fff;
    }
    .site-footer__logo-mark {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        background: linear-gradient(145deg, #f5ead5 0%, #e8dcc4 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
    }
    .site-footer__logo-mark i {
        font-size: 1.35rem;
        background: linear-gradient(135deg, #c9a227 0%, #e07b2a 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        -webkit-text-fill-color: transparent;
    }
    .site-footer__logo-text {
        font-weight: 800;
        font-size: 0.82rem;
        letter-spacing: 0.2em;
        line-height: 1.35;
        text-transform: uppercase;
        text-align: left;
    }
    .site-footer__grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 28px 24px;
        margin-bottom: 36px;
    }
    @media (max-width: 767.98px) {
        .site-footer__grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 479.98px) {
        .site-footer__grid {
            grid-template-columns: 1fr;
        }
    }
    .site-footer__col h3 {
        font-size: 0.8rem;
        font-weight: 700;
        color: #fff;
        margin: 0 0 14px;
        letter-spacing: 0.06em;
    }
    .site-footer__col ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .site-footer__col li {
        margin-bottom: 10px;
    }
    .site-footer__col a {
        color: rgba(255, 255, 255, 0.58);
        text-decoration: none;
        font-size: 0.88rem;
        transition: color 0.2s;
    }
    .site-footer__col a:hover {
        color: #fff;
    }
    .site-footer__social {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .site-footer__social a {
        width: 42px;
        height: 42px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.15rem;
        transition: background 0.2s, color 0.2s;
    }
    .site-footer__social a:hover {
        background: rgba(255, 255, 255, 0.16);
        color: #fff;
    }
    .site-footer__bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.12);
        padding: 22px 20px 28px;
        text-align: center;
    }
    .site-footer__legal {
        font-size: 0.78rem;
        color: rgba(255, 255, 255, 0.45);
        line-height: 1.6;
        margin: 0;
    }
    .site-footer__legal a {
        color: rgba(255, 255, 255, 0.55);
        text-decoration: none;
    }
    .site-footer__legal a:hover {
        color: #fff;
    }
</style>

<footer class="site-footer">
    <div class="site-footer__inner">
        <div class="site-footer__brand">
            <a href="/" class="site-footer__logo">
                <span class="site-footer__logo-mark" aria-hidden="true"><i class="bi bi-stars"></i></span>
                <span class="site-footer__logo-text">THE HAARLEM<br>FESTIVAL</span>
            </a>
        </div>

        <div class="site-footer__grid">
            <div class="site-footer__col">
                <h3>Festival</h3>
                <ul>
                    <li><a href="/events/yummy">Yummy Food Event</a></li>
                    <li><a href="/events/jazz">Jazz Event</a></li>
                    <li><a href="/events/history">History Event</a></li>
                    <li><a href="/events/stories">Storytelling Event</a></li>
                </ul>
            </div>

            <!-- Account -->
            <div class="col-6 col-lg-2 mb-4 mb-lg-0">
                <h6 style="text-transform: uppercase; font-size: 0.72rem; letter-spacing: 2px; color: rgba(255,255,255,0.4); margin-bottom: 16px;">Account</h6>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <?php if (!empty($_SESSION['user_id'])): ?>
                        <li style="margin-bottom: 10px;"><a href="/profile" style="color: rgba(255,255,255,0.75); text-decoration: none; font-size: 0.88rem;">My Profile</a></li>
                        <li style="margin-bottom: 10px;"><a href="/logout" style="color: rgba(255,255,255,0.75); text-decoration: none; font-size: 0.88rem;">Logout</a></li>
                    <?php else: ?>
                        <li style="margin-bottom: 10px;"><a href="/login" style="color: rgba(255,255,255,0.75); text-decoration: none; font-size: 0.88rem;">Login</a></li>
                        <li style="margin-bottom: 10px;"><a href="/register" style="color: rgba(255,255,255,0.75); text-decoration: none; font-size: 0.88rem;">Register</a></li>
                    <?php endif; ?>
                    <li><a href="/cart" style="color: rgba(255,255,255,0.75); text-decoration: none; font-size: 0.88rem;">My Program</a></li>
                </ul>
            </div>
            <div class="site-footer__col">
                <h3>Support</h3>
                <ul>
                    <li><a href="mailto:info@haarlemfestival.nl">Customer Service</a></li>
                    <li><a href="/tickets">Booking Help</a></li>
                    <li><a href="#">Cancellation</a></li>
                </ul>
            </div>
            <div class="site-footer__col">
                <h3>Follow Us</h3>
                <div class="site-footer__social">
                    <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="site-footer__bottom">
        <p class="site-footer__legal">
            &copy; <?= date('Y') ?> The Festival Haarlem. All rights reserved.
            <span aria-hidden="true"> | </span>
            <a href="#">Privacy Policy</a>
            <span aria-hidden="true"> | </span>
            <a href="#">Terms of Service</a>
        </p>
    </div>
</footer>

<!-- Add-to-cart feedback (AJAX forms with class js-cart-add-form) -->
<div class="modal fade" id="cartAddModal" tabindex="-1" aria-labelledby="cartAddModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="cartAddModalTitle">
                    <span id="cartAddModalIcon" class="cart-add-modal__icon" aria-hidden="true"></span>
                    <span id="cartAddModalTitleText">Cart</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-2" id="cartAddModalBody">
                <p class="mb-0 text-secondary" id="cartAddModalMessage"></p>
            </div>
            <div class="modal-footer border-0 flex-wrap gap-2 justify-content-stretch" id="cartAddModalFooter">
                <button type="button" class="btn btn-outline-secondary flex-grow-1" data-bs-dismiss="modal">Continue browsing</button>
                <a href="/cart" class="btn btn-dark flex-grow-1" id="cartAddModalBtnCart">View cart</a>
                <a href="/checkout" class="btn btn-primary flex-grow-1" id="cartAddModalBtnCheckout">Go to checkout</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
(function () {
    function getCsrfToken(form) {
        var input = form.querySelector('input[name="csrf_token"]');
        return input ? input.value : '';
    }

    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!form || !form.classList || !form.classList.contains('js-cart-add-form')) {
            return;
        }
        e.preventDefault();

        var submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
        }

        var fd = new FormData(form);
        fd.set('ajax', '1');

        var csrf = getCsrfToken(form);
        fetch(form.action || '/cart/add', {
            method: 'POST',
            body: fd,
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': csrf
            }
        })
            .then(function (res) {
                return res.json().catch(function () {
                    return { ok: false, message: 'Something went wrong. Please try again.' };
                });
            })
            .then(function (data) {
                showCartModal(data);
            })
            .catch(function () {
                showCartModal({ ok: false, message: 'Network error. Please try again.' });
            })
            .finally(function () {
                if (submitBtn) {
                    submitBtn.disabled = false;
                }
            });
    });

    function showCartModal(data) {
        var ok = data && data.ok === true;
        var modalEl = document.getElementById('cartAddModal');
        var titleText = document.getElementById('cartAddModalTitleText');
        var iconEl = document.getElementById('cartAddModalIcon');
        var msgEl = document.getElementById('cartAddModalMessage');
        var btnCart = document.getElementById('cartAddModalBtnCart');
        var btnCheckout = document.getElementById('cartAddModalBtnCheckout');
        var footer = document.getElementById('cartAddModalFooter');

        if (!modalEl || !msgEl) {
            return;
        }

        msgEl.textContent = (data && data.message) ? data.message : (ok ? 'Added to cart.' : 'Could not add to cart.');

        if (ok) {
            titleText.textContent = 'Added to your cart';
            iconEl.innerHTML = '<i class="bi bi-check-circle-fill text-success fs-4"></i>';
            iconEl.className = 'cart-add-modal__icon';
            if (btnCart) btnCart.classList.remove('d-none');
            if (btnCheckout) btnCheckout.classList.remove('d-none');
        } else {
            titleText.textContent = 'Could not add ticket';
            iconEl.innerHTML = '<i class="bi bi-exclamation-circle-fill text-danger fs-4"></i>';
            iconEl.className = 'cart-add-modal__icon';
            if (btnCart) btnCart.classList.add('d-none');
            if (btnCheckout) btnCheckout.classList.add('d-none');
        }

        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }
    }
})();
</script>
</body>
</html>
