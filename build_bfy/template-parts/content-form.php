<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package kenza
 */

global $sectionclass;
global $notext;
global $big;
global $bodyclass;
global $noposter;
$tag = 'h3';
if (! empty($big)) {
    $tag = 'h2';
}
$formtitle = get_kenza_hubspot_mainform_title();
$formtext = get_kenza_hubspot_mainform_text();
$formbutton = get_kenza_hubspot_mainform_button();

if (has_post_thumbnail() && has_kenza_video_for_page()) {
    if (!isset($noposter) || $noposter == false) {
        ?>
    <section class="posterinvert invert" data-transition="slideup" data-transition-include="yes">
        <?php
        the_post_thumbnail();
    }
} else {
    if (!isset($noposter) || $noposter == false) {
        echo '<section class="formbuttons narrow" data-transition="slideup" data-transition-include="yes">';
    }
}

if (empty($notext)) {
    if (! empty($formtitle) && ! empty($formtext)) {
        echo '<div class="aligner">';
    }
    if (! empty($formtitle)) {
        echo '<' . $tag . ' class="formtitle">' . $formtitle . '</' . $tag . '>';
    }
    ?>
    <?php
    if (! empty($formtext)) {?>
        <p>
            <?php echo $formtext ?>
        </p>
        <?php
    }
}?>
<?php
if (! empty($formbutton)) {
    if ($bodyclass === 'company') {
        ?><p>
            <a href="https://kenza.site/wp-content/uploads/2020/06/KENZA_MANIFESTO.pdf" target="_blank" rel="noopener" class="cta"><?php echo $formbutton ?></a>
        </p><?php
    } else {?>
        <p>
            <label class="cta" for="main-form"><?php echo $formbutton ?></label>
        </p>
        <?php
    }
    if (! empty($formtitle) && ! empty($formtext)) {
        echo '</div>';
    }
}?>
</section>
