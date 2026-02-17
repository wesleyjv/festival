<?php
/**
 * Reusable Button. Expects: $href, $label, $variant ('primary'|'secondary'), $class (optional)
 */
$href = $href ?? '#';
$label = $label ?? 'Button';
$variant = $variant ?? 'primary';
$class = $class ?? '';
?>
<a href="<?php echo htmlspecialchars($href); ?>" class="btn-landing btn-landing--<?php echo htmlspecialchars($variant); ?> <?php echo htmlspecialchars($class); ?>"><?php echo htmlspecialchars($label); ?></a>
