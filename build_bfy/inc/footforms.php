<?php
$show = get_kenza_hubspot_include_emailform();

if ($show != -1) {
    ?>
    <section class="email-form" data-transition="slideup">
        <article>
            <?php
                addComponent('email', [
                    'sidebar' => $email
                ]);
            ?>
        </article>
    </section>
    <?php
}
?>