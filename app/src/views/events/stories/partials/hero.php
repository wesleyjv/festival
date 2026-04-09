<section class="jazz-hero storytelling-jazz-hero" style="background-image:url('<?php echo htmlspecialchars($heroImage, ENT_QUOTES, 'UTF-8'); ?>');background-size:cover;background-position:center;">
    <div class="jazz-hero__overlay"></div>
    <div class="jazz-hero__content storytelling-jazz-hero__content">
        <span class="storytelling-jazz-hero__kicker">Haarlem Festival Storytelling</span>
        <h1><?php
                if ($heroTitleCustom !== '') {
                    echo htmlspecialchars(strip_tags($heroTitleCustom), ENT_QUOTES, 'UTF-8');
                } else {
                    ?>The Art of <span class="highlight">Storytelling</span><?php
                }
            ?></h1>
            <p class="storytelling-jazz-hero__description"><?php
                if ($heroDescriptionCustom !== '') {
                    $descPlain = preg_replace('#</p>\s*<p[^>]*>#i', "\n\n", $heroDescriptionCustom);
                    $descPlain = str_replace(['<br>', '<br/>', '<br />'], "\n", $descPlain);
                    echo nl2br(htmlspecialchars(strip_tags($descPlain), ENT_QUOTES, 'UTF-8'), false);
                } else {
                    echo htmlspecialchars(
                        'Experience the magic of oral tradition as master storytellers weave tales that transport you through time and imagination. From ancient myths to contemporary narratives, discover the power of stories that connect us all.',
                        ENT_QUOTES,
                        'UTF-8'
                    );
                }
            ?></p>
        <div class="storytelling-jazz-hero__actions">
            <a href="#schedule" class="storytelling-jazz-hero__btn">View Event Schedule</a>
            <a href="#featured-title" class="storytelling-jazz-hero__btn storytelling-jazz-hero__btn--ghost">Featured Storyteller</a>
        </div>
    </div>
</section>
