<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://persaper.it/custom-post-type-related-post
 * @since      1.0.0
 *
 * @package    CPTRP
 * @subpackage cptrp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    CPTRP
 * @subpackage cptrp/admin
 * @author     Davide de Mattia <dvddemattia@gmail.com>
 */
class cptrp_Admin {

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
	 * @param      string    $cptrp       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $cptrp, $version ) {

		$this->cptrp = $cptrp;
		$this->version = $version;

		// Initialize Settings
		require_once(sprintf("%s/settings.php", dirname(__FILE__)));
		$cptrp_settings = new cptrp_settings();

		$this->cptrp_options = get_option( 'cptrp-options' );

		// Add meta box
		add_action( 'add_meta_boxes', array( $this, 'cptrp_add_meta_box' ) );

		// Save post
		add_action( 'save_post', array( $this, 'cptrp_save_postdata' ) );

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->cptrp, plugin_dir_url( __FILE__ ) . 'css/cptrp-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->cptrp, plugin_dir_url( __FILE__ ) . 'js/cptrp-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Adds the meta box container.
	 *
	 * @since    1.0.0
	 */
	public function cptrp_add_meta_box( $post_type ) {

		// Add the meta box for active custom post type only

		$cptrp_options = $this->cptrp_options;

		$post_types = $this->cptrp_settings_post_types( $cptrp_options );
 
		if ( in_array( $post_type, $post_types ) ) {

			add_meta_box(
				'Correlati',
				esc_html__( ucfirst($post_type) . ' correlati', 'cptrp' ),
				array( $this, 'cptrp_render_meta_box_content' ),
				$post_type,
				'advanced',
				'high',
				$cptrp_options
			);

		}
	}

	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 *
	 * @since    1.0.0
	 */
	public function cptrp_render_meta_box_content( $post, $options ) {

		$post_id = $post->ID;

		// Get number of related posts

		$cptrp_n = $options['args']['cptrp-n'];

		// WP_Query Args

		$args = array (
			'post_type'         => $post->post_type,
			'orderby'			=> 'title',
			'order'             => 'ASC',
			'post_status'       => 'publish',
			'posts_per_page'	=> -1,
			'post__not_in' 		=> array($post_id)
		);

		remove_all_filters('posts_orderby');

		$query = new WP_Query($args);

		$values = get_post_meta( $post_id, 'cptrp-related' );	

		for ($i = 1; $i <= $cptrp_n; $i++) {

			if ( $query->have_posts() ) :

				echo '<div class="cptrp-select">';

				echo '<span class="cptrp-counter">'.$i.'</span>';

				echo '<select name="cptrp_field-'.$i.'" id="cptrp_field-'.$i.'" class="cptrp_posts_list">';

				echo '<option value="">'.esc_html__( 'Seleziona un post', 'cptrp' ).'</option>';

				while ($query->have_posts()) : 

					$query->the_post();

					if ( get_the_ID() == $values[0][$i] ) { $selected = 'selected'; } else { $selected = ''; }

					echo '<option value="'.get_the_ID().'" '.$selected.'>' . get_the_title() . '</option>';

				endwhile;

				echo '</select>';

				echo '</div>';

			endif;

		}

	}

	/**
	 * Save postdata.
	 *
	 * @since    1.0.0
	 */
	public function cptrp_save_postdata( $post_id ) {

		$cptrp_options = $this->cptrp_options;

		$cptrp_n = $cptrp_options['cptrp-n'];

		for ($i = 1; $i <= $cptrp_n; $i++) {

			if ( array_key_exists('cptrp_field-'.$i, $_POST ) ) {

				$cptrp_related[$i] = intval( $_POST['cptrp_field-'.$i] );

				update_post_meta( 
					$post_id,
					'cptrp-related',
					$cptrp_related
				);

			}

		}

	}

	/**
	 * Get the settings - Active custom post types.
	 *
	 * @since    1.0.0
	 */
	public function cptrp_settings_post_types( $cptrp_options ) {

		$post_types = null;

		$get_post_types = get_post_types( array('public' => true, '_builtin' => false), 'names', 'and' );

		array_unshift($get_post_types, "post");
		
		foreach ( $get_post_types as $get_post_type ) :

			if ( $cptrp_options['cptrp-'.$get_post_type] == 'on') :

				$post_types[] = $get_post_type;

			endif;

		endforeach;

		return $post_types;

	}

}