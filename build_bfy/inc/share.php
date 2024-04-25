<?php
global $shareurl;
global $emailtitle;
global $emailtext;
global $tweet;
global $noform;
global $shnum;
global $alt;

if (empty($noform)) {
    $noform = false;
}

if (empty($alt)) {
    $alt = '';
}

if (empty($shnum)) {
    $shnum = '';
}


if (empty($shareurl)) {
    $shareurl = urlencode(get_permalink());
}

if (empty($emailtitle)) {
    $emailtitle = get_kenza_email_share_title();
}

if (empty($emailtext)) {
    $emailtext = get_kenza_email_share_text();
}

if (empty($tweet)) {
    $tweet = urlencode(get_kenza_twitter_share_text());
}

$show = get_kenza_include_share();

if ($show == 1) {
    if (!$noform) {
        ?><input type="checkbox" id="social-share<?php echo $shnum?>" />
        <section class="social-share" data-transition="slideup">
            <label for="social-share<?php echo $shnum?>" class="share">Share</label>

    <?php }?>
<ul class="social-share-menu<?php echo $alt?>">
    <li class="tw cl1">
        <a rel="noopener"
        href="https://twitter.com/intent/tweet?text=<?php echo $tweet?>&url=<?php echo $shareurl?>"
        target="_blank">Twitter</a>
    </li>
    <li class="fb cl2">
        <a rel="noopener" href="https://www.facebook.com/sharer.php?u=<?php echo $shareurl?>"
            target="_blank">Facebook</a>
    </li>
    <li class="in cl3">
        <a rel="noopener" href="https://www.linkedin.com/shareArticle?url=<?php echo $shareurl?>"
            target="_blank">LinkedIn</a>
    </li>
    <li class="mail cl4">
        <a href="mailto:?subject=<?php
            echo $emailtitle
        ?>&body=<?php
            echo $emailtext?>%0D%0A%0D%0A<?php
            echo $shareurl?>">Email</a>
    </li>
</ul>
    <?php
    if (!$noform) {
        echo '</section>';
    }
} ?>