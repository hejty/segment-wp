<?php
namespace Segment\Admin;
use function Segment\module as module;

/* Segment Settings Page */
class Settings_Page{

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'create_settings' ) );
		add_action( 'admin_init', array( $this, 'setup_sections' ) );
		add_action( 'admin_init', array( $this, 'setup_fields' ) );
    }

	public function create_settings() {

		$page_title = 'Segment Analytics';
		$menu_title = 'Segment';
		$capability = 'manage_options';
        $slug = 'segment';
        $icon = module()->get_url() . '/assets/images/menu-icon.png';
        $position = 100;
		$callback = array($this, 'settings_content');

		add_menu_page($page_title, $menu_title, $capability, $slug, $callback, $icon, $position);

	}

	public function settings_content() { ?>

		<div class="wrap">
			<h1>Segment </h1>
			<?php settings_errors(); ?>
			<form method="POST" action="options.php">
				<?php
					settings_fields( 'segment' );
					do_settings_sections( 'segment' );
					submit_button();
				?>
			</form>
		</div> <?php
	}

	public function setup_sections() {

		add_settings_section( 'segment_section', 'API Keys', array(), 'segment' );

	}

	public function setup_fields() {

		$fields = array(
			array(
				'label' => 'Segment Source API Key',
				'id' => 'segment_api_key',
				'type' => 'text',
				'section' => 'segment_section',
				'desc' => 'The API key for the desired Segment source ',
				'placeholder'	=> '',
			),

			array(

				'label' => 'Intercom Secure Mode Secret Key',
				'id' => 'segment_intercom_secure_mode_key',
				'type' => 'text',
                'section' => 'segment_section',
				'desc' => 'The secret key for Intercom\'s Secure Mode',
				'placeholder'	=> '',

			),

		);

		foreach( $fields as $field ){

			add_settings_field( $field['id'], $field['label'], array( $this, 'field_callback' ), 'segment', $field['section'], $field );
			register_setting( 'segment', $field['id'] );

		}
	}

	public function field_callback( $field ) {

		$value = get_option( $field['id'] );

		switch ( $field['type'] ) {

			default:

				printf( '<input class="regular-text" name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />',
					$field['id'],
					$field['type'],
					$field['placeholder'],
					$value
				);

		}

		if( $desc = $field['desc'] ) {
			printf( '<p class="description">%s </p>', $desc );
		}

	}
}
new Settings_Page();