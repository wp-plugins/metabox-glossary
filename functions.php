<?php 

// Añadimos el Metabox donde se seleccionarán los Glosarios para añadir al Plugin
function adding_custom_meta_boxes( $post ) {
    add_meta_box( 
        'Glosarios',
        __( 'Glossary', 'metaboxglossary' ),
        'render_glosarios',
        'post',
        'normal',
        'default'
    );
}

add_action('init', 'mglossary_script_enqueuer');

function mglossary_script_enqueuer() {
    wp_register_style( 'mglossarystyle', plugins_url('mglossary-style.css', __FILE__) );
    wp_enqueue_style( 'mglossarystyle');
    /*wp_enqueue_script('jquery');*/

}

function render_glosarios( $post, $args = array() ) {

		$type = 'glossary';
		$glossary = get_post_meta( $post->ID, 'glossary-ids', true );
		$args=array(
  				'post_type' => $type,
				'post_status' => 'publish',
				);

		$mypost = get_posts($args);
		foreach($mypost as $post) : setup_postdata( $post );
  			$postID = $post->ID; ?>
    		<p><input type="checkbox" name="glossary[]" class="glossary-check" id="glossary-id-<?php echo $postID;?>" value="<?php echo $postID;?>" <?php if (is_array($glossary) && in_array($postID, $glossary)) echo "checked=\"checked\""; ?>><?php echo $title = get_the_title($postID); ?></p>
   		<?php endforeach; 
		wp_reset_postdata();

}
add_action( 'add_meta_boxes_post', 'adding_custom_meta_boxes' );

function mglossary_save_post_class_meta( $post_id, $post ) {
	if ( isset( $_POST['glossary'] )) {

    $new_meta_value = $_POST['glossary'];

    $meta_key = 'glossary-ids';

    $meta_value = get_post_meta( $post_id, $meta_key, true );

    if ( $new_meta_value && '' == $meta_value )
    add_post_meta( $post_id, $meta_key, $new_meta_value, true );

    elseif ( $new_meta_value && $new_meta_value != $meta_value )
    update_post_meta( $post_id, $meta_key, $new_meta_value );

    elseif ( '' == $new_meta_value && $meta_value )
    delete_post_meta( $post_id, $meta_key, $meta_value );

	}

}

add_action( 'save_post', 'mglossary_save_post_class_meta', 10, 2 );

function mglossary_create_widget() {
    include_once plugin_dir_path(__FILE__) . 'widget.php';
    register_widget('mglossary_widget');
}
add_action('widgets_init', 'mglossary_create_widget');