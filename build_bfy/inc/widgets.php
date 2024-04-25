<?php

function unregister_default_wp_widgets()
{

    unregister_widget('WP_Widget_Tag_Cloud');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Media_Audio');
    unregister_widget('WP_Widget_Media_Video');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Custom_HTML');
    unregister_widget('WP_Widget_Media_Image');
    unregister_widget('WP_Widget_Media_Gallery');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Text');
    unregister_widget('WP_Nav_Menu_Widget');
}

function kenza_setup_sidebars()
{
    $script_desc = 'If you need to add a new script to every page, for instance a tracking code script block';

    register_sidebar(
        array(
            'name'          => __('Script page header', 'kenza'),
            'id'            => 'kenza_scripts_header',
            'description'   => $script_desc,
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => ''
        )
    );

    register_sidebar(
        array(
            'name'          => __('Script page body open', 'kenza'),
            'id'            => 'kenza_scripts_body',
            'description'   => $script_desc,
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => ''
        )
    );

    register_sidebar(
        array(
            'name'          => __('Script page footer', 'kenza'),
            'id'            => 'kenza_scripts_footer',
            'description'   => $script_desc,
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => ''
        )
    );

    register_sidebar(
        array(
            'name'          => __('Email form shortcode', 'kenza'),
            'id'            => 'kenza_emailform',
            'description'   => 'Drop the short code for the email form widget in here',
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => ''
        )
    );

    register_sidebar(
        array(
            'name'          => __('Regular Chat', 'kenza'),
            'id'            => 'kenza_chat',
            'description'   => 'Drop the chat widget in here',
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => ''
        )
    );

    register_sidebar(
        array(
            'name'          => __('Jobs Chat', 'kenza'),
            'id'            => 'kenza_jobs_chat',
            'description'   => 'Drop the chat widget in here',
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => ''
        )
    );

    register_sidebar(
        array(
            'name'          => __('Cookie Bar', 'kenza'),
            'id'            => 'kenza_cookie_bar',
            'description'   => 'Drop a text widget in here',
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => ''
        )
    );
}

// Register and load the widget
function kenza_load_widget()
{
    register_widget('\kenza\ShortCodeWidget');
    register_widget('\kenza\ScriptWidget');
    register_widget('\kenza\TextWidget');
    register_widget('\kenza\ChatWidget');
}
