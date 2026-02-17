<?php
/**
 * Reusable SectionWrapper: max-width centered block with optional background.
 * Expects: $tag (optional, default 'section'), $class (optional, e.g. 'section-wrapper--beige'), $id (optional)
 * Content is passed via slot: capture output before/after or use ob_start. We use a simple pattern: pass $content or require inside.
 * Usage: set $sectionTitle (optional), $sectionClass, $sectionId, then include this partial; the *next* include is the content.
 * Simpler: this partial opens the wrapper; caller includes it with no content, then we need a closing partial. So instead we do:
 * Include section-wrapper-open.php, then content, then section-wrapper-close.php. Or one partial that takes $content as a string.
 * Easiest for PHP: one partial that receives $sectionTitle, $sectionClass, $sectionId and $sectionContent (HTML string) or we use a block.
 * So: section-wrapper.php gets $sectionTitle, $sectionClass (e.g. 'section-wrapper--beige'), $sectionId, $sectionContent.
 * If $sectionContent is not set, we don't output inner content (caller can include this and then output content after, but then we'd need two files for open/close).
 * I'll do: open wrapper in partial, and we have section-wrapper-close.php for the closing tag. So we need two includes. Or one include that echoes $sectionContent.
 * Best: single partial with $sectionTitle, $sectionClass, $sectionId, $sectionContent (optional). If $sectionContent provided, we echo it; else the caller is responsible for outputting content between two partials. Actually in PHP the cleanest is: this file only opens the section; a second file section-wrapper-close.php closes it. Then in the view we do:
 *   <?php $sectionTitle = '...'; $sectionClass = '...'; require 'section-wrapper-open.php'; ?>
 *   ... content ...
 *   <?php require 'section-wrapper-close.php'; ?>
 * So I'll create section-wrapper-open.php and section-wrapper-close.php. Or one component that accepts $sectionContent as a string (caller can build it). I'll go with open/close for simplicity so we don't need to build HTML strings.
 */
$sectionClass = $sectionClass ?? '';
$sectionId = $sectionId ?? '';
$sectionTitle = $sectionTitle ?? '';
?>
<section<?php echo $sectionId ? ' id="' . htmlspecialchars($sectionId) . '"' : ''; ?> class="section-wrapper <?php echo htmlspecialchars($sectionClass); ?>">
<?php if ($sectionTitle !== ''): ?>
  <h2 class="section-title"><?php echo htmlspecialchars($sectionTitle); ?></h2>
<?php endif; ?>
<div class="section-wrapper__inner">
