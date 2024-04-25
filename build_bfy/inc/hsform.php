<section class="<?php

$formtitle = get_kenza_hubspot_mainform_form_title();

if (empty($formtitle)) {
    $formtitle = $title;
}

if (empty($inline)) {
    echo 'main-form ';
} else {
    echo 'narrow ';
}
?>hs-form">
    <?php
    if (empty($inline)) {?>
        <div class="inner invert">
        <?php
        if ($formtitle) {
            ?>
            <h3><?php echo wptexturize($formtitle)?></h3>
            <?php
        }
    }?>
    <label class="close closer" for="main-form">close</label>
    <?php

        echo do_shortcode($shortcode)?>
        <div class="our-error">
            <span>
                <?php echo $error ?>
            </span>
        </div>
    <?php
    if (empty($inline)) {
        ?></div><?php
    }
    ?>
</section>
<?php
if (empty($inline)) {?>
    <label class="shade closer" for="main-form">Close</label>
    <?php
}