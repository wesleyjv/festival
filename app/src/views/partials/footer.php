</main>

<footer class="mt-5" style="background: #1e1e1e; color: rgba(255,255,255,0.85);">
    <div class="container">
        <div class="row py-5">
            <!-- Brand & tagline -->
            <div class="col-lg-4 mb-4 mb-lg-0">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
                    <span style="width: 36px; height: 36px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-music-note-beamed" style="color: #fff; font-size: 1rem;"></i>
                    </span>
                    <strong style="font-size: 1rem; text-transform: uppercase; letter-spacing: 1.5px;">The Haarlem Festival</strong>
                </div>
                <p style="font-size: 0.85rem; color: rgba(255,255,255,0.5); line-height: 1.6; margin: 0;">
                    Experience the best of Haarlem's culture, food, music, and history.
                </p>
            </div>

            <!-- Events -->
            <div class="col-6 col-lg-2 mb-4 mb-lg-0">
                <h6 style="text-transform: uppercase; font-size: 0.72rem; letter-spacing: 2px; color: rgba(255,255,255,0.4); margin-bottom: 16px;">Events</h6>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="margin-bottom: 10px;"><a href="/events/yummy" style="color: rgba(255,255,255,0.75); text-decoration: none; font-size: 0.88rem; transition: color 0.2s;"><i class="bi bi-cup-straw" style="color: #e74c3c; margin-right: 6px;"></i>Yummy</a></li>
                    <li style="margin-bottom: 10px;"><a href="/events/jazz" style="color: rgba(255,255,255,0.75); text-decoration: none; font-size: 0.88rem;"><i class="bi bi-music-note" style="color: #9b59b6; margin-right: 6px;"></i>Jazz</a></li>
                    <li style="margin-bottom: 10px;"><a href="/events/history" style="color: rgba(255,255,255,0.75); text-decoration: none; font-size: 0.88rem;"><i class="bi bi-bank" style="color: #27ae60; margin-right: 6px;"></i>History</a></li>
                    <li><a href="/events/stories" style="color: rgba(255,255,255,0.75); text-decoration: none; font-size: 0.88rem;"><i class="bi bi-book" style="color: #e67e22; margin-right: 6px;"></i>Storytelling</a></li>
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
                    <li><a href="#" style="color: rgba(255,255,255,0.75); text-decoration: none; font-size: 0.88rem;">My Program</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="col-lg-4">
                <h6 style="text-transform: uppercase; font-size: 0.72rem; letter-spacing: 2px; color: rgba(255,255,255,0.4); margin-bottom: 16px;">Contact</h6>
                <p style="font-size: 0.85rem; color: rgba(255,255,255,0.6); line-height: 1.7; margin: 0;">
                    <i class="bi bi-geo-alt" style="margin-right: 6px;"></i>Haarlem, The Netherlands<br>
                    <i class="bi bi-envelope" style="margin-right: 6px;"></i>info@haarlemfestival.nl
                </p>
            </div>
        </div>

        <!-- Bottom bar -->
        <div style="border-top: 1px solid rgba(255,255,255,0.1); padding: 20px 0; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 10px;">
            <span style="font-size: 0.8rem; color: rgba(255,255,255,0.4);">&copy; <?= date('Y') ?> The Haarlem Festival. All rights reserved.</span>
            <div style="display: flex; gap: 16px;">
                <a href="#" style="color: rgba(255,255,255,0.4); font-size: 1.1rem; transition: color 0.2s;" title="Facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" style="color: rgba(255,255,255,0.4); font-size: 1.1rem; transition: color 0.2s;" title="Instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" style="color: rgba(255,255,255,0.4); font-size: 1.1rem; transition: color 0.2s;" title="Twitter"><i class="bi bi-twitter-x"></i></a>
            </div>
        </div>
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
