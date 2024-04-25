<?php

namespace kenza;

class TextWidget extends \WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            // Base ID of your widget
            'kenza_text_widget',
            // Widget name will appear in UI
            __('Text', 'kenza_text_widget'),
            // Widget description
            array(
                'description' => __('Drop one or more of these into a text area', 'kenza_text_widget')
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
        $contents = isset($instance[ 'contents' ]) ? $instance[ 'contents' ] : '';
        ?>
        <p>
        <label for="<?php echo $this->get_field_id('contents'); ?>">
            <?php _e('Text'); ?>
        </label>
        <textarea rows="10" style="width:100%" id="<?php echo $this->get_field_id('contents'); ?>"
            name="<?php echo $this->get_field_name('contents'); ?>"
            placeholder="add text here"><?php echo $contents; ?></textarea>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['contents'] = (! empty($new_instance['contents'])) ? $new_instance['contents'] : '';
        return $instance;
    }
}