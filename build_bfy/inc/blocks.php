<?php
// phpcs:disable PSR1.Files.SideEffects
function kenza_block_category($categories, $post)
{
    return array_merge(
        $categories,
        array(
            array(
                'slug' => 'kenza-blocks-reports',
                'title' => __('Kenza Insights', 'kenza'),
            ),
            array(
                'slug' => 'kenza-blocks-cases',
                'title' => __('Kenza Cases', 'kenza'),
            ),
            array(
                'slug' => 'kenza-blocks',
                'title' => __('Kenza General', 'kenza'),
            ),
        )
    );
}

add_filter('block_categories', 'kenza_block_category', 10, 2);

global $version;
global $template_location_uri;
global $environment;

if (!function_exists('register_block_type')) {
        // Gutenberg is not active.
        return;
}

add_action('admin_enqueue_scripts', 'template_path', 1);

function template_path()
{
    global $template_location_uri;
    echo "<script>";
    echo "var kenzaData = kenzaData || {};";
    echo "kenzaData.templateLocationURI = '".$template_location_uri."';";
    echo "</script>";
}

function block_scripts()
{
    global $template_location_uri;
    global $version;
    global $environment;
    $ver = null;
    $date = new DateTime();

    if ($environment == 'development') {
            $ver = date_timestamp_get($date);
    }
    wp_register_script(
        'blocks-js',
        $template_location_uri . '/js/blocks-'. $version .'.min.js',
        array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-data', 'wp-date', 'wp-wordcount' ),
        $ver
    );

    wp_register_style(
        'blocks-css',
        $template_location_uri . '/css/editor-'. $version .'.min.css',
        null,
        $ver
    );
}

function kenza_add_page_meta_boxes()
{
    add_meta_box(
        'utm-page-class',
        'Kenza Page attributes',
        'kenza_page_class_meta_box',
        'page',
        'side',
        'high'
    );
}

function kenza_page_class_meta_box()
{
    global $post;

    $accent = esc_attr(get_post_meta($post->ID, 'kenza_page_class', true));
    $inverted = esc_attr(get_post_meta($post->ID, 'kenza_page_inverted', true));

    if (empty($accent)) {
        $accent = '';
    }

    if (empty($inverted)) {
        $inverted = 'no';
    }

    //console_log('inverted value '. $inverted. ' ' .($inverted == 'yes' ? ' selected ' : ' no '));
    wp_nonce_field(basename(__FILE__), 'kenza_page_class_nonce');?>

    <div class="components-base-control editor-post-excerpt__textarea">
        <div class="components-base-control__field">
            <label class="components-base-control__label" for="utm-page-class">Colour accent</label>
            <!-- <select name="utm-page-class" id="utm-page-class">
                <option value="" <?php echo $accent == '' ? 'selected ' : ''?>>
                    None
                </option>
                <option <?php echo $accent == 'kenza-orange' ? 'selected' : '';?> value="kenza-orange">Orange</option>
                <option <?php echo $accent == 'kenza-grey' ? 'selected' : '';?> value="kenza-grey">Grey</option>
                <option <?php echo $accent == 'kenza-green' ? 'selected' : '';?> value="kenza-green">Green</option>
                <option <?php echo $accent == 'kenza-yellow' ? 'selected' : '';?> value="kenza-yellow">Yellow</option>
            </select> -->
        </div>
        <div class="components-base-control__field">
            <label class="components-base-control__label" for="utm-page-inverted">Inverted?</label>
            <select name="utm-page-inverted" id="utm-page-inverted">
                <option <?php echo $inverted == 'yes' ? 'selected' : '';?> value="yes">Yes</option>
                <option <?php echo $inverted == 'no' ? 'selected' : '';?> value="no">No</option>
            </select>
        </div>
    </div>
<?php }

function kenza_save_page_class_meta($post_id, $post)
{
    if (!isset($_POST['kenza_page_class_nonce']) ||
        !wp_verify_nonce($_POST['kenza_page_class_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    $post_type = get_post_type_object($post->post_type);

    if (!current_user_can($post_type->cap->edit_post, $post_id)) {
        return $post_id;
    }

    $new_meta_value = (isset($_POST['utm-page-class']) ? $_POST['utm-page-class'] : '');
    $meta_key = 'kenza_page_class';
    $meta_value = get_post_meta($post_id, $meta_key, true);

    if ($new_meta_value && '' == $meta_value) {
        add_post_meta($post_id, $meta_key, $new_meta_value, true);
    } elseif ($new_meta_value && $new_meta_value != $meta_value) {
        update_post_meta($post_id, $meta_key, $new_meta_value);
    } elseif ('' == $new_meta_value && $meta_value) {
        delete_post_meta($post_id, $meta_key, $meta_value);
    }
}

function kenza_save_page_inverted_meta($post_id, $post)
{
    if (!isset($_POST['kenza_page_class_nonce']) ||
        !wp_verify_nonce($_POST['kenza_page_class_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    $post_type = get_post_type_object($post->post_type);

    if (!current_user_can($post_type->cap->edit_post, $post_id)) {
        return $post_id;
    }

    $new_meta_value = (isset($_POST['utm-page-inverted']) ? $_POST['utm-page-inverted'] : '');
    $meta_key = 'kenza_page_inverted';
    $meta_value = get_post_meta($post_id, $meta_key, true);

    if ($new_meta_value && '' == $meta_value) {
        add_post_meta($post_id, $meta_key, $new_meta_value, true);
    } elseif ($new_meta_value && $new_meta_value != $meta_value) {
        update_post_meta($post_id, $meta_key, $new_meta_value);
    } elseif ('' == $new_meta_value && $meta_value) {
        delete_post_meta($post_id, $meta_key, $meta_value);
    }
}

//Allow page classes
add_action('admin_init', 'kenza_post_meta_boxes_setup');

function kenza_post_meta_boxes_setup()
{
    add_action('add_meta_boxes', 'kenza_add_page_meta_boxes');
    add_action('wp_insert_post', 'kenza_save_page_class_meta', 10, 2);
    add_action('wp_insert_post', 'kenza_save_page_inverted_meta', 10, 2);
}

add_filter('enqueue_block_editor_assets', 'block_scripts', 10);

require $template_location . '/inc/blocks/carousel.php';
require $template_location . '/inc/blocks/casesCreative.php';
require $template_location . '/inc/blocks/casesHeader.php';
require $template_location . '/inc/blocks/casesInformation.php';
require $template_location . '/inc/blocks/casesTitleAndPoints.php';
require $template_location . '/inc/blocks/casesVideo.php';

require $template_location . '/inc/blocks/customAnimation.php';
require $template_location . '/inc/blocks/emailForm.php';
require $template_location . '/inc/blocks/howWeServe.php';
require $template_location . '/inc/blocks/mainForm.php';
require $template_location . '/inc/blocks/openerCTA.php';
require $template_location . '/inc/blocks/ourServicesBlock.php';
require $template_location . '/inc/blocks/ourServices.php';
require $template_location . '/inc/blocks/share.php';
//
require $template_location . '/inc/blocks/reportsAuthorColumn.php';
require $template_location . '/inc/blocks/reportsChapterHeader.php';
require $template_location . '/inc/blocks/reportsHeader.php';
require $template_location . '/inc/blocks/reportsImageQuote.php';
require $template_location . '/inc/blocks/reportsOfferings.php';
require $template_location . '/inc/blocks/reportsPercentageQuote.php';
require $template_location . '/inc/blocks/reportsQuickFacts.php';
require $template_location . '/inc/blocks/reportsSummary.php';
require $template_location . '/inc/blocks/reportsTable.php';
require $template_location . '/inc/blocks/reportsTextAndButton.php';
require $template_location . '/inc/blocks/reportsTitleAndSub.php';
require $template_location . '/inc/blocks/socialShare.php';
require $template_location . '/inc/blocks/sticky.php';

require $template_location . '/inc/blocks/navigation.php';
require $template_location . '/inc/blocks/numberscountingup.php';
