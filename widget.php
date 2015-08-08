<?php
 
class mglossary_widget extends WP_Widget {
 
    function __construct(){
        // Constructor del Widget
        $widget_ops = array('classname' => 'mglossary_widget', 'description' => __("Show Glossary Terms attached to a post","metaboxglossary") );
        parent::__construct('mglossary_widget', "Metabox Glossary Widget", $widget_ops);
    }
 
    function widget($args,$instance){
        // Contenido del Widget que se mostrará en la Sidebar
        extract($args);
        echo $before_widget; 

        $title = $instance["mglossary_pollid"]; 
        $atoz = $instance["mglossary_atoz"];
        ?>
        <h3 class="widget-title"><?php echo $title; ?></h3>
        <?php
        if (!is_front_page()) {
            global $post;
            setup_postdata( $post );
            $post_ID = get_the_ID();
            if ( $post->post_type != "glossary") {
                    $type = 'glossary';
                    $glossary = get_post_meta( $post_ID, 'glossary-ids', true );
                    $args=array(
                            'post_type' => $type,
                            'post_status' => 'publish',
                            );

                    $mypost = get_posts($args);
                    if ($glossary) {
                    ?><ul><?php
                    foreach($mypost as $post) : setup_postdata( $post );
                        $postID = $post->ID; 
                        if (!empty($glossary) && in_array($postID, $glossary)) {?>
                        <li><a href="<?php echo $url = get_permalink($postID); ?>"><?php echo $title = get_the_title($postID); ?></a></li>
                    <?php }
                     endforeach; 
                    ?></ul><?php
                    } else {
                        _e('No entries for this post');
                    }
                    wp_reset_postdata();
            } else {
                    if ($atoz=="1") {
                            echo "<div id=\"mglossary-glossary-atoz\">";
                            echo do_shortcode('[glossary_atoz /]');
                            echo "</div>";
                    } else {
                        $type = 'glossary';
                        $args=array(
                                'post_type' => $type,
                                'post_status' => 'publish',
                        );

                        $mypost = get_posts($args);
                        if ($mypost) {
                        ?><ul><?php
                        foreach($mypost as $post) : setup_postdata( $post );
                            $postID = $post->ID; ?>
                            <li><a href="<?php echo $url = get_permalink($postID); ?>"><?php echo $title = get_the_title($postID); ?></a></li>
                        <?php endforeach; 
                        ?></ul><?php
                         } else {
                            _e('No entries Glossary','metaboxglossary');
                        }
                        wp_reset_postdata();
                    }
            } 
        } else {
                    if ($atoz=="1") {
                            echo "<div id=\"mglossary-glossary-atoz\">";
                            echo do_shortcode('[glossary_atoz /]');
                            echo "</div>";
                    } else {
                            $type = 'glossary';
                            $args=array(
                                    'post_type' => $type,
                                    'post_status' => 'publish',
                            );

                            $mypost = get_posts($args);
                            if ($mypost) {
                            ?><ul><?php
                            foreach($mypost as $post) : setup_postdata( $post );
                                $postID = $post->ID; ?>
                                <li><a href="<?php echo $url = get_permalink($postID); ?>"><?php echo $title = get_the_title($postID); ?></a></li>
                            <?php endforeach; 
                            ?></ul><?php
                             } else {
                                _e('No entries Glossary','metaboxglossary');
                            }
                            wp_reset_postdata();
                    }
            }


        echo $after_widget;
    }
 
    function update($new_instance, $old_instance){
        // Función de guardado de opciones  
        $instance = $old_instance;
        $instance["mglossary_pollid"] = strip_tags($new_instance["mglossary_pollid"]);
        $instance["mglossary_atoz"] = strip_tags($new_instance["mglossary_atoz"]);
        // Repetimos esto para tantos campos como tengamos en el formulario.
        return $instance;      
    }
 
    function form($instance){
        // Formulario de opciones del Widget, que aparece cuando añadimos el Widget a una Sidebar
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('mglossary_pollid'); ?>"><?php _e('Titulo','metaboxglossary'); ?></label>
            <input type="text" id="<?php echo $this->get_field_id('mglossary_pollid'); ?>" name="<?php echo $this->get_field_name('mglossary_pollid'); ?>" value="<?php echo $title = $instance["mglossary_pollid"]; ?>">
        </p>
        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id('mglossary_atoz'); ?>" name="<?php echo $this->get_field_name('mglossary_atoz'); ?>" value="1" <?php if (isset($instance["mglossary_atoz"]) && $instance["mglossary_atoz"] == "1") echo "checked=\"checked\""; ?>>
            <label for="<?php echo $this->get_field_id('mglossary_atoz'); ?>"><?php _e('Show A-Z','metaboxglossary'); ?></label>
        </p>
        <?php
    }    
} 
 
?>