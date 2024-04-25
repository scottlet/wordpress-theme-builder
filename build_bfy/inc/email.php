<?php

$overridetitle = get_kenza_hubspot_emailform_title();
$email_subtitle = get_kenza_hubspot_emailform_subtitle();
$content = json_decode(\ojoho\get_dynamic_sidebar($sidebar));

if (!empty($content)) {
    ?>
    <div class="hs-form">
        <h2><?php echo empty($overridetitle) ? $content->title : $overridetitle?></h2>
        <?php if (!empty($email_subtitle)) {
            echo '<p>' . $email_subtitle . '</p>';
        }

        echo do_shortcode($content->contents) ?>
        <div class="our-error">
            <span>
                <?php echo $content->error ?>
            </span>
        </div>
    </div>
    <?php
}?>