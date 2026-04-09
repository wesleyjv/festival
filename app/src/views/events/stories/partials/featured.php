<section class="storytelling-featured" aria-labelledby="featured-title">
    <div class="storytelling-featured-content">
        <h2 id="featured-title"><?php echo htmlspecialchars($featuredTitle); ?></h2>
        <p><?php echo htmlspecialchars($featuredDescription); ?></p>
        <p>
            From intimate gatherings in historic venues to grand performances under the stars, each story is carefully crafted
            to captivate audiences of all ages and backgrounds.
        </p>
    </div>
    <div class="storytelling-featured-image">
        <img src="<?php echo htmlspecialchars($featuredImage); ?>" alt="Featured Storyteller" />
        <div class="storyteller-name"><?php echo htmlspecialchars($featuredName); ?></div>
        <div class="storyteller-line"></div>
        <div class="storyteller-description">
            Master storyteller with over 20 years of experience bringing Dutch folklore to life
        </div>
    </div>
</section>
