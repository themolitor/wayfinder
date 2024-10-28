<?php

/* Plugin Name: Wayfinder
 * Plugin URI: https://themolitor.com/wayfinder
 * Description: Easily select and identify nested blocks in the editor.
 * Version: 1.2.0
 * Author: THE MOLITOR
 * Author URI: https://themolitor.com
 * Text Domain: wayfinder
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

$wayfinder_version = '1.2.0';


//SET DEFAULTS ON ACTIVATION
register_activation_hook( __FILE__, 'wayfinder_activated' );
function wayfinder_activated(){
	
	update_option('wayfinder_option_name',array(
		'display_block_title_0' => 'display_block_title_0',
		'display_block_classes_1' => 'display_block_classes_1',
		'display_block_outline_2' => 'display_block_outline_2',
		'display_spacer_outline_3' => 'display_spacer_outline_3'	
	));
}


//SETUP PLUGIN OPTIONS
class Wayfinder {
	private $wayfinder_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wayfinder_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'wayfinder_page_init' ) );
	}

	public function wayfinder_add_plugin_page() {
		add_options_page(
			'Wayfinder', // page_title
			'Wayfinder', // menu_title
			'manage_options', // capability
			'wayfinder', // menu_slug
			array( $this, 'wayfinder_create_admin_page' ) // function
		);
	}

	public function wayfinder_create_admin_page() {
		$this->wayfinder_options = get_option( 'wayfinder_option_name' ); ?>

		<div class="wrap">
		<br />
			<?php //settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'wayfinder_option_group' );
					do_settings_sections( 'wayfinder-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function wayfinder_page_init() {
		register_setting(
			'wayfinder_option_group', // option_group
			'wayfinder_option_name', // option_name
			array( $this, 'wayfinder_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'wayfinder_setting_section', // id
			__('Wayfinder Settings','wayfinder'), // title
			array( $this, 'wayfinder_section_info' ), // callback
			'wayfinder-admin' // page
		);

		add_settings_field(
			'display_block_title_0', // id
			__('Display block type','wayfinder'), // title
			array( $this, 'display_block_title_0_callback' ), // callback
			'wayfinder-admin', // page
			'wayfinder_setting_section' // section
		);

		add_settings_field(
			'display_block_classes_1', // id
			__('Display block classes','wayfinder'), // title
			array( $this, 'display_block_classes_1_callback' ), // callback
			'wayfinder-admin', // page
			'wayfinder_setting_section' // section
		);

		add_settings_field(
			'display_block_outline_2', // id
			__('Display block outline','wayfinder'), // title
			array( $this, 'display_block_outline_2_callback' ), // callback
			'wayfinder-admin', // page
			'wayfinder_setting_section' // section
		);
		
		add_settings_field(
			'display_spacer_outline_3', // id
			__('Display spacer outline','wayfinder'), // title
			array( $this, 'display_spacer_outline_3_callback' ), // callback
			'wayfinder-admin', // page
			'wayfinder_setting_section' // section
		);
	}

	public function wayfinder_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['display_block_title_0'] ) ) {
			$sanitary_values['display_block_title_0'] = $input['display_block_title_0'];
		}

		if ( isset( $input['display_block_classes_1'] ) ) {
			$sanitary_values['display_block_classes_1'] = $input['display_block_classes_1'];
		}

		if ( isset( $input['display_block_outline_2'] ) ) {
			$sanitary_values['display_block_outline_2'] = $input['display_block_outline_2'];
		}
		
		if ( isset( $input['display_spacer_outline_3'] ) ) {
			$sanitary_values['display_spacer_outline_3'] = $input['display_spacer_outline_3'];
		}

		return $sanitary_values;
	}

	public function wayfinder_section_info() {
		
	}

	public function display_block_title_0_callback() {
		printf(
			'<input type="checkbox" name="wayfinder_option_name[display_block_title_0]" id="display_block_title_0" value="display_block_title_0" %s>',
				( isset( $this->wayfinder_options['display_block_title_0'] ) && $this->wayfinder_options['display_block_title_0'] === 'display_block_title_0' ) ? 'checked' : ''
		);
	}

	public function display_block_classes_1_callback() {
		printf(
			'<input type="checkbox" name="wayfinder_option_name[display_block_classes_1]" id="display_block_classes_1" value="display_block_classes_1" %s>',
				( isset( $this->wayfinder_options['display_block_classes_1'] ) && $this->wayfinder_options['display_block_classes_1'] === 'display_block_classes_1' ) ? 'checked' : ''
		);
	}

	public function display_block_outline_2_callback() {
		printf(
			'<input type="checkbox" name="wayfinder_option_name[display_block_outline_2]" id="display_block_outline_2" value="display_block_outline_2" %s>',
			( isset( $this->wayfinder_options['display_block_outline_2'] ) && $this->wayfinder_options['display_block_outline_2'] === 'display_block_outline_2' ) ? 'checked' : ''

		);
	}
	
	public function display_spacer_outline_3_callback() {
		printf(
			'<input type="checkbox" name="wayfinder_option_name[display_spacer_outline_3]" id="display_spacer_outline_3" value="display_spacer_outline_3" %s>',
			( isset( $this->wayfinder_options['display_spacer_outline_3'] ) && $this->wayfinder_options['display_spacer_outline_3'] === 'display_spacer_outline_3' ) ? 'checked' : ''
		);
	}

}

//WAYFINDER INIT
if ( is_admin() ){
	$wayfinder = new Wayfinder();
}

//USER PROFILE SETTINGS
add_action( 'show_user_profile', 'wayfinder_profile_fields' );
add_action( 'edit_user_profile', 'wayfinder_profile_fields' );

function wayfinder_profile_fields( $user ) {
	
	$selected_option = 'selected="selected"';
	
	$user_block_type = get_the_author_meta( 'userblocktype', $user->ID );
	$user_block_classes = get_the_author_meta( 'userblockclasses', $user->ID );
	$user_block_outline = get_the_author_meta( 'userblockoutline', $user->ID );
	$user_spacer_outline = get_the_author_meta( 'userspaceroutline', $user->ID );

	echo '<br /><h3>Wayfinder Settings</h3>'.

	'<table class="form-table">'.

		//TYPE
		'<tr>'.
			'<th><label for="userblocktype">' . esc_html__('Block type', 'wayfinder') . '</label></th>'.

			'<td>'.
				'<select type="text" name="userblocktype" id="userblocktype">';?>
					<option value="site-default" <?php echo ($user_block_type == 'site-default' || !$user_block_type) ? esc_attr($selected_option) : ''; ?>><?php esc_html_e('Site Default', 'wayfinder'); ?></option>
					<option value="show" <?php echo ($user_block_type == 'show') ? esc_attr($selected_option) : ''; ?>><?php esc_html_e('Show', 'wayfinder'); ?></option>
					<option value="hide" <?php echo ($user_block_type == 'hide') ? esc_attr($selected_option) : ''; ?>><?php esc_html_e('Hide', 'wayfinder'); ?></option>
				<?php echo '</select>'.
			'</td>'.
		'</tr>'.
		
		//CLASSES
		'<tr>'.
			'<th><label for="userblockclasses">' . esc_html__('Block classes', 'wayfinder') . '</label></th>'.
			'<td>'.
				'<select type="text" name="userblockclasses" id="userblockclasses">';
					?>
					<option value="site-default" <?php selected($user_block_classes, 'site-default'); selected($user_block_classes, ''); ?>><?php esc_html_e('Site Default', 'wayfinder'); ?></option>
					<option value="show" <?php selected($user_block_classes, 'show'); ?>><?php esc_html_e('Show', 'wayfinder'); ?></option>
					<option value="hide" <?php selected($user_block_classes, 'hide'); ?>><?php esc_html_e('Hide', 'wayfinder'); ?></option>
					<?php
				echo '</select>'.
			'</td>'.
		'</tr>'.
		
		//OUTLINE
		 '<tr>'.
			'<th><label for="userblockoutline">' . esc_html__('Block outline', 'wayfinder') . '</label></th>'.
			'<td>'.
				'<select type="text" name="userblockoutline" id="userblockoutline">';
					?>
					<option value="site-default" <?php selected($user_block_outline, 'site-default'); selected($user_block_outline, ''); ?>><?php esc_html_e('Site Default', 'wayfinder'); ?></option>
					<option value="show" <?php selected($user_block_outline, 'show'); ?>><?php esc_html_e('Show', 'wayfinder'); ?></option>
					<option value="hide" <?php selected($user_block_outline, 'hide'); ?>><?php esc_html_e('Hide', 'wayfinder'); ?></option>
					<?php
				echo '</select>'.
			'</td>'.
		'</tr>'.
		
		//SPACER OUTLINE
		'<tr>'.
			'<th><label for="userspaceroutline">' . esc_html__('Spacer outline', 'wayfinder') . '</label></th>'.
			'<td>'.
				'<select type="text" name="userspaceroutline" id="userspaceroutline">';
					?>
					<option value="site-default" <?php selected($user_spacer_outline, 'site-default'); selected($user_spacer_outline, ''); ?>><?php esc_html_e('Site Default', 'wayfinder'); ?></option>
					<option value="show" <?php selected($user_spacer_outline, 'show'); ?>><?php esc_html_e('Show', 'wayfinder'); ?></option>
					<option value="hide" <?php selected($user_spacer_outline, 'hide'); ?>><?php esc_html_e('Hide', 'wayfinder'); ?></option>
					<?php
				echo '</select>'.
			'</td>'.
		'</tr>'.

	'</table><br />';
}

//SAVE USER PROFILE SETTINGS
add_action( 'personal_options_update', 'wayfinder_save_profile_fields' );
add_action( 'edit_user_profile_update', 'wayfinder_save_profile_fields' );


function wayfinder_save_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }

    // Verify nonce
    if ( !isset( $_POST['wayfinder_profile_nonce'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wayfinder_profile_nonce'] ) ), 'wayfinder_profile_update' ) ) {
	    return false;
	}

    $fields = ['userblocktype', 'userblockclasses', 'userblockoutline', 'userspaceroutline'];

    foreach ( $fields as $field ) {
        if ( isset( $_POST[$field] ) ) {
            $value = sanitize_text_field( wp_unslash( $_POST[$field] ) );
            update_user_meta( $user_id, $field, $value );
        }
    }
}

//CREATE NONCE FIELD
function wayfinder_add_profile_nonce_field() {
    wp_nonce_field( 'wayfinder_profile_update', 'wayfinder_profile_nonce' );
}
add_action( 'show_user_profile', 'wayfinder_add_profile_nonce_field' );
add_action( 'edit_user_profile', 'wayfinder_add_profile_nonce_field' );

	
//ADD WAYFINDER CLASSES TO ADMIN BODY TAG
add_filter( 'admin_body_class', 'custom_admin_body_class' );
function custom_admin_body_class( $classes ) {
	
	//SITE DEFAULTS
	$wayfinder_options = get_option( 'wayfinder_option_name' );
	
	//USER SETTINGS
	$current_user_id = get_current_user_id();
	$user_block_type = get_the_author_meta( 'userblocktype', $current_user_id );
		if($user_block_type == 'show'){
			$display_block_title_0 = ' wayfinder-title ';
		} else if($user_block_type == 'hide'){
			$display_block_title_0 = '';
		} else {
			$display_block_title_0 = isset($wayfinder_options['display_block_title_0'])
				? ' wayfinder-title '
				: '';
		}
	$user_block_classes = get_the_author_meta( 'userblockclasses', $current_user_id );
		if($user_block_classes == 'show'){
			$display_block_classes_1 = ' wayfinder-classes ';
		} else if($user_block_classes == 'hide'){
			$display_block_classes_1 = '';
		} else {
			$display_block_classes_1 = isset($wayfinder_options['display_block_classes_1'])
				? ' wayfinder-classes '
				: '';
		}
	$user_block_outline = get_the_author_meta( 'userblockoutline', $current_user_id );
		if($user_block_outline == 'show'){
			$display_block_outline_2 = ' wayfinder-outline ';
		} else if($user_block_outline == 'hide'){
			$display_block_outline_2 = '';
		} else {
			$display_block_outline_2 = isset($wayfinder_options['display_block_outline_2'])
				? ' wayfinder-outline '
				: '';
		}
	$user_spacer_outline = get_the_author_meta( 'userspaceroutline', $current_user_id );
		if($user_spacer_outline == 'show'){
			$display_spacer_outline_3 = ' wayfinder-spacer-outline ';
		} else if($user_spacer_outline == 'hide'){
			$display_spacer_outline_3 = '';
		} else {
			$display_spacer_outline_3 = isset($wayfinder_options['display_spacer_outline_3'])
				? ' wayfinder-spacer-outline '
				: '';
		}
		
	$wayfinder_classses = $display_block_title_0.$display_block_classes_1.$display_block_outline_2.$display_spacer_outline_3;
	
    $classes .= $wayfinder_classses;

	return $classes;
}


//ADD EDITOR STYLE CSS
function enqueue_wayfinder() {

	global $wayfinder_version; 
    wp_enqueue_style( 'wayfinder_css',plugin_dir_url( __FILE__ ).'css/editor-style.css', array(), $wayfinder_version);
    wp_enqueue_script('wayfinder_toggle', plugin_dir_url(__FILE__) . 'js/wayfinder-toggle.js', array('wp-data'), $wayfinder_version, true);
}
add_action( 'admin_enqueue_scripts', 'enqueue_wayfinder' );
