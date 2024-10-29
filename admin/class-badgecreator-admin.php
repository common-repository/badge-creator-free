<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       
 * @since      1.0.0
 *
 * @package    BadgeCreator
 * @subpackage BadgeCreator/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    BadgeCreator
 * @subpackage BadgeCreator/admin
 * @author     Stangel <stan.angel@yahoo.com>
 */
class BadgeCreator_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @accbadgeCreator   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @accbadgeCreator   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in BadgeCreator_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The BadgeCreator_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/badgeCreator-admin.css', array(), $this->version, 'all' );
        wp_enqueue_style('thickbox');

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in BadgeCreator_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The BadgeCreator_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/badgeCreator-admin.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');

	}

        public function register_badgeCreator_type() {

        $labels_badgeCreator = array(
            'name' => _x('Badge', 'badgeCreator'),
            'singular_name' => _x('Badge', 'badgeCreator'),
            'add_new' => _x('New badge', 'badgeCreator'),
            'add_new_item' => _x('New Badge', 'badgeCreator'),
            'edit_item' => _x('Edit Badge', 'badgeCreator'),
            'new_item' => _x('New badge', 'badgeCreator'),
            'view_item' => _x('View badge', 'badgeCreator'),
            'not_found' => _x('No badge found', 'badgeCreator'),
            'not_found_in_trash' => _x('No badge in the trash', 'badgeCreator'),
            'menu_name' => _x('Badges', 'badgeCreator'),
            'all_items' => _x('Badges', 'badgeCreator'),
        );

        $args_badgeCreator = array(
            'labels' => $labels_badgeCreator,
            'hierarchical' => false,
            'description' => 'Badges',
            'supports' => array('title'),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => false,
            'publicly_queryable' => false,
            'exclude_from_search' => false,
            'has_archive' => false,
            'query_var' => false,
            'can_export' => true,
            'menu_icon' => 'dashicons-thumbs-up',
        );

        register_post_type('badgeCreator-badge', $args_badgeCreator);
    }

    public function badgeCreator_cpt_add_boxes(){
            $screens = array('badgeCreator-badge');
            foreach ($screens as $screen) {
                add_meta_box('badgeCreator_dimensions',
                        __('Badge settings', 'badgeCreator'),
                        array($this, 'badgeCreator_cpt_badgeCreator_boxe_dimensions'),
                        $screen
                        );
            }
        }

    public function badgeCreator_cpt_badgeCreator_boxe_dimensions(){
    	global $post;
    	echo '<input type="hidden" name="badgeCreator_noncename" id="badgeCreator_noncename" value="'.wp_create_nonce(plugin_basename(__FILE__)).'" />';

        $width = 600;
        $height = 600;
        $image_src = "";
            
                
                if( get_post_meta($post->ID, '_badgeCreator_width', true) ){

                    $width = get_post_meta( $post->ID, '_badgeCreator_width', true );
                }

                if( get_post_meta($post->ID, '_badgeCreator_height', true) ){

                    $height = get_post_meta( $post->ID, '_badgeCreator_height', true ); 
                }

                if( get_post_meta($post->ID, '_badgeCreator_image', true) ){

                    $image_id  = get_post_meta( $post->ID, '_badgeCreator_image', true );
                    $image_src = wp_get_attachment_url( $image_id );
                }

                if($post->ID){
                    ?>
                        <p style="margin-top: 2%; margin-bottom: 3%;">
                            <i>
                                <?php _e( 'To use this badge, use the following shortcode:', 'badgeCreator' ); ?> 
                                <strong>[display_badge id=<?php echo($post->ID); ?>] </strong> 
                            </i>
                        </p>
                    <?php
                }
                
    	?>


    	
    	<h2 class="section-title"> <?php _e( 'Width' ); ?> </h2>
    	<input type="number" name="_badgeCreator_width" value="<?php echo $width; ?>" class="widefat" />
    	
        <h2 class="section-title"> <?php _e( 'Height' ); ?> </h2>
    	<input type="number" name="_badgeCreator_height" value="<?php echo $height; ?>" class="widefat" />
        
        <h2 class="section-title"> <?php _e( 'Image' ); ?> </h2>
        <div>
            <img id="badge_image" src="<?php echo $image_src ?>" />
            <input type="hidden" name="upload_image_id" id="upload_image_id" value="<?php echo $image_id; ?>" />
            <input type="hidden" id="post_id" value="<?php echo $post->ID; ?>" />
            <p>
                <a title="<?php esc_attr_e( 'Set image' ) ?>" href="#" id="set-badge-image"> <?php _e( 'Set image' ) ?></a>
                <a title="<?php esc_attr_e( 'Remove image' ) ?>" href="#" id="remove-badge-image" style="<?php echo ( ! $image_id ? 'display:none;' : '' ); ?>"><?php _e( 'Remove image' ) ?></a>
            </p>
        </div>
        


        <?php
        
    }

    public function badgeCreator_cpt_save_badgeCreator_meta($post_id, $post){
    	if( !wp_verify_nonce($_POST['badgeCreator_noncename'], plugin_basename(__FILE__))  ){
    		return $post->ID;
    	} 

    	if( !current_user_can('edit_post', $post->ID) ){
    		return $post->ID;
    	}

    	$badgemetas['_badgeCreator_width'] = $_POST['_badgeCreator_width'];
    	$badgemetas['_badgeCreator_height'] = $_POST['_badgeCreator_height'];
        $badgemetas['_badgeCreator_image'] = $_POST['upload_image_id'];

    	foreach ($badgemetas as $key => $value) {
    		if($post->post_type == 'revision' ){
    				return;
    		}

    		if( get_post_meta($post->ID, $key, FALSE) ){
    			update_post_meta($post->ID, $key, $value);
    		}else{
    			add_post_meta($post->ID, $key, $value);
    		}

    		if(!$value){
    			delete_post_meta($post->ID, $key);
    		}
    	}

    }


 
function badgeCreator_cpt_add_column_header($defaults) {
    $defaults = array_slice($defaults, 0, 2, true) +
    array("shortcode" => "Shortcode") +
    array_slice($defaults, 2, count($defaults)-2, true);

    return $defaults;
}
function badgeCreator_cpt_add_column_content($column_name, $post_ID) {
    if ($column_name == 'shortcode') {
        echo '[display_badge id='.$post_ID.']';
    }
}
    


}
