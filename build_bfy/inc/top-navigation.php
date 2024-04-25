<?php
$menuParameters = array(
    'container'       => false,
    'echo'            => false,
    'items_wrap'      => '%3$s',
    'depth'           => 0,
    'theme_location' => 'main-menu-1'
);

$leftNav = wp_nav_menu($menuParameters);
$menuParameters['theme_location'] = 'main-menu-2';
$rightNav = wp_nav_menu($menuParameters);

?>

<nav class="header">
    <a class="kenza" href="/company/">Kenza</a>
    <div class="wrap">
        <section class="left-side">
            <ul>
                <?php echo $leftNav ?>
            </ul>
        </section>
        <section class="right-side">
            <ul>
                <?php echo $rightNav ?>
            </ul>
        </section>
    </div>
</nav>
<div class="mobnav">
    <nav class="mobile">
        <a class="kenza" href="/company/">Kenza</a>
        <input type="checkbox" name="mobile-nav" id="mobile-nav" />
        <label for="mobile-nav" class="opener">
            <span>Open</span>
        </label>
        <div class="mobile-nav">
            <ul>
                <?php echo $leftNav ?>
            </ul>
            <ul>
                <?php echo $rightNav ?>
            </ul>
        </div>
        <label for="mobile-nav">open</label>
    </nav>
</div>
