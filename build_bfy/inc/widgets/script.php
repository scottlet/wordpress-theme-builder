<?php

namespace kenza;

class ScriptWidget extends \WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            // Base ID of your widget
            'kenza_script_widget',
            // Widget name will appear in UI
            __('Script', 'kenza_script_widget'),
            // Widget description
            array(
                'description' => __('Drop one or more of these into the scripts area', 'kenza_script_widget')
            )
        );
    }

    public function widget($args, $instance)
    {
        $contents = $instance['contents'];
        if (! empty($contents)) {
            echo $contents;
        }
    }

    public function form($instance)
    {
        if (isset($instance[ 'title' ])) {
            $title = $instance[ 'title' ];
        } else {
            $title = __('', 'kenza_script_widget');
        }
        if (isset($instance[ 'contents' ])) {
            $contents = $instance[ 'contents' ];
        } else {
            $contents = __('', 'kenza_script_widget');
        }
        ?>
        <p>
        <label for="<?php echo $this->get_field_id('title'); ?>">
            <?php _e('Script block title'); ?>
        </label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
            name="<?php echo $this->get_field_name('title'); ?>"
            placeholder="script name"
            type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id('contents'); ?>">
            <?php _e('JS script snippet'); ?>
        </label>
        <textarea rows="10" style="width:100%" id="<?php echo $this->get_field_id('contents'); ?>"
            name="<?php echo $this->get_field_name('contents'); ?>"
            placeholder="add script block here"><?php echo $contents; ?></textarea>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (! empty($new_instance['title'])) ? $new_instance['title'] : '';
        $instance['contents'] = (! empty($new_instance['contents'])) ? $new_instance['contents'] : '';
        return $instance;
    }
}