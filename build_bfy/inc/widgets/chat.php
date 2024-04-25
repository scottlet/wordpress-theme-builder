<?php

namespace kenza;

class ChatWidget extends \WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            // Base ID of your widget
            'kenza_chat_widget',
            // Widget name will appear in UI
            __('Chat', 'kenza_chat_widget'),
            // Widget description
            array(
                'description' => __('Drop one of these into a chat area', 'kenza_chat_widget')
            )
        );
    }

    public function widget($args, $instance)
    {
        $contents = $instance['contents'];
        $popup = $instance['popup'];
        $title = get_kenza_hubspot_chatbot_title();
        $bubbletext = $title;

        if (empty($title)) {
            $bubbletext = $popup;
        }

        $template = <<<EOS
        <script>
        function addScript(src) {
            var s = document.createElement('script');
            s.setAttribute('src', src);
            s.setAttribute('id', 'hs-script-loader');
            s.async = true;
            s.defer = true;
            s.type = 'text/javascript';
            document.body.appendChild(s);
        }

        if (document.querySelector('html').classList.contains('fixed')) {
            setTimeout(function () {
                addScript('%script%');
            }, 14000);
        } else {
            addScript('%script%');
        }
        </script>
EOS;

        if (! empty($bubbletext)) {
            echo '<div class="k-chat-bubble">';
            echo '    '.wptexturize($bubbletext);
            echo '</div>';
        }
        if (! empty($contents)) {
            $script = preg_replace(
                '/^(?:<[^>]+>)?.*(?:\n.*)?(\/\/[a-z0-9-_\/.]+?)".*(?:\n.*)?(?:<[^>]+>)?$/im',
                '$1',
                $contents
            );

            echo str_replace('%script%', $script, $template);
        }
    }

    public function form($instance)
    {
        if (isset($instance[ 'title' ])) {
            $title = $instance[ 'title' ];
        } else {
            $title = __('', 'kenza_chat_widget');
        }
        if (isset($instance[ 'contents' ])) {
            $contents = $instance[ 'contents' ];
        } else {
            $contents = __('', 'kenza_chat_widget');
        }
        if (isset($instance[ 'popup' ])) {
            $popup = $instance[ 'popup' ];
        } else {
            $popup = __('', 'kenza_chat_widget');
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
        <p>
        <label for="<?php echo $this->get_field_id('popup'); ?>">
            <?php _e('Help bubble text'); ?>
        </label>
        <textarea rows="10" style="width:100%" id="<?php echo $this->get_field_id('popup'); ?>"
            name="<?php echo $this->get_field_name('popup'); ?>"
            placeholder="add text for help bubble here"><?php echo $popup; ?></textarea>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (! empty($new_instance['title'])) ? $new_instance['title'] : '';
        $instance['contents'] = (! empty($new_instance['contents'])) ? $new_instance['contents'] : '';
        $instance['popup'] = (! empty($new_instance['popup'])) ? $new_instance['popup'] : '';
        return $instance;
    }
}