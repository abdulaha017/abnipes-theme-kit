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



// register javascript and css on initialization
function abnipes_woo_register_script() {

    wp_register_style( 'ab-theme-kit-css', plugins_url('/assets/css/style.css', __FILE__), false, '1.0.0', 'all');
}

add_action('init', 'abnipes_woo_register_script');


// use the registered javascript and css above
function abnipes_woo_enqueue_style(){
    wp_enqueue_style('ab-theme-kit-css');
}

add_action('wp_enqueue_scripts', 'abnipes_woo_enqueue_style');



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



// 

function abnipes_user_contact_method( $methods ) {

    $methods['facebook'] = __( Facebook, 'abnipes-theme-kit' );
    $methods['twitter'] = __( Twitter, 'abnipes-theme-kit' );
    $methods['linkedin'] = __( Linkedin, 'abnipes-theme-kit' );

    return $methods;

}

add_filter( 'user_contactmethods', 'abnipes_user_contact_method' );


function abnipes_author_bio( $content ) {
    global $post;

    $author = get_user_by( 'id', $post->post_author );

    $author_bio = get_user_meta( $author->ID, 'description', true );
    $facebook = get_user_meta( $author->ID, 'facebook', true );
    $twitter = get_user_meta( $author->ID, 'twitter', true );
    $linkedin = get_user_meta( $author->ID, 'linkedin', true );

    ob_start();
    ?>

    <div class="abnipes-bio-wrap">
        <div class="avatar-wrap">
            <?php echo get_avatar( $author->ID, 64 ) ?>
        </div>

        <div class="abnipes-bio-content">
            <div class="author-name">
                <?php echo $author->display_name; ?>
            </div>
            <div class="bio">
                <?php echo wpautop( wp_kses_post( $author_bio ) ); ?>
            </div>
            <ul class="author-social">
                <li><a href="<?php echo esc_url( $facebook ); ?>"><!-- <i class="fa fa-facebook"></i> --> F</a></li>
                <li><a href="<?php echo esc_url( $twitter ); ?>"><!-- <i class="fa fa-twitter"></i> --> T</a></li>
                <li><a href="<?php echo esc_url( $linkedin ); ?>"><!-- <i class="fa fa-linkedin"></i> --> L</a></li>
            </ul>
        </div>
    </div>

    <?php
    $bio_content = ob_get_clean();

    return $content . $bio_content;
}

add_filter( 'the_content', 'abnipes_author_bio' );

