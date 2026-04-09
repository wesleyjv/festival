<section class="storytelling-info" aria-labelledby="info-title">
    <div class="info-icon">ℹ️</div>
    <div class="info-content">
        <h3 id="info-title">Additional Information</h3>
        <p>
            <?php
            $infoParagraph = $storiesContent['info_paragraph'] ?? "All storytelling events are suitable for ages 12 and above unless specifically marked as children's events. Tickets can be purchased online or at the venue 30 minutes before each performance. In case of rain, outdoor events will be moved to covered locations nearby.";
            $infoPlain = preg_replace('#</p>\s*<p[^>]*>#i', "\n\n", $infoParagraph);
            $infoPlain = str_replace(['<br>', '<br/>', '<br />'], "\n", $infoPlain);
            echo nl2br(htmlspecialchars(strip_tags($infoPlain), ENT_QUOTES, 'UTF-8'), false);
            ?>
        </p>
    </div>
</section>
