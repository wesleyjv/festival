</main>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
