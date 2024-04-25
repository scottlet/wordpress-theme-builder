<?php
$type = isset($layout) && $layout === 'services' ? 'service-teasers' : 'teasers';
$args = array(
    'post_type' => $type,
    'posts_per_page' => $count * 2
);

if ($type === 'teasers') {
    $args['category_name'] = $category_name;
}

$the_query = new WP_Query($args);

?>
<?php if ($the_query->have_posts()) {
    $i = 1;
    $alt = '';
    if (isset($alt_class) && $alt_class) {
        $alt = ' class="' . $alt_class .'"';
    }
    ?>
    <div class="crosslinks" data-transition="slideup" data-transition-include="through">
        <?php
        while ($the_query->have_posts()) {
            $the_query->the_post();
            $cls = $i % 2 === 0 ? $alt : '';
            $desktop = has_category('desktop');
            $mobile = has_category('mobile');
            if ($type === 'service-teasers') {
                ?><article<?php echo $cls ?>>
                    <?php get_template_part('template-parts/content', 'services');?>
                </article><?php
            } else {
                if ($desktop && !$mobile) {
                    $cls = ' class="desktop"';
                }

                if (!$desktop && $mobile) {
                    $cls = ' class="tablet"';
                }
                ?><article<?php echo $cls ?>>
                    <?php get_template_part('template-parts/content', 'teaser');?>
                </article><?php
            }

                $i++;
                wp_reset_postdata();
        } ?>
    </div>
<?php }