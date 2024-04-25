<?php
/**
 * The template for displaying the footer
 *
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package kenza
 */
addComponent('cookie-bar');
?>
<!-- <article class="hs-chat">
    <?php
    $pagecat = get_the_category();
    $pagecatname = '';
    if (!empty($pagecat)) {
        $pagecatname = $pagecat[0]->name;
    }
    addComponent('chat', [
        'sidebar' => $pagecatname == 'career' ? 'kenza_jobs_chat' : 'kenza_chat'
    ]);
    ?>
</article> -->
<footer class="invert">
    <?php
    $socialMenuName = \ojoho\get_menu_name('social');
    $menuParameters = array(
        'container'       => false,
        'echo'            => false,
        'fallback_cb'     => false,
        'items_wrap'      => '<h5>'.$socialMenuName.'</h5><ul>%3$s</ul>',
        'depth'           => 0,
        'theme_location'  => 'social'
    );
    global $classno;
    $classno = 0;
    $socialMenu = wp_nav_menu($menuParameters);
    $menuParameters['theme_location'] = 'legal';
    $menuParameters['items_wrap'] = '<ul>%3$s</ul>';
    $legalMenu = wp_nav_menu($menuParameters);
    ?>
    <h4>Kenza</h4>
    <nav class="footer">
        <div class="wrap">
            <section class="left-side">
                <?php
                if (!empty($socialMenu)) {
                    echo $socialMenu;
                }
                ?>
            </section>
            <section class="right-side">
                <?php
                echo $legalMenu ?>
            </section>
        </div>

    </nav>

</footer>

<?php wp_footer(); ?>
<?php dynamic_sidebar('kenza_scripts_footer'); ?>
</body>
</html>
