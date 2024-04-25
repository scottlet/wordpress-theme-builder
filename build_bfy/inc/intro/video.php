<?php

$oldpath = get_template_directory_uri();
$path = str_replace('https://kenza.site', '', $oldpath);
?>
<video muted preload="auto" playsinline class="background" crossorigin="anonymous">
    <source src="<?php echo $oldpath; ?>/video/texture_hevc.mp4"
        type="video/mp4; codecs=hevc,hvc1" />
    <source src="<?php echo $oldpath; ?>/video/texture.webm" type="video/webm" />
    <source src="<?php echo $path ?>/video/texture.mp4" type="video/mp4" />
</video>
