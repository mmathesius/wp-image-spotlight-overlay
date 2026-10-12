<?php
/**
 * Plugin Name: Image Spotlight Overlay Shorthand
 * Description: High-contrast image focus effect that brightens a custom rectangle and dims the background on hover using a shortcode.
 * Version: 1.0
 * Author: AI Assistant
 *
 * INSTALLATION
 * 1. Compress your image-spotlight-overlay.php file into a standard .zip folder named image-spotlight-overlay.zip.
 * 2. Go to your WordPress Dashboard and navigate to Plugins > Add New Plugin.
 * 3. Click the Upload Plugin button at the top of the window.
 * 4. Select your .zip file, click Install Now, and then click Activate Plugin.
 * 
 * USAGE
 * Insert this effect into any page, post, or widget area using the standard WordPress text blocks by pasting a quick shortcode line.
 * You pass your image link and coordinates as simple attributes like this:
 *   [spotlight_image src="https://yourwebsite.com" top="25%" left="40%" width="30%" height="20%"]
 *
 * PLUGIN ATTRIBUTES
 *   src: Paste the direct URL of your image from your Media Library.
 *   top / left: Controls where the small spotlight rectangle is positioned inside the frame.
 *   width / height: Sets the size dimensions of the rectangle.
 * (Note: Always remember to include the % symbol in your attribute text so the coordinates remain flexible across responsive phone or monitor displays!)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// 1. Automatically inject the CSS styles into the site header
function iso_enqueue_styles() {
    ?>
    <style>
    .custom-image-container {
        position: relative;
        display: inline-block;
        line-height: 0;
        background-color: #000;
    }
    .custom-image-container img {
        width: 100%;
        height: auto;
        transition: filter 0.4s ease;
    }
    .custom-image-container .target-highlight-area {
        position: absolute;
        border: 1px solid rgba(255, 255, 255, 0.4); 
        box-shadow: 0 0 15px rgba(255, 255, 255, 0.1);
        opacity: 0;
        transition: all 0.4s ease;
        pointer-events: none; 
    }
    .custom-image-container:hover img {
        filter: brightness(0.7);
    }
    .custom-image-container:hover .target-highlight-area {
        opacity: 1;
        backdrop-filter: brightness(1.3);
        -webkit-backdrop-filter: brightness(1.3);
    }
    </style>
    <?php
}
add_action( 'wp_head', 'iso_enqueue_styles' );

// 2. Create the Shortcode [spotlight_image]
function iso_render_shortcode( $atts ) {
    // Merge user parameters with default settings
    $a = shortcode_atts( array(
        'src'    => '',
        'top'    => '30%',
        'left'   => '45%',
        'width'  => '20%',
        'height' => '15%',
        'alt'    => 'Interactive Image'
    ), $atts );

    if ( empty( $a['src'] ) ) {
        return '<p style="color:red;">Error: Please provide an image source URL inside the shortcode.</p>';
    }

    // Build the dynamic inline coordinates for the specific block
    $box_styles = sprintf(
        'top: %s; left: %s; width: %s; height: %s;',
        esc_attr( $a['top'] ),
        esc_attr( $a['left'] ),
        esc_attr( $a['width'] ),
        esc_attr( $a['height'] )
    );

    // Output the structural layout markup safely
    $output = '<div class="custom-image-container">';
    $output .= '<img src="' . esc_url( $a['src'] ) . '" alt="' . esc_attr( $a['alt'] ) . '">';
    $output .= '<div class="target-highlight-area" style="' . $box_styles . '"></div>';
    $output .= '</div>';

    return $output;
}
add_shortcode( 'spotlight_image', 'iso_render_shortcode' );

