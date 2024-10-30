<?php
if(!class_exists('cptrp_settings')) {

	class cptrp_settings {
		/**
		 * Construct the plugin object
		 */
		public function __construct() {
			
			add_action('admin_init', array(&$this, 'cptrp_admin_init'));
			add_action('admin_menu', array(&$this, 'cptrp_add_menu'));

		}
		
		/**
		 * hook into WP's admin_init action hook
		 */
		public function cptrp_admin_init() {

			global $post_types;

			// Get the custom post types

			$args = array(
				'public'   => true,
				'_builtin' => false
			);

			$output = 'names';

			$operator = 'and';

			$post_types = get_post_types( $args, $output, $operator );

			// Add the general post to the custom post type list
			array_unshift($post_types, "post");

			// Register settings
			register_setting( 'cptrp-group', 'cptrp-options', array(&$this, 'cptrp_validation') );

			// Settings section 1
			add_settings_section(
				'cptrp-section-1', 
				esc_html__( 'Tipologie di post', 'cptrp' ),
				array(&$this, 'cptrp_settings_section_1'), 
				'cptrp'
			);

			// Settings section 2
			add_settings_section(
				'cptrp-section-2', 
				esc_html__( 'Numero di campi', 'cptrp' ),
				array(&$this, 'cptrp_settings_section_2'), 
				'cptrp'
			);

			// Settings section 3
			add_settings_section(
				'cptrp-section-3', 
				esc_html__( 'Titolo personalizzato', 'cptrp' ),
				array(&$this, 'cptrp_settings_section_3'), 
				'cptrp'
			);

			// Settings section 4
			add_settings_section(
				'cptrp-section-4', 
				esc_html__( 'Template personalizzato', 'cptrp' ),
				array(&$this, 'cptrp_settings_section_4'), 
				'cptrp'
			);

			// Add section 1 setting's fields
			foreach ( $post_types  as $post_type ) :

				add_settings_field(
					'cptrp-'.$post_type,
					ucfirst($post_type),
					array(&$this, 'cptrp_settings_field_input_checkbox'), 
					'cptrp', 
					'cptrp-section-1',
					array(
						'label_for'   => 'cptrp-'.$post_type, // makes the field name clickable,
						'name'        => 'cptrp-'.$post_type, // value for 'name' attribute
						'option' => 'cptrp-options'
					)
				);

			endforeach;

			// Add section 2 setting's fields
			add_settings_field(
				'cptrp-n', 
				'Numero campi', 
				array(&$this, 'cptrp_settings_field_input_text'), 
				'cptrp', 
				'cptrp-section-2',
				array(
					'label_for'   => 'cptrp-n', // makes the field name clickable,
					'name'        => 'cptrp-n', // value for 'name' attribute
					'option' => 'cptrp-options'
				)
			);

			// Add section 3 setting's fields
			add_settings_field(
				'cptrp-title', 
				'Titolo', 
				array(&$this, 'cptrp_settings_field_input_text'), 
				'cptrp', 
				'cptrp-section-3',
				array(
					'label_for'   => 'cptrp-title', // makes the field name clickable,
					'name'        => 'cptrp-title', // value for 'name' attribute
					'option' => 'cptrp-options'
				)
			);

			// Add section 4 setting's fields
			add_settings_field(
				'cptrp-path', 
				'Percorso', 
				array(&$this, 'cptrp_settings_field_input_text'), 
				'cptrp', 
				'cptrp-section-4',
				array(
					'label_for'   => 'cptrp-path', // makes the field name clickable,
					'name'        => 'cptrp-path', // value for 'name' attribute
					'option' => 'cptrp-options'
				)
			);
			
		}

		public function cptrp_validation( $values ) {

			global $post_types;

			$default_values = array (
				'cptrp-n' => 3,
				'cptrp-title'  => '',
				'cptrp-path'   => ''
			);

			foreach ( $post_types as $post_type ) :

				$default_values['cptrp-'.$post_type] = '';

			endforeach;

			if ( ! is_array( $values ) )

				return $default_values;

			$out = array ();

			foreach ( $default_values as $key => $value ) {

				if ( empty ( $values[ $key ] ) ) {

					$out[ $key ] = $value;

				} else {

					if ( 'cptrp-n' === $key ) {

						if ( $values[ $key ] < 1 ) :

							add_settings_error(
								'cptrp_option_group',
								'number-too-low',
								esc_html__( 'Il numero deve essere compreso tra 1 e 20', 'cptrp' )
							);

							$out[$key] = $default_values[$key];

						elseif ( $values[ $key ] > 20 ) :

							add_settings_error(
								'cptrp_option_group',
								'number-too-high',
								esc_html__( 'Il numero deve essere compreso tra 1 e 20', 'cptrp' )
							);

							$out[$key] = $default_values[$key];

						else :

							$out[ $key ] = $values[ $key ];

						endif;

					} else {

						$out[ $key ] = wp_filter_nohtml_kses( trim( $values[ $key ] ) );

					}
				}
			}

			return $out;

		} 			
		
		public function cptrp_settings_section_1() {

			_e( 'Attiva CPTRP per i seguenti gruppi di post:', 'cptrp' );
		
		}

		public function cptrp_settings_section_2() {

			_e( 'Il numero di campi per selezionare i post correlati (min. 1, max. 20)', 'cptrp' );
		
		}

		public function cptrp_settings_section_3() {

			_e( 'Il titolo da visualizzare. Se lasciato vuoto non comparirà.', 'cptrp' );
		
		}

		public function cptrp_settings_section_4() {

			_e( "Il percorso per l'utilizzo di uno specifico template.", 'cptrp' );
		
		}
		
		/**
		 * This function provides text inputs for settings fields
		 */
		public function cptrp_settings_field_input_text($args) {

			$value = get_option($args['option']);

			printf(
				'<input name="%1$s[%2$s]" id="%3$s" value="%4$s">',
				$args['option'],
				$args['name'],
				$args['label_for'],
				$value[$args['name']]
			);

		}

		/**
		 * This function provides text inputs for settings fields
		 */
		public function cptrp_settings_field_input_checkbox($args) {
			
			$value = get_option($args['option']);

			printf(
				'<input type="checkbox" id="%3$s" name="%1$s[%2$s] value="%4$s" ' . checked('on', $value[$args['name']], false) . '/>',
				$args['option'],
				$args['name'],
				$args['label_for'],
				$value[$args['name']]
			);

		}
		
		/**
		 * add a menu
		 */		
		public function cptrp_add_menu() {
			// Add a page to manage this plugin's settings
			add_options_page(
				esc_html__( 'CPTRP - Impostazioni', 'cptrp' ),
				'CPTRP', 
				'manage_options', 
				'cptrp', 
				array(&$this, 'cptrp_plugin_settings_page')
			);
		}
	
		/**
		 * Menu Callback
		 */		
		public function cptrp_plugin_settings_page() {
			if(!current_user_can('manage_options'))
			{
				wp_die(__('You do not have sufficient permissions to access this page.'));
			}
	
			// Render the settings template
			include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
		}
	} // END class cptrp_Settings
}