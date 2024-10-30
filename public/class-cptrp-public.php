<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://persaper.it/custom-post-type-related-post
 * @since      1.0.0
 *
 * @package    CPTRP
 * @subpackage cptrp/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    CPTRP
 * @subpackage cptrp/public
 * @author     Davide de Mattia <dvddemattia@gmail.com>
 */
class cptrp_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $cptrp    The ID of this plugin.
	 */
	private $cptrp;

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
	 * @param      string    $cptrp       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $cptrp, $version ) {

		$this->cptrp = $cptrp;
		$this->version = $version;

		add_shortcode( 'cptrp', array( $this, 'cptrp_shortcode' ) );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function cptrp_enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in cptrp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The cptrp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->cptrp, plugin_dir_url( __FILE__ ) . 'css/cptrp-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function cptrp_enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in cptrp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The cptrp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->cptrp, plugin_dir_url( __FILE__ ) . 'js/cptrp-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add cptrp shortcode.
	 *
	 * @since    1.0.0
	 */
	public function cptrp_shortcode() {

		global $post;

		$cptrp_options = get_option( 'cptrp-options' );

		$cptrp_path = $cptrp_options['cptrp-path'];

		$cptrp_title = $cptrp_options['cptrp-title'];

		$cptrp_post_type = $post->post_type;

		$current_post_id = $post->ID;

		// Check that the Post Type is active or exit

		if ( $cptrp_options['cptrp-'.$cptrp_post_type] != 'on' ) :

			return false;

		endif;

		$meta_value = get_post_meta( $current_post_id, 'cptrp-related' );

		$post_in = $this->cptrp_array_flatten($meta_value);

		if ($post_in) :

			$args = array( 
				'post_type' => $cptrp_post_type, 
				'posts_per_page' => -1, 
				'post__in' => $post_in,
				'orderby' => array('post__in'),
				
			);

			remove_all_filters('posts_orderby');
										
			$loop = new WP_Query( $args );

			$cptrp_get_template = null;

			if ($loop->found_posts > 0) :

				if ( $cptrp_path != '' ) :

					$cptrp_get_template = preg_replace('/\\.[^.\\s]{3,4}$/', '', $cptrp_path);

				else :

					$cptrp_theme_name = strtolower(wp_get_theme());

					$di = new RecursiveDirectoryIterator('wp-content/themes/'. $cptrp_theme_name);

					if ( $post->post_type != 'post' ) :

						foreach (new RecursiveIteratorIterator($di) as $filename => $file) {

							if ( basename($filename) == 'content-' . $post->post_type . '.php' ) :

								$filename_x = explode('/'.$cptrp_theme_name.'/', $filename);

								list($cptrp_get_template, $ext) = explode('.', end($filename_x));

								break;

							endif;

						}

					else :

						foreach (new RecursiveIteratorIterator($di) as $filename => $file) {

							if ( basename($filename) == 'content.php' ) :

								$filename_x = explode('/'.$cptrp_theme_name.'/', $filename);

								list($cptrp_get_template, $ext) = explode('.', end($filename_x));

								break;

							endif;

						}

					endif;

				endif;

				?>

				<?php

				if ( $cptrp_title != '' ) :

					echo '<h2>'.$cptrp_title.'</h2>';

				endif;

				?>

				<?php while ( $loop->have_posts() ) : $loop->the_post();

					get_template_part( $cptrp_get_template, get_post_format() );

				endwhile;

			endif;

		endif;	

	}

	public function cptrp_array_flatten($array) { 
		if (!is_array($array)) {
			return FALSE; 
		} 
		$result = array(); 
		foreach ($array as $key => $value) { 
			if (is_array($value)) { 
			  $result = array_merge($result, $this->cptrp_array_flatten($value)); 
			} else { 
			  $result[$key] = $value; 
			} 
		} 
		return $result; 
	}

}