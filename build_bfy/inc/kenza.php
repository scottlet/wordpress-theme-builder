<?php
// phpcs:disable PSR1.Files.SideEffects
/**
 * Custom functions for Kenza website.
 *
 * @link https://ojoho.co/
 *
 * @package kenza
 */

global $version;

register_nav_menus(
    array(
        'main-menu-1' => esc_html__('Main Menu Top Left', 'kenza'),
        'main-menu-2' => esc_html__('Main Menu Top Right', 'kenza'),
        'legal' => esc_html__('Footer Bottom Right', 'kenza'),
        'social' => esc_html__('Social links', 'kenza')
    )
);

function tobase64($int)
{
    $byte = pack('J*', $int);
    return base64_encode($byte);
}

function frombase64($base64)
{
    $byte = base64_decode($base64);
    return unpack('J*', $byte)[1];
}

function get_key()
{
    return rawurlencode(tobase64(rand(0, 1000).time()));
}
function add_query_vars($aVars)
{
    $aVars[] = "q"; // represents the name of the product category as shown in the URL
    return $aVars;
}

// hook add_query_vars function into query_vars
add_filter('query_vars', 'add_query_vars');

function kenza_save_play()
{
    register_rest_route('play/v1', '/save/', array(
        'methods' => 'POST',
        'callback' => 'kenza_save_play_data',
        'permission_callback' => '__return_true'
    ));
}

add_action('rest_api_init', 'kenza_save_play');


function kenza_save_play_data($request)
{
    global $wpdb;

    if (!isset($request['key']) || !isset($request['data']) || !isset($request['nonce'])) {
        return '{}';
    }

    $key = rawurldecode($request['key']);
    $data = $request['data'];
    $response = '{}';

    if (wp_verify_nonce($request['nonce'], 'play_key')) {
        $response = array(
            'shortcode'=>$key,
            'audio'=>$data
        );
        if (kenza_load_play_data($key) === null) {
            $wpdb->insert('kenza_play', $response);
        }
    }

    return rest_ensure_response($response);
}


function kenza_load_play_data($key)
{
    global $wpdb;
    if (!isset($key)) {
        return null;
    }
    console_log($key);
    $songdata = $wpdb->get_row('SELECT * from kenza_play where shortcode = "'.$key.'";');
    return ($songdata !== null) ? $songdata->audio : null;
}

function kenza_add_rewrite_rules($aRules)
{
    $aNewRules = array('play/([^/]+)/?$' => 'index.php?pagename=play&q=$matches[1]');
    $aRules = $aNewRules + $aRules;
    return $aRules;
}

add_filter('rewrite_rules_array', 'kenza_add_rewrite_rules');

if (function_exists('add_theme_support')) {
    add_theme_support('title-tag');
    add_image_size('square-large', 640, 640, true); // name, width, height, crop
    add_image_size('bigger1', 1440, 960, true); // name, width, height, crop
    add_image_size('bigger2', 1920, 1080, true); // name, width, height, crop
    add_image_size('bigger3', 2048, 1200, false);
    add_image_size('bigger4', 1920, 1125, false);
    add_image_size('bigger5', 1440, 844, false);
    add_image_size('bigger6', 1440, 844, false);
    add_image_size('bigger7', 1366, 800, false);
    add_image_size('chapter', 800, 800);
    add_image_size('bigger8', 640, 375, false);
    add_filter('image_size_names_choose', 'my_image_sizes');
    add_theme_support('post-thumbnails', array('clients', 'page', 'post', 'careers', 'teasers', 'service-teasers'));
    add_theme_support('align-wide');
    add_theme_support('wp-block-styles');
    add_theme_support('dark-editor-style');
    add_theme_support('responsive-embeds');
    add_theme_support('editor-color-palette');
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        )
    );
}

function my_image_sizes($sizes)
{
    $addsizes = array(
        "square-large" => __("Large square image")
    );
    $newsizes = array_merge($sizes, $addsizes);
    return $newsizes;
}

/**
 * Set max srcset image width to 1800px
 */
function remove_max_srcset_image_width($max_width)
{
    return false;
}
add_filter('max_srcset_image_width', 'remove_max_srcset_image_width');

add_filter('post_thumbnail_html', 'remove_width_attribute', 10);
add_filter('render_block', 'remove_width_attribute', 10);
add_filter('the_content', 'remove_width_attribute', 10);
add_filter('image_send_to_editor', 'remove_width_attribute', 10);

function remove_width_attribute($html)
{
    $html = preg_replace('/(width|height)="\d*"\s/', "", $html);

    return $html;
}

function console_log($te)
{
    $bt = debug_backtrace();
    \ojoho\console_log($te, $bt);
}

function mce_mod($init)
{
    $init['block_formats'] = 'Headline=h2;Sub Headline=h3;Sub Headline 2=h4;Sub Headline 3=h5;Copy=\
p;Smaller=small;How we serve=article;Vertical center=vcenter';

    //This allows color styles to be inherited from the editor styelsheet.
    unset($init['preview_styles']);


    return $init;
}

add_filter('tiny_mce_before_init', 'mce_mod');

function tiny_mce_add_plugins($plugins)
{
    global $version;
    $plugins['fancyquotes'] = get_template_directory_uri() . '/js/quote-plugin-' . $version . '.min.js';
    return $plugins;
}
function tiny_mce_add_buttons($buttons)
{
    $buttons[] = 'fancyquotes';
    return $buttons;
}
function tiny_mce_new_buttons()
{
    add_filter('mce_external_plugins', 'tiny_mce_add_plugins');
    add_filter('mce_buttons', 'tiny_mce_add_buttons');
}

add_action('init', 'tiny_mce_new_buttons');


function create_post_type()
{
    register_post_type(
        'teasers',
        array(
            'labels'             => array(
                                        'name'          => __('Teasers'),
                                        'singular_name' => __('Teaser'),
                                        'add_new_item'  => __('Add new teaser'),
                                        'edit_item'     => __('Edit teaser')
                                    ),
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'post_name'           => 'teasers',
            'exclude_from_search' => true,
            'show_in_nav_menus'   => false,
            'has_archive'         => false,
            'rewrite'             => false,
            'menu_icon'           => 'dashicons-admin-links',
            'taxonomies'          => array('category'),
            'supports'            => array('title', 'editor', 'thumbnail', 'content')
        )
    );

    register_post_type(
        'service-teasers',
        array(
            'labels'             => array(
                                        'name'          => __('Services'),
                                        'singular_name' => __('Service'),
                                        'add_new_item'  => __('Add new service'),
                                        'edit_item'     => __('Edit service')
                                    ),
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'post_name'           => 'service-teasers',
            'exclude_from_search' => true,
            'show_in_nav_menus'   => false,
            'has_archive'         => false,
            'rewrite'             => false,
            'menu_icon'           => 'dashicons-admin-generic',
            'supports'            => array('title', 'editor', 'thumbnail', 'content')
        )
    );
    register_post_type(
        'careers',
        array(
            'labels'             => array(
                                        'name'          => __('Jobs'),
                                        'singular_name' => __('Job'),
                                        'add_new_item'  => __('Add new job'),
                                        'edit_item'     => __('Edit job')
                                    ),
            'public'              => true,
            'post_name'           => 'jobs',
            'exclude_from_search' => false,
            'show_in_nav_menus'   => false,
            'has_archive'         => false,
            'taxonomies'          => array('category'),
            'menu_icon'           => 'dashicons-paperclip',
            'supports'            => array('title', 'editor', 'summary', 'thumbnail', 'content')
        )
    );

    register_post_type(
        'videos',
        array(
            'labels'             => array(
                                        'name'          => __('Videos'),
                                        'singular_name' => __('Video'),
                                        'add_new_item'  => __('Add new video'),
                                        'edit_item'     => __('Edit video')
                                    ),
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'post_name'           => 'videos',
            'exclude_from_search' => true,
            'show_in_rest'        => true,
            'show_in_nav_menus'   => false,
            'has_archive'         => false,
            'rewrite'             => false,
            'menu_icon'           => 'dashicons-editor-video',
            'supports'            => array('title', 'editor', 'media', 'thumbnail')
        )
    );

    register_post_type(
        'clients',
        array(
          'labels' => array(
            'name' => __('Clients'),
            'singular_name' => __('Client'),
            'add_new_item' => __('Add new client'),
            'edit_item' => __('Edit client')
         ),
          'public' => true,
          'exclude_from_search' => true,
          'show_in_nav_menus' => false,
          'menu_icon' => 'dashicons-businessman',
          'has_archive' => false,
          'taxonomies'  => array('category'),
          'supports' => array('title', 'thumbnail')
        )
    );
}
add_action('init', 'create_post_type');

function kenza_get_post_meta_cb($object, $field_name, $request)
{
    return get_kenza_videos($object[ 'id' ]);
}

function kenza_add_videos_to_endpoint()
{
    register_rest_field(
        'videos',
        '_kenza_page_video',
        array('get_callback'    => 'kenza_get_post_meta_cb')
    );
}

add_action('rest_api_init', 'kenza_add_videos_to_endpoint');

function kenza_fancyquote_shortcode($texts)
{
    $text = '<blockquote class="variant' . $texts['style'] . '">';

    if ($texts['style'] == 3) {
        if (isset($texts['percentage'])) {
            $text .= '<h2><span>' . $texts['percentage'] . '</span><small>%</small></h2>';
        }

        if (isset($texts['quote'])) {
            $text .= '<p>' . $texts['quote'] . '</p>';
        }

        if (isset($texts['source'])) {
            $text .= '<aside class="source">' . $texts['source'] . '</aside>';
        }
    } else {
        if (isset($texts['percentage'])) {
            $text .= '<h2><span>' . $texts['percentage'] . '</span><small>%</small></h2>';
        }

        if (isset($texts['quote'])) {
            $text .= '<div><p>' . $texts['quote'] . '</p></div>';
        }

        if (isset($texts['source'])) {
            $text .= '<aside class="source">' . $texts['source'] . '</aside>';
        }
    }

    if (isset($texts['video'])) {
        $videos = get_kenza_videos($texts['video']);
        $text .= renderVideos($videos);
    }

    if (isset($texts['image'])) {
        $text .= wp_get_attachment_image($texts['image']);
    }

    $text .= '<div class="shade"></div></blockquote>';

    return $text;
}

add_shortcode('fancyquote', 'kenza_fancyquote_shortcode');

function kenza_responsive_shortcode($texts)
{
    $text = '';
    if (isset($texts['mobile']) && !isset($texts['tablet']) && !isset($texts['desktop'])) {
        $text .= '<span class="mobile">' . $texts['mobile'] . '</span>';
    }

    if (!isset($texts['mobile']) && isset($texts['tablet']) && !isset($texts['desktop'])) {
        $text .= '<span class="uptotablet">' . $texts['tablet'] . '</span>';
    }

    if (isset($texts['mobile']) && isset($texts['tablet']) && !isset($texts['desktop'])) {
        $text .= '<span class="mobile">' . $texts['mobile'] . '</span>';
        $text .= '<span class="tablet">' . $texts['tablet'] . '</span>';
    }

    if (isset($texts['mobile']) && !isset($texts['tablet']) && isset($texts['desktop'])) {
        $text .= '<span class="mobile">' . $texts['mobile'] . '</span>';
        $text .= '<span class="tabletplus">' . $texts['desktop'] . '</span>';
    }

    if (!isset($texts['mobile']) && isset($texts['tablet']) && isset($texts['desktop'])) {
        $text .= '<span class="uptotablet">' . $texts['tablet'] . '</span>';
        $text .= '<span class="desktop">' . $texts['desktop'] . '</span>';
    }

    if (isset($texts['mobile']) && isset($texts['tablet']) && isset($texts['desktop'])) {
        $text .= '<span class="mobile">' . $texts['mobile'] . '</span>';
        $text .= '<span class="tablet">' . $texts['tablet'] . '</span>';
        $text .= '<span class="desktop">' . $texts['desktop'] . '</span>';
    }

    return $text;
}

add_shortcode('responsive', 'kenza_responsive_shortcode');

function kenza_is_front_page()
{
    global $wp;
    return empty($wp->request);
}

function kenza_get_category_name()
{
    $cat = get_the_category();

    if (!is_array($cat) || count($cat) === 0) {
        return null;
    }
    return $cat[0]->slug;
}

function kenza_get_category_ID()
{
    $cat = get_the_category();

    if (!is_array($cat)) {
        return null;
    }
    return $cat[0]->cat_ID;
}

function renderVideos(
    $videos,
    $headclasses = '',
    $header = '',
    $tag = '',
    $subtitle = '',
    $small = false,
    $autoplay = true,
    $loop = true
) {
    $ret = '';

    $ret .= '<div class="hidden" data-video=\''.json_encode($videos).'\'>';
    $ret .= '<div data-transition="parallax" class="poster'.$headclasses.'">';
    if ($header || $subtitle) {
        $ret .= doHeaderTitle($header, $tag, $subtitle, $small);
    }

    //console_log($videos['kenza_video_desktop_preview_mpeg']['url']);

    $ret .= '<video class="preview" muted playsinline';
    if ($loop == true) {
        $ret .=' loop';
    }
    if ($autoplay == true) {
        $ret .= ' autoplay';
    }
    $ret .= '></video>';
    $ret .= '<noscript><video class="preview" muted playsinline';
    if ($loop == true) {
        $ret .=' loop';
    }
    if ($autoplay == true) {
        $ret .= ' autoplay';
    }
    $ret .= '>';
    $ret .= '<source type="video/mp4" src="' . $videos['kenza_video_desktop_preview_mpeg']['url'] . '"/>';
    $ret .= '</video></noscript>';
    $ret .= '<a class="scrolldown" href="#down">scroll down</a>';
    $ret .= '</div>';
    $ret .= '</div>';

    return $ret;
}

function addComponent($fileName, $variables = array(), $print = true)
{
    $filePath = null;
    $output = null;

    if (file_exists(get_template_directory() . '/inc/'.$fileName.'.php')) {
        $filePath = get_template_directory() . '/inc/'.$fileName.'.php';
    }
    if (!empty($filePath)) {
        extract($variables);

        ob_start();

        include $filePath;

        $output = ob_get_clean();
    }
    if ($print) {
        print $output;
    }
    return $output;
}

// add tag support to pages
function tags_support_all()
{
    register_taxonomy_for_object_type('category', 'page', 'teaser');
    register_taxonomy_for_object_type('post_tag', 'page');
}

// tag hooks
add_action('init', 'tags_support_all');

function kenza_help()
{
    $helptext = <<<EOH
        Edit this page, publish it and you shall see changes immediately.
        To display teasers on this page, make sure the category for this
        page is checked, for example both the start page and the company
        page should have a category of 'company'. Then go to "teasers" in
        the menu on the left and assign a category of 'company' to the
        two teasers you wish to display.
        If you wish to display different texts for mobile, tablet or desktop
        there is a shortcode "responsive" which can deal with this:
        eg - <br /><code>[responsive mobile="only mobile" tablet="only tablet" desktop="only desktop"]</code>

EOH;
    \ojoho\helptext(
        array(
            'html' => '<h3>Help</h3><p>$text</p>',
            'text' => array(
                $helptext
            ),
            'type' => ['page']
        )
    );
}

add_action('edit_form_after_title', 'kenza_help', 20);

// Custom data fields

function kenza_title()
{
    $helptext = <<<EOH
        This is the H1 page title, visible in most cases in the large page image. It is
        automatically turned into uppercase.
EOH;

    \ojoho\custom_post(
        [
            'type'     => ['page', 'careers'],
            'name'     => 'title',
            'placeholder' => 'Enter page title here',
            'label'    => 'Page title',
            'helptext' => $helptext,
            'no-i18n'  => true
        ]
    );
}

function get_kenza_title()
{
    return do_shortcode(wptexturize(\ojoho\get_custom_post(['page', 'careers'], 'title')));
}

function save_kenza_title($post_ID, $post, $update)
{
    \ojoho\save_custom_post(['page', 'careers'], 'title', $update);
}

add_action('edit_form_after_title', 'kenza_title', 20);
add_action('wp_insert_post', 'save_kenza_title', 20, 3);

function kenza_subtitle()
{
    $helptext = <<<EOH
        This is the H2 page subtitle, visible in most cases under the main title in the large page image. It is
        automatically turned into uppercase. On some pages without a large title, this will be the only title.
EOH;

    \ojoho\custom_post(
        [
            'type'     => ['page', 'careers'],
            'name'     => 'subtitle',
            'placeholder' => 'Enter page subtitle here',
            'label'    => 'Page subtitle',
            'helptext' => $helptext,
            'no-i18n'  => true
        ]
    );
}

function get_kenza_subtitle()
{
    return do_shortcode(wptexturize(\ojoho\get_custom_post(['page', 'careers'], 'subtitle')));
}

function save_kenza_subtitle($post_ID, $post, $update)
{
    \ojoho\save_custom_post(['page', 'careers'], 'subtitle', $update);
}

add_action('edit_form_after_title', 'kenza_subtitle', 20);
add_action('wp_insert_post', 'save_kenza_subtitle', 20, 3);

function kenza_opening_sentence()
{
    $helptext = <<<EOH
        This is the first sentence on the services page - this is bigger and in a box slightly overlapping the image
        at the top of the page.
EOH;

    \ojoho\custom_post(
        [
            'type'     => ['page'],
            'name'     => 'opening_sentence',
            'onlyin'   => 'page-service.php',
            'placeholder' => 'Enter page opening sentence here',
            'label'    => 'Page opening sentence',
            'helptext' => $helptext,
            'no-i18n'  => true
        ]
    );
}

function get_kenza_opening_sentence()
{
    return do_shortcode(wptexturize(\ojoho\get_custom_post(['page'], 'opening_sentence')));
}

function save_kenza_opening_sentence($post_ID, $post, $update)
{
    \ojoho\save_custom_post(['page'], 'opening_sentence', $update);
}

add_action('edit_form_after_title', 'kenza_opening_sentence', 20);
add_action('wp_insert_post', 'save_kenza_opening_sentence', 20, 3);

function getKey($videotype, $vtype)
{
    return 'kenza_video_' . $videotype . '_' . strtolower($vtype);
}

function kenza_video_selector_builder($opts)
{
    extract($opts);
    wp_enqueue_media();

    $fieldname = getKey($videotype, $vtype);
    $video_id = '';
    $url = '';
    $name = '';
    if (!empty($savedVideos[$fieldname])) {
        $video = $savedVideos[$fieldname];
        $video_id = $video['video_id'];
        $url = $video['url'];
        $name = $video['name'];
    }
    ?><fieldset class="k_upload" style="position: relative; padding-left: 320px; padding-top: 60px;">
    <?php
    if (! empty($vname)) {
        ?>
        <h3 style="position: absolute; left: 0;top: 0;"><?php echo $vname?></h3>
        <?php
    }
    if (! empty($mainhelp)) {
        ?>
        <p style="position: absolute; left: 0; top: 30px;color:#666"><?php echo $mainhelp?></p>
        <?php
    }
    ?>
        <video
            mute
            autoplay
            loop
            class='image-preview'
            src='<?php echo $url?>'
            width='300'
            height='125'
            style='border:1px solid #999;max-height: 125px; width: 300px;position: absolute; top: 80px; left: 0;'
        >


        </video>
        <h4><?php echo $vtype?> video: <span class="vname"><?php echo $name?></span></h4>
        <?php
        if (! empty($shorthelp)) {
            ?>
            <p style="color:#666"><?php echo $shorthelp?>
            <?php
            if ($vtype === 'WEBM') {
                ?><br/>Video preview for WEBM does not work in Safari
                <?php
            }?>
            <?php
            if ($vtype === 'HEVC') {
                ?><br/>Video preview for HEVC does not work in Chrome
                <?php
            }?></p>
            <?php
        }?>
        <input class="upload_video_button button" type="button" value="<?php
            empty($video) ? _e('Add video') : _e('Change video');
        ?>" /><br/>
        <input class="clear_video_button button" type="button" value="<?php _e('Clear video'); ?>" /><br/>
        <input type='hidden' name='<?php echo $fieldname?>' value='<?php echo $video_id; ?>'>
    </fieldset><?php
}

$videos = [
    [
        'videotype'   => 'desktop_preview',
        'vname'       => 'Preview video loop for desktop',
        'mainhelp'    => 'Add video loop here. At the absolute minimum needs an MPEG (mp4) video in HD',
        'shorthelp'   => 'Most compatible file format, but not most efficient.',
        'vtype'       => 'MPEG'
    ],
    [
        'videotype'   => 'desktop_preview',
        'vtype'       => 'WEBM',
        'shorthelp'   => 'Better file compression than MPEG, used by Chrome and Firefox'
    ],
    [
        'videotype'   => 'desktop_preview',
        'vtype' => 'HEVC',
        'shorthelp'   => 'Better file compression than anything, used by IE, Safari, iOS when hardware decoding allows'
    ],
    [
        'videotype'   => 'mobile_preview',
        'vname'       => 'Preview video loop for mobile',
        'mainhelp'    => 'Add video loop here. At the absolute minimum needs an MPEG (mp4) video in 720p',
        'shorthelp'   => 'Most compatible file format, but not most efficient.',
        'vtype'       => 'MPEG'
    ],
    [
        'videotype'   => 'mobile_preview',
        'vtype'       => 'WEBM',
        'shorthelp'   => 'Better file compression than MPEG, used by Chrome and Firefox'
    ],
    [
        'videotype'   => 'mobile_preview',
        'vtype' => 'HEVC',
        'shorthelp'   => 'Better file compression than anything, used by IE, Safari, iOS when hardware decoding allows'
    ],
    [
        'videotype'   => 'desktop',
        'vname'       => 'Video for desktop (optional)',
        'mainhelp'    => 'Add If you want the video player on the page. At the absolute minimum needs an MPEG (mp4) \
        video in HD',
        'shorthelp'   => 'Most compatible file format, but not most efficient.',
        'vtype'       => 'MPEG'
    ],
    [
        'videotype'   => 'desktop',
        'vtype'       => 'WEBM_HDR',
        'shorthelp'   => 'Chrome and Firefox on Windows can play HDR VP9 video'
    ],
    [
        'videotype'   => 'desktop',
        'vtype'       => 'WEBM',
        'shorthelp'   => 'Better file compression than MPEG, used by Chrome and Firefox'
    ],
    [
        'videotype'   => 'desktop',
        'vtype' => 'HEVC',
        'shorthelp'   => 'Better file compression than anything, used by IE, Safari, iOS when hardware decoding allows'
    ],
    [
        'videotype'   => 'mobile',
        'vname'       => 'Video for mobile (optional)',
        'mainhelp'    => 'Add If you want the video player on the page. At the absolute minimum needs an MPEG (mp4) \
        video in 720p',
        'shorthelp'   => 'Most compatible file format, but not most efficient.',
        'vtype'       => 'MPEG'
    ],
    [
        'videotype'   => 'mobile',
        'vtype'       => 'WEBM',
        'shorthelp'   => 'Better file compression than MPEG, used by Chrome and Firefox'
    ],
    [
        'videotype'   => 'mobile',
        'vtype' => 'HEVC',
        'shorthelp'   => 'Better file compression than anything, used by IE, Safari, iOS when hardware decoding allows'
    ]

];

function kenza_video_selector()
{
    global $videos;
    global $post;

    if (! in_array($post->post_type, [ 'videos' ], true)) {
        return;
    }

    $savedVideos = get_post_meta($post->ID, '_kenza_page_video', true);

    foreach ($videos as $video) {
        $video['savedVideos'] = $savedVideos;
        kenza_video_selector_builder($video);
    }
}

function get_kenza_video()
{
    return \ojoho\get_custom_post(['videos'], 'page_video');
}

function save_kenza_video_selector($post_ID, $post, $update)
{
    global $videos;

    if (!isset($post) || !isset($post_ID)) {
        return;
    }

    if (! in_array($post->post_type, ['videos'], true)) {
        return;
    }
    if (defined('DOING_AJAX') && DOING_AJAX) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    $values = [];
    foreach ($videos as $video) {
        $key = getKey($video['videotype'], $video['vtype']);
        if (! empty($_POST[$key])) {
            $video = $_POST[$key];
            $url = wp_get_attachment_url($video);
            $name = basename(get_attached_file($video));

            $values[$key] = [
                'video_id' => $video,
                'url' => $url,
                'name' => $name
            ];
        }
    }

    if ($update) {
        update_post_meta($post_ID, '_kenza_page_video', $values);
    } elseif (! empty($values)) {
        add_post_meta($post_ID, '_kenza_page_video', $values, true);
    }
}

add_action('edit_form_after_title', 'kenza_video_selector', 20);

add_action('wp_insert_post', 'save_kenza_video_selector', 20, 3);

function kenza_video_for_page()
{
    $helptext = <<<EOH
        Choose the video loop or video for this page
EOH;

    \ojoho\custom_crossref_post(
        [
            'type'     => ['page', 'careers'],
            'name'     => 'video_for_page',
            'subreference' => 'videos',
            'placeholder' => 'Choose video/loop',
            'label'    => 'Video loop or video for this page',
            'helptext' => $helptext,
            'class'    => 'videoc',
            'no-i18n'  => true,
            'single'   => true,
            'allowempty'    => true
        ]
    );
}

function get_kenza_videos($id)
{
    return get_post_meta($id, '_kenza_page_video', true);
}

function get_kenza_video_for_page()
{
    $vid = \ojoho\get_custom_post(['page', 'careers'], 'video_for_page');
    if (is_array($vid) && count($vid) !== 0) {
        return get_kenza_videos($vid[0]);
    }

    return [];
}

function has_kenza_video_for_page()
{
    $vid = \ojoho\get_custom_post(['page', 'careers'], 'video_for_page');
    return empty($vid) ? false : true;
}

function save_kenza_video_for_page($post_ID, $post, $update)
{
    \ojoho\save_custom_post(['page', 'careers'], 'video_for_page', $update, true);
}

add_action('edit_form_after_title', 'kenza_video_for_page', 20);
add_action('wp_insert_post', 'save_kenza_video_for_page', 20, 3);

function kenza_copy_subtitle()
{
    $helptext = <<<EOH
        Enter main copy sub headline
EOH;

    \ojoho\custom_post(
        [
            'type'     => ['page'],
            'onlyin'   => ['page-transform.php', 'page-reports.php', 'page-contact.php'],
            'name'     => 'copy_subtitle',
            'placeholder' => 'Enter sub headline',
            'label'    => 'Enter sub headline',
            'helptext' => $helptext,
            'no-i18n'  => true
        ]
    );
}

function get_kenza_copy_subtitle()
{
    return do_shortcode(wptexturize(\ojoho\get_custom_post(['page'], 'copy_subtitle')));
}

function save_kenza_copy_subtitle($post_ID, $post, $update)
{
    \ojoho\save_custom_post(['page'], 'copy_subtitle', $update);
}

add_action('edit_form_after_title', 'kenza_copy_subtitle', 20);
add_action('wp_insert_post', 'save_kenza_copy_subtitle', 20, 3);

function kenza_job_reference()
{
    $helptext = <<<EOH
        This is the job reference that identifies this position in HubSpot responses.
EOH;

    \ojoho\custom_post(
        [
            'type'     => ['careers'],
            'name'     => 'job_reference',
            'placeholder' => 'Enter unique job reference here',
            'label'    => 'Job reference',
            'helptext' => $helptext,
            'no-i18n'  => true
        ]
    );
}

function get_kenza_job_reference()
{
    return \ojoho\get_custom_post(['careers'], 'job_reference');
}

function save_kenza_job_reference($post_ID, $post, $update)
{
    \ojoho\save_custom_post(['careers'], 'job_reference', $update);
}

add_action('edit_form_after_title', 'kenza_job_reference', 20);
add_action('wp_insert_post', 'save_kenza_job_reference', 20, 3);

function kenza_no_positions()
{
    $helptext = <<<EOH
        Enter text displayed when there are no open positions in a category
EOH;

    \ojoho\custom_post(
        [
            'type'     => ['page'],
            'onlyin'   => 'page-careers.php',
            'name'     => 'no_positions',
            'placeholder' => 'Enter text',
            'label'    => 'No positions',
            'helptext' => $helptext,
            'no-i18n'  => true
        ]
    );
}

function get_kenza_no_positions()
{
    return do_shortcode(wptexturize(\ojoho\get_custom_post(['page'], 'no_positions')));
}

function save_kenza_no_positions($post_ID, $post, $update)
{
    \ojoho\save_custom_post(['page'], 'no_positions', $update);
}

add_action('edit_form_after_title', 'kenza_no_positions', 20);
add_action('wp_insert_post', 'save_kenza_no_positions', 20, 3);

function kenza_content_title()
{
    $helptext = 'The Worpress Editor. For more help on using this, go ';
    $helptext .= '<a target="_blank" ';
    $helptext .= 'href="https://make.wordpress.org/support/user-manual/content/editors/visual-editor/">here</a>';
    \ojoho\helptext(
        array(
            'html' => '<h3>Page content</h3><p>$text</p>',
            'text' => [$helptext],
            'type' => ['page']
        )
    );
}

add_action('edit_form_after_title', 'kenza_content_title', 20);

function kenza_hubspot_mainform_title()
{
    \ojoho\custom_post(
        [
            'type'        => ['page', 'careers'],
            'name'        => 'hubspot_mainform_title',
            'placeholder' => 'Enter form title here',
            'label'       => 'HubSpot main form opener title',
            'helptext'    => 'Most pages have a main form. This is the title that appears above the form button.',
            'no-i18n'     => true
        ]
    );
}

function get_kenza_hubspot_mainform_title()
{
    return do_shortcode(wptexturize(\ojoho\get_custom_post(['page', 'careers'], 'hubspot_mainform_title')));
}

function save_kenza_hubspot_mainform_title($post_ID, $post, $update)
{
    \ojoho\save_custom_post(['page', 'careers'], 'hubspot_mainform_title', $update);
}

add_action('edit_form_after_editor', 'kenza_hubspot_mainform_title', 20);
add_action('wp_insert_post', 'save_kenza_hubspot_mainform_title', 20, 3);

function kenza_hubspot_mainform_text()
{
    \ojoho\custom_post(
        [
            'type'        => ['page', 'careers'],
            'name'        => 'hubspot_mainform_text',
            'placeholder' => 'Enter optional form text here',
            'label'       => 'HubSpot main form opener text',
            'helptext'    => 'Some pages have text under the title of their main form. Leave blank if not needed.',
            'no-i18n'     => true
        ]
    );
}

function get_kenza_hubspot_mainform_text()
{
    return do_shortcode(wptexturize(\ojoho\get_custom_post(['page', 'careers'], 'hubspot_mainform_text')));
}

function save_kenza_hubspot_mainform_text($post_ID, $post, $update)
{
    \ojoho\save_custom_post(['page', 'careers'], 'hubspot_mainform_text', $update);
}

add_action('edit_form_after_editor', 'kenza_hubspot_mainform_text', 20);
add_action('wp_insert_post', 'save_kenza_hubspot_mainform_text', 20, 3);

function kenza_hubspot_mainform_button()
{
    \ojoho\custom_post(
        [
            'type'        => ['page', 'careers'],
            'name'        => 'hubspot_mainform_button',
            'placeholder' => 'Enter CTA text here',
            'label'       => 'HubSpot main form opener CTA text',
            'helptext'    => 'Text of the form CTA',
            'no-i18n'     => true
        ]
    );
}

function get_kenza_hubspot_mainform_button()
{
    return \ojoho\get_custom_post(['page', 'careers'], 'hubspot_mainform_button');
}

function save_kenza_hubspot_mainform_button($post_ID, $post, $update)
{
    \ojoho\save_custom_post(['page', 'careers'], 'hubspot_mainform_button', $update);
}

add_action('edit_form_after_editor', 'kenza_hubspot_mainform_button', 20);
add_action('wp_insert_post', 'save_kenza_hubspot_mainform_button', 20, 3);

function kenza_hubspot_mainform_form_title()
{
    \ojoho\custom_post(
        [
            'type'        => ['page', 'careers'],
            'name'        => 'hubspot_mainform_form_title',
            'placeholder' => 'Enter form title here',
            'label'       => 'HubSpot main form title',
            'helptext'    => 'Most pages have a main form. This is the title that appears above the form itself when it
             slides open.',
            'no-i18n'     => true
        ]
    );
}

function get_kenza_hubspot_mainform_form_title()
{
    return do_shortcode(wptexturize(\ojoho\get_custom_post(['page', 'careers'], 'hubspot_mainform_form_title')));
}

function save_kenza_hubspot_mainform_form_title($post_ID, $post, $update)
{
    \ojoho\save_custom_post(['page', 'careers'], 'hubspot_mainform_form_title', $update);
}

add_action('edit_form_after_editor', 'kenza_hubspot_mainform_form_title', 20);
add_action('wp_insert_post', 'save_kenza_hubspot_mainform_form_title', 20, 3);

function kenza_hubspot_applicationform_title()
{
    \ojoho\custom_post(
        [
            'type'        => ['page'],
            'name'        => 'hubspot_applicationform_title',
            'onlyin'      => 'page-careers.php',
            'placeholder' => 'Enter application form title here',
            'label'       => 'HubSpot application form title',
            'helptext'    => 'Most pages have a main form. This is the title that appears next to it.',
            'no-i18n'     => true
        ]
    );
}

function get_kenza_hubspot_applicationform_title()
{
    return do_shortcode(wptexturize(\ojoho\get_custom_post(['page'], 'hubspot_applicationform_title')));
}

function save_kenza_hubspot_applicationform_title($post_ID, $post, $update)
{
    \ojoho\save_custom_post(['page'], 'hubspot_applicationform_title', $update);
}

add_action('edit_form_after_editor', 'kenza_hubspot_applicationform_title', 20);
add_action('wp_insert_post', 'save_kenza_hubspot_applicationform_title', 20, 3);

function kenza_hubspot_applicationform_text()
{
    \ojoho\custom_post(
        [
            'type'        => ['page'],
            'name'        => 'hubspot_applicationform_text',
            'onlyin'      => 'page-careers.php',
            'placeholder' => 'Enter optional application form text here',
            'label'       => 'HubSpot application form text',
            'helptext'    => 'Some pages have text under the title of their main form. Leave blank if not needed.',
            'no-i18n'     => true
        ]
    );
}

function get_kenza_hubspot_applicationform_text()
{
    return do_shortcode(wptexturize(\ojoho\get_custom_post(['page'], 'hubspot_applicationform_text')));
}

function save_kenza_hubspot_applicationform_text($post_ID, $post, $update)
{
    \ojoho\save_custom_post(['page'], 'hubspot_applicationform_text', $update);
}

add_action('edit_form_after_editor', 'kenza_hubspot_applicationform_text', 20);
add_action('wp_insert_post', 'save_kenza_hubspot_applicationform_text', 20, 3);

function kenza_hubspot_mainform_shortcode()
{
    $helptext = 'This is the shortcode that comes from the hubspot menu item. Click "HubSpot" in the menu, log in';
    $helptext .= ' if needed and you will see a list of all forms. Hover your mouse over the form you wish to place';
    $helptext .= ' here and click "copy shortcode"';
    \ojoho\custom_post(
        [
            'type'        => ['page', 'careers'],
            'name'        => 'hubspot_mainform_shortcode',
            'placeholder' => 'Enter shortcode here',
            'helptext'    => $helptext,
            'label'       => 'Hubspot form shortcode',
            'no-i18n'     => true
        ]
    );
}

function get_kenza_hubspot_mainform_shortcode()
{
    return \ojoho\get_custom_post(['page', 'careers'], 'hubspot_mainform_shortcode');
}

function save_kenza_hubspot_mainform_shortcode($post_ID, $post, $update)
{
    \ojoho\save_custom_post(['page', 'careers'], 'hubspot_mainform_shortcode', $update);
}

add_action('edit_form_after_editor', 'kenza_hubspot_mainform_shortcode', 20);
add_action('wp_insert_post', 'save_kenza_hubspot_mainform_shortcode', 20, 3);

function kenza_hubspot_mainform_error_message()
{
    $helptext = 'This is the error message we want to appear when there&rsquo;s a problem with the form.';
    \ojoho\custom_post(
        [
            'type'        => ['page'],
            'name'        => 'hubspot_mainform_error_message',
            'placeholder' => 'Enter error message here',
            'helptext'    => $helptext,
            'label'       => 'Hubspot form error message',
            'no-i18n'     => true
        ]
    );
}

function get_kenza_hubspot_mainform_error_message()
{
    return do_shortcode(wptexturize(\ojoho\get_custom_post(['page'], 'hubspot_mainform_error_message')));
}

function save_kenza_hubspot_mainform_error_message($post_ID, $post, $update)
{
    \ojoho\save_custom_post(['page'], 'hubspot_mainform_error_message', $update);
}

add_action('edit_form_after_editor', 'kenza_hubspot_mainform_error_message', 20);
add_action('wp_insert_post', 'save_kenza_hubspot_mainform_error_message', 20, 3);

function kenza_hubspot_include_emailform()
{
    global $post;
    $helptext = 'Uncheck this to omit the email form from a page';
    $chval = get_post_meta($post->ID, '_kenza_hubspot_include_emailform', true);
    $checked = 'checked';

    if ($chval == -1) {
        $checked = '';
    }
    $HTML = <<<EOH
    <fieldset>
        <h3><label for=" \$name">\$labelhtml</label></h3>
        <p style="color:#666;">
            <input type="checkbox" $checked id="\$name" name="\$name" value="true"/>
            \$helptext
        </p>
    </fieldset>
EOH;

    \ojoho\custom_post(
        [
            'type'        => ['page'],
            'html'        => $HTML,
            'name'        => 'hubspot_include_emailform',
            'placeholder' => 'Remove email form',
            'helptext'    => $helptext,
            'label'       => 'Show Hubspot email form on page',
            'no-i18n'     => true
        ]
    );
}

function get_kenza_hubspot_include_emailform()
{
    return \ojoho\get_custom_post(['page'], 'hubspot_include_emailform');
}

function save_kenza_hubspot_include_emailform($post_ID, $post, $update)
{
    \ojoho\save_custom_post(['page'], 'hubspot_include_emailform', $update, false, false, true);
}

add_action('edit_form_after_editor', 'kenza_hubspot_include_emailform', 20);
add_action('wp_insert_post', 'save_kenza_hubspot_include_emailform', 20, 3);

function kenza_hubspot_emailform_title()
{
    echo '<div class="email-form-section">';

    $helptext = 'This will override the title of the email form on this page if needed.';
    \ojoho\custom_post(
        [
            'type'        => ['page'],
            'name'        => 'hubspot_emailform_title',
            'placeholder' => 'Enter form title here',
            'helptext'    => $helptext,
            'label'       => 'Hubspot email form title',
            'no-i18n'     => true
        ]
    );

    echo '</div>';
}

function get_kenza_hubspot_emailform_title()
{
    return do_shortcode(wptexturize(\ojoho\get_custom_post(['page'], 'hubspot_emailform_title')));
}

function save_kenza_hubspot_emailform_title($post_ID, $post, $update)
{
    \ojoho\save_custom_post(['page'], 'hubspot_emailform_title', $update);
}

add_action('edit_form_after_editor', 'kenza_hubspot_emailform_title', 20);
add_action('wp_insert_post', 'save_kenza_hubspot_emailform_title', 20, 3);

function kenza_hubspot_emailform_subtitle()
{
    echo '<div class="email-form-section">';

    $helptext = 'Optional. Adds a paragraph under the email form title';
    \ojoho\custom_post(
        [
            'type'        => ['page'],
            'name'        => 'hubspot_emailform_subtitle',
            'placeholder' => 'Enter form subtitle here',
            'helptext'    => $helptext,
            'label'       => 'Hubspot email form subtitle',
            'no-i18n'     => true
        ]
    );

    echo '</div>';
}

function get_kenza_hubspot_emailform_subtitle()
{
    return do_shortcode(wptexturize(\ojoho\get_custom_post(['page'], 'hubspot_emailform_subtitle')));
}

function save_kenza_hubspot_emailform_subtitle($post_ID, $post, $update)
{
    \ojoho\save_custom_post(['page'], 'hubspot_emailform_subtitle', $update);
}

add_action('edit_form_after_editor', 'kenza_hubspot_emailform_subtitle', 20);
add_action('wp_insert_post', 'save_kenza_hubspot_emailform_subtitle', 20, 3);


function kenza_include_share()
{
    global $post;
    $helptext = 'Check this to include share on a page';
    $chval = get_post_meta($post->ID, '_kenza_include_share', true);
    $checked = '';

    if ($chval == 1) {
        $checked = 'checked';
    }
    $HTML = <<<EOH
    <fieldset>
        <h3><label for=" \$name">\$labelhtml</label></h3>
        <p style="color:#666;">
            <input type="checkbox" $checked id="\$name" name="\$name" value="true"/>
            \$helptext
        </p>
    </fieldset>
EOH;

    \ojoho\custom_post(
        [
            'type'        => ['page', 'careers'],
            'html'        => $HTML,
            'name'        => 'include_share',
            'placeholder' => 'Add share to page',
            'helptext'    => $helptext,
            'label'       => 'Show share on page',
            'no-i18n'     => true
        ]
    );
}

function get_kenza_include_share()
{
    return \ojoho\get_custom_post(['page', 'careers'], 'include_share');
}

function save_kenza_include_share($post_ID, $post, $update)
{
    \ojoho\save_custom_post(['page', 'careers'], 'include_share', $update, false, false, true);
}

add_action('edit_form_after_editor', 'kenza_include_share', 20);
add_action('wp_insert_post', 'save_kenza_include_share', 20, 3);

function kenza_share()
{
    echo '<div class="share-section">';
    $helptext = 'This is the tweet text when the page is shared via twitter.';
    \ojoho\custom_post(
        [
            'type'        => ['page', 'careers'],
            'name'        => 'twitter_share_text',
            'helptext'    => $helptext,
            'label'       => 'Share by twitter text',
            'no-i18n'     => true
        ]
    );
    $helptext = 'This is the title when the page is shared via email.';
    \ojoho\custom_post(
        [
            'type'        => ['page', 'careers'],
            'name'        => 'email_share_title',
            'helptext'    => $helptext,
            'label'       => 'Share by email title',
            'no-i18n'     => true
        ]
    );
    $helptext = 'This is the text when the page is shared via email.';
    \ojoho\custom_post(
        [
            'type'        => ['page', 'careers'],
            'name'        => 'email_share_text',
            'helptext'    => $helptext,
            'label'       => 'Share by email text',
            'no-i18n'     => true
        ]
    );
    echo '</div>';
}

function get_kenza_twitter_share_text()
{
    return do_shortcode(wptexturize(\ojoho\get_custom_post(['page', 'careers'], 'twitter_share_text')));
}

function save_kenza_twitter_share_text($post_ID, $post, $update)
{
    \ojoho\save_custom_post(['page', 'careers'], 'twitter_share_text', $update);
}

function get_kenza_email_share_title()
{
    return do_shortcode(wptexturize(\ojoho\get_custom_post(['page', 'careers'], 'email_share_title')));
}

function get_kenza_email_share_text()
{
    return do_shortcode(wptexturize(\ojoho\get_custom_post(['page', 'careers'], 'email_share_text')));
}

function save_kenza_email_share_title($post_ID, $post, $update)
{
    \ojoho\save_custom_post(['page', 'careers'], 'email_share_title', $update);
}

function save_kenza_email_share_text($post_ID, $post, $update)
{
    \ojoho\save_custom_post(['page', 'careers'], 'email_share_text', $update);
}


add_action('edit_form_after_editor', 'kenza_share', 20);
add_action('wp_insert_post', 'save_kenza_email_share_title', 20, 3);
add_action('wp_insert_post', 'save_kenza_email_share_text', 20, 3);
add_action('wp_insert_post', 'save_kenza_twitter_share_text', 20, 3);

function kenza_hubspot_chatbot_title()
{
    $helptext = 'This will override the text in the chatbot bubble if needed.';
    \ojoho\custom_post(
        [
            'type'        => ['page'],
            'name'        => 'hubspot_chatbot_title',
            'helptext'    => $helptext,
            'label'       => 'Hubspot chatbot bubble text',
            'no-i18n'     => true
        ]
    );
}

add_filter('post_thumbnail_html', 'remove_width_height_attribute', 10);
add_filter('image_send_to_editor', 'remove_width_height_attribute', 10);

function remove_width_height_attribute($html)
{
    $html = preg_replace('/(width|height)="\d*"\s/', "", $html);
    return $html;
}

function get_kenza_hubspot_chatbot_title()
{
    return do_shortcode(wptexturize(\ojoho\get_custom_post(['page'], 'hubspot_chatbot_title')));
}

function save_kenza_hubspot_chatbot_title($post_ID, $post, $update)
{
    \ojoho\save_custom_post(['page'], 'hubspot_chatbot_title', $update);
}

add_action('edit_form_after_editor', 'kenza_hubspot_chatbot_title', 20);
add_action('wp_insert_post', 'save_kenza_hubspot_chatbot_title', 20, 3);

function my_remove_wp_seo_meta_box()
{
    remove_meta_box('wpseo_meta', ['videos', 'service-teasers', 'teasers'], 'normal');
}
add_action('add_meta_boxes', 'my_remove_wp_seo_meta_box', 100);

add_filter('the_content', 'kenza_lazy_load_images');
add_filter('post_thumbnail_html', 'kenza_lazy_load_images');
add_filter('render_block', 'kenza_lazy_load_images');

function kenza_lazy_load_images($content)
{
    $content = str_replace('<img', '<img loading="lazy"', $content);
    return $content;
}


//Allow some SVG tags
add_filter('wp_kses_allowed_html', function ($tags) {
    $tags['svg'] = array(
        'xmlns' => array(),
        'fill' => array(),
        'viewbox' => array(),
        'role' => array(),
        'aria-hidden' => array(),
        'focusable' => array(),
        'width' => array(),
        'height' => array()
    );
    $tags['path'] = array(
        'd' => array(),
        'fill' => array(),
    );
    $tags['defs'] = array();
    $tags['radialGradient'] = array(
        'id' => array(),
        'cx' => array(),
        'cy' => array(),
        'r' => array(),
        'fx' => array(),
        'fy' => array(),
    );
    $tags['animate'] = array(
        'attributeName' => array(),
        'dur' => array(),
        'from' => array(),
        'to' => array(),
        'repeatCount' => array(),
        'fill' => array(),
    );
    $tags['stop'] = array(
        'offset' => array(),
        'style' => array()
    );
    $tags['clipPath'] = array(
        'id' => array()
    );
    $tags['rect'] = array(
        'class' => array(),
        'clip-path' => array(),
        'width' => array(),
        'height' => array()
    );
    return $tags;
}, 10, 2);
