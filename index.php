<?php
/**
 * Plugin Name:       Abnipes Theme Kit
 * Plugin URI:        https://example.com/
 * Description:       Work With WordPress Settings & Customization API.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Abdulaha Islam
 * Author URI:        https://www.linkedin.com/in/abdulaha-islam/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       abnipes-theme-kit
 * Domain Path:       /languages
 */


// Play with WordPress Settings API.


function business_media_settings_init() {
    // register a new setting for "general" page
    register_setting('general', 'business-media-links');
 
    // register a new section in the "general" page
    add_settings_section(
        'business-media-section',
        'Business Media', 
        'business_media_section_callback',
        'general'
    );
 
    // register a new field in the "business-media-section" section, inside the "general" page
    add_settings_field(
        'business-media-field',
        'Linkedin URL', 
        'business_media_field_callback',
        'general',
        'business-media-section'
    );
}

 
/**
 * register business_media_settings_init to the admin_init action hook
 */
add_action('admin_init', 'business_media_settings_init');
 
/**
 * callback functions
 */
 
// section content cb
function business_media_section_callback() {
    echo "<p>". __( 'Business Media Section Introduction.', 'abnipes-theme-kit' ) ."</p>";
}
 
// field content cb
function business_media_field_callback() {
    // get the value of the setting we've registered with register_setting()
    $setting = get_option('business-media-links');
    // output the field
    ?>
    <input type="url" class="regular-text" name="business-media-links" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">
    <?php
}



// Output of WP Settings Value

function business_media_footer() {
	$linkdin_url = get_option('business-media-links');
	echo $linkdin_url;
	?>
	<br/>
	<?php
}

add_action('wp_footer', 'business_media_footer', 10);




// Play with WordPress Customization API.


add_action('customize_register','abnipes_customize_register');
function abnipes_customize_register( $abnipes_customize ) {

	// for developer name
 
	$abnipes_customize->add_section( 'dev-section', 
		array(
			'title' => __( 'Developer Info' ),
			'description' => __( 'Write About Site Developer', 'abnipes-theme-kit' ),
			'capability' => 'edit_theme_options',
		) 
	);
 
	$abnipes_customize->add_setting( 'dev-name-setting', 
		array(
			'default'    => '', 
			'type'       => 'theme_mod', 
			'capability' => 'edit_theme_options', 
			'transport'  => 'postMessage', 
		) 
	); 
 
	$abnipes_customize->add_control( 'dev-name-control', 
		array(
            'label'      => __( 'Developer Name', 'abnipes-theme-kit' ), 
            'type'    	 => 'text', 
            'section'    	 => 'dev-section', 
            'settings'    	 => 'dev-name-setting', 
        )
    );



	// for developer link
 
	$abnipes_customize->add_setting( 'dev-link-setting', 
		array(
			'default'    => '', 
			'type'       => 'theme_mod', 
			'capability' => 'edit_theme_options', 
			'transport'  => 'postMessage', 
		) 
	); 
 
	$abnipes_customize->add_control( 'dev-link-control', 
		array(
            'label'      => __( 'Developer Link', 'abnipes-theme-kit' ), 
            'type'    	 => 'url', 
            'section'    	 => 'dev-section', 
            'settings'    	 => 'dev-link-setting', 
        )
    );

}


// Output of WP Customization Value

function customizer_developer_info() {
	echo get_theme_mod("dev-name-setting");
	?>
	<br/>
	<?php
	echo get_theme_mod("dev-link-setting");
}

add_action('wp_footer', 'customizer_developer_info', 15);





