<?php

/*
*Plugin Name: Infinity Fields Widget
*Plugin URI: http://www.anderson-goncalves.com
*Description: Widget to add infinity custom fields
*Version: 1.0
*Author: Anderson Gonçalves (Bônus)
*Author URI: www.facebook.com/anderson.rockandroll
*Licence: GPL2
*Text Domain: infinity-fields-widget
*Domain Path: languages/
*/

if (!defined('ABSPATH')) { exit; }

$ifw_dir = plugins_url('', __FILE__) . '/';

add_action('plugins_loaded', 'ifw_load_textdomain');

 if (!function_exists('ifw_load_textdomain')) {
    function ifw_load_textdomain() {
        global $ifw_dir;
        load_plugin_textdomain('infinity-fields-widget', false, $ifw_dir . 'languages/');
    }
 }
 
function ifw_register_scripts() {
    global $ifw_dir;
    wp_enqueue_script('ifw_script', $ifw_dir . 'js/infinity-fields-widget.js', array('jquery'), '1.0', true);
}

//add_action('wp_enqueue_scripts', 'ifw_register_scripts');
add_action('admin_enqueue_scripts', 'ifw_register_scripts');

if (!class_exists('IFW_Widget')) {
    class IFW_Widget extends WP_Widget {
        
        public function __construct() {
            parent::__construct('ifw', 
                    'Infinity Fields Widget', 
                    array(
                        'description' => __('Widget to custom infinity fields', 'infinity-fields-widget')
                    ));
        }
        
        public function form($instance) {
            $i = 0;
            $a = 0;
            $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'text_domain' );
            ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __( 'Title:', 'infinity-fields-widget' ); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
            </p>
            <div class="ifw" style="float:left;width:100%">
            <?php
            if (count($instance)) {
                unset($instance['title']);
                foreach ($instance as $key => $value) : ?>
                    <?php if (($i % 2) === 0) { $label = 'Label'; ?> <div> <?php } else { $label = 'Valor'; } ?>
                        <p style="float:left;width:48%;margin:2px 1%;">
                            <label for="<?php echo $this->get_field_id( $key ); ?>"><?php echo sprintf(__( $label .' <span class="ifw-label">%d</span>:', 'infinity-fields-widget' ), $a + 1); ?></label> 
                            <input class="widefat" id="<?php echo $this->get_field_id( $key ); ?>" name="<?php echo $this->get_field_name( $key ); ?>" type="text" value="<?php echo esc_attr( $value ); ?>">
                        </p>
                    <?php if (($i % 2) === 1) { $a++; ?> </div> <?php } ?>
                <?php $i++; endforeach;
            } else {
            $x = md5(uniqid(rand(), true));
            $y = md5(uniqid(rand(), true));
            ?>
                <div>
                    <p style="float:left;width:48%;margin:2px 1%;">
                        <label for="<?php echo $this->get_field_id( $x ); ?>"><?php echo sprintf(__( 'Label <span class="ifw-label">%d</span>:', 'infinity-fields-widget' ), $a + 1); ?></label> 
                        <input class="widefat" id="<?php echo $this->get_field_id( $x ); ?>" name="<?php echo $this->get_field_name( $x ); ?>" type="text" value="">
                    </p>
                    <p style="float:left;width:48%;margin:2px 1%;">
                        <label for="<?php echo $this->get_field_id( $y ); ?>"><?php echo sprintf(__( 'Value <span class="ifw-label">%d</span>:', 'infinity-fields-widget' ), $a + 1); ?></label> 
                        <input class="widefat" id="<?php echo $this->get_field_id( $y ); ?>" name="<?php echo $this->get_field_name( $y ); ?>" type="text" value="">
                    </p>
                </div>
            <?php } ?>
            <a href="#" class="ifw-add" onclick="return ifw_add_field(this)"><?php _e('Add', 'infinity-fields-widget'); ?></a>
            </div>
        <?php }
        public function update($new_instance, $old_instance) {
            $instance = array();
            
            foreach ($new_instance as $key => $value) {
                $value = $new_instance[$key];
                
                if (empty($value)) { continue; }
                $instance[$key] = $value;
            }
            //var_dump($instance);exit;
            return $instance;
        }
        public function widget($args, $instance) {
            echo $args['before_widget'];
            if ( ! empty( $instance['title'] ) ) {
                    echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
            }
            unset($instance['title']);
            echo "<ul>";
            $i = 0;
            foreach ($instance as $value) {
                if (($i % 2) === 0) { echo '<li>'; }
                echo "<span class='ifw-" . (($i % 2) === 0 ? 'label' : 'value') . "'>$value</span>";
                if (($i % 2) === 1) { echo '</li>'; }
                $i++;
            }
            echo "</ul>";
            echo $args['after_widget'];
        }
    }
    
    add_action('widgets_init', function () {
        register_widget('IFW_Widget');
    });
    
    function ifw_get_fields() {
        global $wp_widget_factory;
        $ifw = $wp_widget_factory->widgets['IFW_Widget'];
        $option = get_option('widget_ifw');
        $option = $option[$ifw->number];
        unset($option['title']);
        
        $i = $a = 0;
        $opts = array();
        foreach ($option as $value) {
            if (($i % 2) === 0) {
                $opts[$a]['label'] = $value;
            } else {
                $opts[$a]['value'] = $value;
                $a++;
            }
            $i++;
        }
        return $opts;
    }
}