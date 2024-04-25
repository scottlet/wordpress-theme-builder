<?php
// phpcs:disable PSR1.Files.SideEffects
namespace ojoho;

@ini_set('upload_max_size', '512M');
@ini_set('post_max_size', '256M');
@ini_set('max_execution_time', '300');

$_ojoho_prefix = '';
$classno = 0;

function set_prefix($text)
{
    global $debug;
    if ($debug) {
        error_log('Running set_prefix');
    }
    global $_ojoho_prefix;
    $_ojoho_prefix = $text;
}

function __($text)
{
    global $debug;
    if ($debug) {
        error_log('Running __');
    }
    if (function_exists('qtranxf_gettext')) {
        return qtranxf_gettext($text);
    }
    return $text;
}
function build_html($opts)
{
    global $debug;
    if ($debug) {
        error_log('Running build_html');
    }

    extract($opts);

    if ($empty) {
        $html = '';
    }
    $width = 'width: '.$width.';';
    $labelhtml = '';
    if ($label) {
        $labelhtml = '<h3><label class="i18n-multilingual-display" for="$name_$num">$labelhtml</label></h3>';
    }
    if ($helptext) {
        $labelhtml .= '<p style="color:#666;">$helptext</p>';
    }
    if ($custom == false) {
        $html .= '<div>'.$labelhtml;
        $html .= '    <input placeholder="$placeholder" name="$name'.$postnamesuffix.'" ';
        $html .= 'style="'.$width.'padding:10px; border:solid 1px #ddd; box-shadow:inset 0 1px 2px rgba(0,0,0,.07)" ';
        $html .= 'id="$name_$num" spellcheck="true" class="'.$i18n.'" value="$value" />';
        $html .= '<br /><br /></div>';
    }
    $html = str_replace('$placeholder', __($placeholder), $html);
    $html = str_replace('$name', $name, $html);
    $html = str_replace('$labelhtml', __($label), $html);
    $html = str_replace('$label', __(strip_tags($label)), $html);
    $html = str_replace('$helptext', __($helptext), $html);
    $html = str_replace('$value', $_stitle, $html);

    return $html;
}

function helptext($opts)
{
    global $debug;
    if ($debug) {
        error_log('Running helptext');
    }
    global $post;
    extract($opts);
    if (! in_array($post->post_type, $type, true)) {
        return;
    }
    array_walk($text, '__');
    echo str_replace('$text', join('<br />', $text), $html);
}

function get_url($text)
{
    $re = '/https?\:\/\/|mailto:/';
    if (preg_match($re, $text) !== 1) {
        return 'https://'.$text;
    }
    return $text;
}

function custom_post($opts)
{
    $type = $opts['type'];
    global $post;
    if (! in_array($post->post_type, $type, true)) {
        global $debug;
        if ($debug) {
            error_log('Returning from custom_post');
        }
        return;
    }
    if (isset($opts['onlyin'])) {
        $pageTemplate = get_post_meta($post->ID, '_wp_page_template', true);
        $onlyin = $opts['onlyin'];

        if (\is_array($onlyin) && !\in_array($pageTemplate, $onlyin)) {
            return;
        }
        if (!\is_array($onlyin) && $opts['onlyin'] !== $pageTemplate) {
            return;
        }
    }
    global $debug;
    if ($debug) {
        error_log('Running custom_post');
    }
    global $_ojoho_prefix;
    $name = $opts['name'];
    $label = $opts['label'];
    $html = isset($opts['html']) ? $opts['html'] : '';
    $class = isset($opts['class']) ? $opts['class'] : '';
    $date = isset($opts['date']) ? $opts['date'] : false;
    $width = isset($opts['width']) ? $opts['width'] : '100%';
    $helptext = isset($opts['helptext']) ? $opts['helptext'] : '';
    $titlesh = $name;
    $placeholder =  isset($opts['placeholder']) ?
        $opts['placeholder'] : '[:en]Enter $label here[:de]$label hier eingeben[:]';
    $i18n = 'wp-editor-area multilanguage-input';
    if (isset($opts['no-i18n'])) {
        $i18n = '';
        $placeholder =  'Enter $label here';
    }

    if (isset($opts['label'])) {
        $label = $opts['label'];
    }
    if (isset($opts['helptext'])) {
        $helptext = $opts['helptext'];
    }
    $name = $_ojoho_prefix.'_'.$name;
    $_stitle = get_post_meta($post->ID, '_'.$name, true);

    if ($date) {
        $_stitle = str_replace(' ', 'T', $_stitle);
    }
    $postnamesuffix = '[]';
    $custom = !empty($html);
    $originalhtml = $html;
    if (isset($opts['multi'])) {
        $count = $opts['count'];
        $html = '';
        for ($i = 0; $i<$count; $i++) {
            $empty = false;
            if ($i == 0) {
                $empty = true;
            }
            $value = $_stitle;
            if (is_array($value)) {
                $value = $value[$i];
            }
            $html .= build_html(
                array(
                    'class'          => $class,
                    'html'           => $originalhtml,
                    'postnamesuffix' => $postnamesuffix,
                    'i18n'           => $i18n,
                    'placeholder'    => $placeholder,
                    'name'           => $name,
                    'label'          => $label,
                    '_stitle'        => esc_attr($value),
                    'empty'          => $empty,
                    'custom'         => $custom,
                    'helptext'       => $helptext,
                    'width'         => $width
                )
            );
            $html = str_replace('$num', $i+1, $html);
        }
    } else {
        $postnamesuffix = '';
        $empty = empty($html);
        $html = build_html(
            array(
                'html'           => $originalhtml,
                'postnamesuffix' => $postnamesuffix,
                'i18n'           => $i18n,
                'placeholder'    => $placeholder,
                'name'           => $name,
                'label'          => $label,
                '_stitle'        => esc_attr($_stitle),
                'empty'          => $empty,
                'custom'         => $custom,
                'helptext'       => $helptext,
                'width'         => $width
            )
        );
        $html = str_replace('$num', '', $html);
    }
    echo '<fieldset>' . $html . '</fieldset>';
    unset($opts, $html);
    //do_action('edit_form_after_'.$name, $post);
}

function custom_crossref_post($opts)
{
    global $post;
    $type = $opts['type'];
    $single = isset($opts['single']) ? $opts['single'] : null;
    $order = isset($opts['order']) ? $opts['order'] : 'ASC';
    $orderby = isset($opts['orderby']) ? $opts['orderby'] : 'title';
    $helptext = isset($opts['helptext']) ? $opts['helptext'] : '';
    $class = isset($opts['class']) ? $opts['class'] : '';
    if (! in_array($post->post_type, $type, true)) {
        global $debug;
        if ($debug) {
            error_log('Returning from custom_crossref_post');
        }
        return;
    }
    if (isset($opts['onlyin'])) {
        $pageTemplate = get_post_meta($post->ID, '_wp_page_template', true);
        $onlyin = $opts['onlyin'];

        if (\is_array($onlyin) && !\in_array($pageTemplate, $onlyin)) {
            return;
        }
        if (!\is_array($onlyin) && $opts['onlyin'] !== $pageTemplate) {
            return;
        }
    }
    global $debug;
    if ($debug) {
        error_log('Running custom_crossref_post');
    }
    $original_post = $post;
    global $_ojoho_prefix;
    $name = $opts['name'];
    $subreference = $opts['subreference'];
    $html = '<div>';
    if (isset($opts['html'])) {
        $html = $opts['html'];
    }
    $titlesh = $name;
    $label = 'Speaker featured in these events (+ctrl to select more than one)';
    if (isset($opts['label'])) {
        $label = $opts['label'];
        $html .= '<h3><label class="i18n-multilingual-display" for="$name_$num">$labelhtml</label></h3>';
    }
    if (isset($opts['helptext'])) {
        $html .= '<p style="color:#666;">$helptext</p>';
    }
    $placeholder = '[:en]Enter $label here[:de]$label hier eingeben[:]';
    if (isset($opts['placeholder'])) {
        $placeholder = $opts['placeholder'];
    }
    $name = $_ojoho_prefix.'_'.$name;
    $_stitle = get_post_meta($post->ID, '_'.$name, true);
    $_stitle = $_stitle ? $_stitle : [];
    if (! is_array($_stitle)) {
        $_stitle = (array)$_stitle;
    }
    if (empty($html)) {
        $html = '';
    }
    $args = array(
        'post_type' => $subreference,
        'posts_per_page' => 100,
        'orderby' => 'title',
        'order' => $order,
        'orderby' => $orderby
    );
    $arr = new \WP_Query($args);
    if ($arr->have_posts()) {
        $html .= '<select '.(!$single ? 'multiple ' :'').''.(!$class ? '' :'class="' . $class . '" ').'name="$name[]"';
        $html .= ' id="$name">';
        if (isset($opts['placeholder'])) {
            $selected = '';
            if (empty($_stitle)) {
                $selected = 'selected="selected" ';
            }
            $html .= '<option '. $selected .'disabled="disabled">' . $placeholder . '</option>';
        }
        if (isset($opts['allowempty'])) {
            $html .= '<option value="">None</option></option>';
        }
        while ($arr->have_posts()) {
            $arr->the_post();
            $html .= '    <option value="' . get_the_ID() . '"';
            $html .= ((in_array(get_the_ID(), $_stitle)) ? ' selected="selected"' : '') . '>' . get_the_title();
            $html .= '</option>';
        }
        $html .=  '</select>';
    }
    $html .=  '</div>';
    setup_postdata($GLOBALS['post'] =& $original_post);

    $html = str_replace('$placeholder', __($placeholder), $html);
    $html = str_replace('$name', $name, $html);
    $html = str_replace('$labelhtml', __($label), $html);
    $html = str_replace('$helptext', __($helptext), $html);

    echo '<fieldset>' . $html . '</fieldset>';
    unset($arr, $args, $html, $_stitle);
    //do_action('edit_form_after_'.$name, $post);
}

function get_custom_post($type, $name, $array = false)
{
    global $debug;
    if ($debug) {
        error_log('Running get_custom_post');
    }
    global $post;
    if (! in_array($post->post_type, $type, true)) {
        return;
    }
    global $_ojoho_prefix;
    $name = $_ojoho_prefix.'_'.$name;
    $_stitle = get_post_meta($post->ID, '_'.$name, true);
    if ($array) {
        $_stitle = (array) $_stitle;
    }

    return $_stitle;
}

function save_custom_post($type, $name, $update, $isarray = false, $ishtml = false, $checkbox = false)
{
    global $debug;
    if ($debug) {
        error_log('Running save_custom_post');
    }

    global $post;
    global $post_ID;
    if (!isset($post) || !isset($post_ID)) {
        return;
    }

    if (! in_array($post->post_type, $type, true)) {
        return;
    }
    if (defined('DOING_AJAX') && DOING_AJAX) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    global $_ojoho_prefix;
    $name = $_ojoho_prefix.'_'.$name;
    $_stitle = '';
    if ($isarray == true) {
        $_stitle = (array) $_POST[$name];
        if (!$ishtml) {
            array_walk($_stitle, 'sanitize_text_field');
        }
    } else {
        if (!isset($_POST[$name]) && !$checkbox) {
            global $debug;
            if ($debug) {
                error_log('no post set.');
            }
            return;
        }

        if ($checkbox) {
            $_stitle = !isset($_POST[$name]) ? -1 : 1;
        } else {
            $_stitle =  $ishtml ? $_POST[$name] : sanitize_text_field($_POST[$name]);

            if (preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}$/", $_stitle)) {
                $_stitle = str_replace('T', ' ', $_stitle);
            }
        }
    }
    if ($update) {
        update_post_meta($post_ID, '_'.$name, $_stitle);
    } elseif ($checkbox) {
        add_post_meta($post_ID, '_'.$name, $_stitle, true);
    } elseif (! empty($_stitle)) {
        add_post_meta($post_ID, '_'.$name, $_stitle, true);
    }
}

function _format_menu($classes, $item, $args)
{
    global $classno;
    $classno++;
    global $post;

    if (!isset($post)) {
        return $classes;
    }

    // Getting the post type of the current post
    $current_post_type = get_post_type_object(get_post_type($post->ID));
    $cat = get_the_category($post);
    if ($cat && $cat[0]) {
        $current_slug = $cat[0]->slug;
    }

    if ($post->post_type === 'page') {
        $current_post_type_slug = $post->post_name;
        if ($post->post_parent) {
            $current_post_type_slug = get_post($post->post_parent)->post_name;
        }
    } elseif ($post->post_type === 'post') {
        $current_post_type_slug = $current_slug;
    } else {
        $current_post_type_slug = $post->post_type;
    }

    // Getting the URL of the menu item
    $menu_slug = strtolower(trim($item->url));
    //console_log($menu_slug . ' | ' . $current_post_type_slug);

    $custom = isset($classes[0]) && $classes[0] !== 'menu-item' ? $classes[0] : null;
    $classes = ['cl'.$classno];
    $classes[] = $custom;

    if (strpos($menu_slug, $current_post_type_slug) !== false) {
        $classes[] = 'selected';
    }
    return $classes;
}


function _remove_menu_classes($var)
{
    return is_array($var) ? array() : '';
}

function _remove_size_attributes($html)
{
    $html = preg_replace('/(width|height)="\d*"\s/', "", $html);
    return $html;
}



function get_menu_name($menuarea)
{

    if (empty($menuarea)) {
        return false;
    }

    $menuareas = get_nav_menu_locations();

    if (!isset($menuareas[$menuarea])) {
        return false;
    }

    $term = get_term($menuareas[$menuarea], 'nav_menu');

    if (!isset($term)) {
        return false;
    }

    return isset($term->name) ? $term->name : false;
}

function get_dynamic_sidebar($bar)
{
    $contents = '';
    ob_start();
    dynamic_sidebar($bar);
    $contents = ob_get_clean();

    return $contents;
}

/**
 * Simple helper to debug to the console
 *
 * @param $data object, array, string $data
 * @param $context string  Optional a description.
 *
 * @return string
 */
function console_log($data, $bt)
{

    global $console_logs;

    if (empty($bt)) {
        $bt = debug_backtrace();
    }
    $my_caller = array_shift($bt);
    $file = $my_caller['file'];
    $line = $my_caller['line'];

    //Buffering to solve problems frameworks, like header() in this and not a solid return.

    if (! empty($file) && ! empty($line)) {
        $console_logs[] =
            'console.log(\'\','
            .json_encode($data)
            .',\'     '
            .basename($file)
            .':'
            . $line
            . '\');';
    } else {
        $console_logs[] =
            'console.log(' . json_encode($data) . ');';
    }

    //$console_logs[] = 'console.log(' . json_encode($data) . ');';
}

function print_log()
{
    global $console_logs;
    if (empty($console_logs)) {
        return '';
    }
    $output = 'console.warn("PHP Log");';
    $output .= implode(' ', $console_logs);
    $output .= 'console.warn("PHP Log end");';
    $output = sprintf('<script>%s</script>', $output);
    echo $output;
}

/**
 * Remove empty paragraphs created by wpautop()
 * @author Ryan Hamilton
 * @link https://gist.github.com/Fantikerz/5557617
 */
function remove_empty_p($content)
{
    $content = force_balance_tags($content);
    return preg_replace('#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content);
}

add_filter('the_content', '\ojoho\remove_empty_p', 20, 1);
add_filter('nav_menu_css_class', '\ojoho\_format_menu', 1, 3);
add_filter('nav_menu_item_id', '\ojoho\_remove_menu_classes', 100, 1);
add_filter('post_thumbnail_html', '\ojoho\_remove_size_attributes', 10);
add_filter('image_send_to_editor', '\ojoho\_remove_size_attributes', 10);
add_action('wp_footer', '\ojoho\print_log');
add_action('admin_footer', '\ojoho\print_log');
