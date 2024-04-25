<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package kenza
 */

global $sectionclass;
addBannerImage(get_kenza_title(), get_kenza_subtitle(), true);
?>
    <section data-transition="slideup" class="main<?php echo $sectionclass?>">
    <?php
    the_content();
    ?></section>
