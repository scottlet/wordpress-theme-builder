<div data-component="background" class="<?php echo $classes ?>" data-options='<?php echo json_encode($options) ?>'>
<?php
require get_template_directory() . '/inc/intro/video.php';
require get_template_directory() . '/inc/intro/touch.php';
?>
    <canvas class="background"></canvas>
<?php
if (!empty($options['logoAnimation'])) {
    ?>
    <div class="lconstrain">
        <div class="logomark">
            <span class="c1"></span>
            <span class="c2"></span>
            <span class="c3"></span>
            <span class="c4"></span>
            <span class="c5"></span>
            <span class="c6"></span>
            <div class="logo">
                <h1>Kenza</h1>
                <span class="k">k</span>
                <span class="e">e</span>
                <span class="n">n</span>
                <span class="z">z</span>
                <span class="a">a</span>
            </div>
        </div>
        <div class="logoextra">
            <span class="e1"></span>
            <span class="e2"></span>
            <span class="e3"></span>
            <span class="e4"></span>
            <span class="e5"></span>
        </div>
    </div>
    <?php
}

if (!empty($options['play'])) { ?>
<section class="play">
    <input type="checkbox" id="inline-social-share" />
    <ul>
        <li class="cl1">
            <a href="#delete" data-action="play-delete" class="delete"><i>Delete</i></a>
        </li>
        <li class="cl2">
            <a href="#rewind" data-action="play-rewind" class="rewind"><i>Rewind</i></a>
        </li>
        <li class="cl3">
            <a href="#record" data-action="play-record"
                class="record"><i class="o">Record</i><i class="r">Stop</i></a>
        </li>
        <li class="play cl4">
            <a href="#play" data-action="play-play"><i>Play</i></a>
        </li>
        <li class="pause cl4">
            <a href="#pause" data-action="play-play"><i>Pause</i></a>
        </li>
        <li>
            <label for="inline-social-share" class="share" data-action="save-share-link-data"><i>Share</i></label>
        </li>
    </ul>
    <?php
    addComponent('share');
    ?></section>
    <?php
}
?>
</div>
