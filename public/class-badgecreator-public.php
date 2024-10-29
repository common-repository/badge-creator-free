<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       
 * @since      1.0.0
 *
 * @package    BadgeCreator
 * @subpackage BadgeCreator/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    BadgeCreator
 * @subpackage BadgeCreator/public
 * @author     Stangel <stan.angel@yahoo.com>
 */
class BadgeCreator_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
                add_shortcode('display_badge', array($this, 'badgeCreator_cpt_get_badge'));
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/badgeCreator-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
                wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/badgeCreator-public.js', array( 'jquery' ), $this->version, false );
                wp_enqueue_script('badgeCreator-iframe-transport', plugin_dir_url( __FILE__ ) . 'js/upload/js/jquery.iframe-transport.min.js');
                wp_enqueue_script('wpd-jquery-form-js', plugin_dir_url( __FILE__ ) . 'js/jquery.form.min.js');
                wp_enqueue_script('badgeCreator-widget', plugin_dir_url( __FILE__ ) . 'js/upload/js/jquery.ui.widget.min.js');
                wp_enqueue_script('badgeCreator-fileupload', plugin_dir_url( __FILE__ ) . 'js/upload/js/jquery.fileupload.min.js');
                wp_enqueue_script('wpd-lazyload-js', plugin_dir_url( __FILE__ ) . 'js/jquery.lazyload.min.js', array('jquery'), $this->version, false);
                wp_enqueue_script('badgeCreator-knob', plugin_dir_url( __FILE__ ) . 'js/upload/js/jquery.knob.min.js');
                wp_enqueue_script('wpd-fabric-js', plugin_dir_url( __FILE__ ) . 'js/fabric.all.min.js', array('jquery'), $this->version, false);
                wp_localize_script($this->plugin_name, 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
	}
        
        public function badgeCreator_cpt_get_badge($args=null){
            $b_image = "";
            
            if($args && isset($args['id']) ){
                $b_id = $args['id'];

                $width = 600;
                $height = 600;
            
                
                if( get_post_meta($b_id, '_badgeCreator_width', true) ){

                    $width = get_post_meta($b_id, '_badgeCreator_width', true); 
                }

                if( get_post_meta($b_id, '_badgeCreator_height', true) ){

                    $height = get_post_meta($b_id, '_badgeCreator_height', true); 
                }

                if( get_post_meta($b_id, '_badgeCreator_image', true) ){

                    $image_id  = get_post_meta( $b_id, '_badgeCreator_image', true );
                    $b_image = wp_get_attachment_url( $image_id );
                } 

            } 
           ?>
             <script>
                    var badge_image = "<?php echo $b_image; ?>";
                    var badgeCreatorwidth = "<?php echo $width; ?>";
                    var badgeCreatorheight = "<?php echo $height; ?>";
                </script>
                <input type="hidden" id='badgeCreator-img' value="<?php echo $b_image; ?>"> 
                <div id="badgeCreator-whole-container" >
                    <div id="badge-container" style="position: relative;">
                    <canvas id="badgeCreator-canvas-editor" width="<?php echo intval($width); ?>" height="<?php echo intval($height);?>" style="position: absolute; left: 0; top: 0;" ></canvas>
                    <div id="badgeImages">
                    </div>
                </div>
                
                <div id="badgeCreator-actions">
                    <div class="badgeCreator-uploaded-container">
                        <form id="custom-upload-form" class="custom-uploader custom-upload-form" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>" enctype="multipart/form-data">
                            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('wpc-picture-upload-nonce'); ?>">
                            <input type="hidden" name="action" value="badgeCreator_cpt_handle_picture_upload"> 

                                <div id="drop">
                                    <a><?php _e("Choose image", "badgeCreator"); ?></a>
                                    <label for="user-custom-design"></label>
                                    <input type="file" name="userfile" id="user-custom-design"/>
                                    <div class="badgeCreator-upload-info"></div>
                                </div>
                        </form>
                    </div>
                        <a href="" class="button" id="badgeCreator-download" download=""> <?php _e('Download', 'badgeCreator"');?> </a>

                        <a href="" class="button" id="badgeCreator-reset"> <?php _e('Reset', 'badgeCreator"');?> </a>
                </div>
                
                </div>
            <?php
        }
        
        public function badgeCreator_cpt_handle_picture_upload() {
        $nonce = $_POST['nonce'];
//        check_ajax_referer( 'wpc-picture-upload-nonce', 'nonce', false);
        if (!check_ajax_referer( 'wpc-picture-upload-nonce', 'nonce', false)) {
            $busted = __("Cheating huh?", "badgeCreator");
            die($busted);
        }
        
        $upload_dir = wp_upload_dir();
        $generation_path = $upload_dir["basedir"]."/BM";
        if(!is_dir($generation_path))
            wp_mkdir_p ($generation_path);
        $generation_url = $upload_dir["baseurl"]."/BM";
        $file_name = uniqid();
            $valid_formats = array("jpg", "png", "jpeg"); //wpc-upl-extensions


            
//    var_dump($valid_formats);
        $name = $_FILES['userfile']['name'];
        $size = $_FILES['userfile']['size'];
        
        if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
            if (strlen($name)) {
                $success = 0;
                $message = "";
                $img_url = "";
                $img_id = uniqid();
//                    list($txt, $ext) = explode(".", $name);
                $path_parts = pathinfo($name);
                $ext = $path_parts['extension'];
                $ext = strtolower($ext);
                if (in_array($ext, $valid_formats)) {
                    $tmp = $_FILES['userfile']['tmp_name'];
                    if (move_uploaded_file($tmp, $generation_path . "/" . $file_name . ".$ext")) {
                        $min_width = 300;
                        $min_height = 300;
                        
                        if ($min_width > 0 || $min_height > 0) {
                            list($width, $height, $type, $attr) = getimagesize($generation_path . "/" . $file_name . ".$ext");
                            if (($min_width > $width || $min_height > $height) && $ext != "svg") {
                                $success = 0;
                                $message = sprintf(__('Uploaded file dimensions: %1$spx x %2$spx, minimum required ', 'badgeCreator'), $width, $height);
                                if ($min_width > 0 && $min_height > 0)
                                    $message.=__("dimensions:", "badgeCreator")." $min_height" . "px" . " x $min_height" . "px";
                                else if ($min_width > 0)
                                    $message.="width: $min_width" . "px";
                                else if ($min_height > 0)
                                    $message.="height: $min_height" . "px";
                            }
                            else {
                                $success = 1;
                                $message = "<span class='clipart-img'><img id='$img_id' src='$generation_url/$file_name.$ext' ></span>";
                                $img_url = "$generation_url/$file_name.$ext";
                            }
                        } else {
                            $success = 1;
                            $message = "<span class='clipart-img'><img id='$img_id' src='$img_url'></span>";
//                            $img_url = "$generation_url/$file_name.$ext";
                        }
                        if ($success == 0)
                            unlink($generation_path . "/" . $file_name . ".$ext");
                    }
                    else {
                        $success = 0;
                        $message = __('An error has occured! Please, try again later.', 'badgeCreator');
                    }
                } else {
                    $success = 0;
                    $message = __('File extension is incorrect: ' . $ext . '. Authorized extensions are: ', 'badgeCreator') . implode(", ", $valid_formats);
                }
                echo json_encode(
                        array(
                            "success" => $success,
                            "message" => $message,
                            "img_url" => $img_url,
                            "img_id" => $img_id,
                        )
                ); 
            }
        }
        die();
    }
    
    

}
