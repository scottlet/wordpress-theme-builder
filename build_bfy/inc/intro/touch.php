<?php
$oldpath = get_template_directory_uri();
$path = str_replace('https://kenza.site', '', $oldpath); ?>
<video muted preload="auto" playsinline class="touch" width="19" height="19" crossorigin="anonymous">
    <!-- <source src="<?php echo $oldpath; ?>/video/touch_hevc.mp4"
        type="video/mp4; codecs=hevc,hvc1" /> -->
    <source src="<?php echo $oldpath; ?>/video/touch.webm" type="video/webm" />
    <source src="<?php echo $path; ?>/video/touch_big.mp4" type="video/mp4" />
</video>
