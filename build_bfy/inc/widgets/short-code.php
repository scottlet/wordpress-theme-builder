<?php

namespace kenza;

class ShortCodeWidget extends \WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            // Base ID of your widget
            'kenza_short_code_widget',
            // Widget name will appear in UI
            __('Email Form', 'kenza_short_code_widget'),
            // Widget description
            array(
                'description' => __('Drop only one of these into the form widget area', 'kenza_short_code_widget')
            )
        );
    }

    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', $instance['title']);
        $contents = apply_filters('widget_title', $instance['contents']);
        $error = apply_filters('widget_title', $instance['error']);

        echo json_encode($instance);
    }

    public function form($instance)
    {
        if (isset($instance[ 'title' ])) {
            $title = $instance[ 'title' ];
        } else {
            $title = __('', 'kenza_short_code_widget');
        }
        if (isset($instance[ 'contents' ])) {
            $contents = $instance[ 'contents' ];
        } else {
            $contents = __('', 'kenza_short_code_widget');
        }

        if (isset($instance[ 'error' ])) {
            $error = $instance[ 'error' ];
        } else {
            $error = __('', 'kenza_short_code_widget');
        }
        ?>
        <p>
        <label for="<?php echo $this->get_field_id('title'); ?>">
            <?php _e('Form title'); ?>
        </label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
            name="<?php echo $this->get_field_name('title'); ?>"
            placeholder="short code"
            type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id('contents'); ?>">
            <?php _e('Form short code'); ?>
        </label>
        <input class="widefat" id="<?php echo $this->get_field_id('contents'); ?>"
            name="<?php echo $this->get_field_name('contents'); ?>"
            placeholder="short code"
            type="text" value="<?php echo esc_attr($contents); ?>" />
        </p>
        <label for="<?php echo $this->get_field_id('error'); ?>">
            <?php _e('Form error message'); ?>
        </label>
        <input class="widefat" id="<?php echo $this->get_field_id('error'); ?>"
            name="<?php echo $this->get_field_name('error'); ?>"
            placeholder="error text"
            type="text" value="<?php echo esc_attr($error); ?>" />
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (! empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['contents'] = (! empty($new_instance['contents'])) ? strip_tags($new_instance['contents']) : '';
        $instance['error'] = (! empty($new_instance['error'])) ? strip_tags($new_instance['error']) : '';
        return $instance;
    }
}