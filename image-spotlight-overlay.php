<?php
/**
 * Plugin Name: Image Spotlight Overlay Shorthand
 * Description: High-contrast image focus effect that brightens a custom rectangle and dims the background on hover using a shortcode.
 * Version: 1.1
 * Author: AI Assistant
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

    $box_styles = sprintf(
        'top: %s; left: %s; width: %s; height: %s;',
        esc_attr( $a['top'] ),
        esc_attr( $a['left'] ),
        esc_attr( $a['width'] ),
        esc_attr( $a['height'] )
    );

    $output = '<div class="custom-image-container">';
    $output .= '<img src="' . esc_url( $a['src'] ) . '" alt="' . esc_attr( $a['alt'] ) . '">';
    $output .= '<div class="target-highlight-area" style="' . $box_styles . '"></div>';
    $output .= '</div>';

    return $output;
}
add_shortcode( 'spotlight_image', 'iso_render_shortcode' );

// 3. Inject Documentation into the "View details" Modal
function iso_plugin_documentation_tabs( $false, $action, $args ) {
    // Check if the request is targeting this specific plugin block
    if ( isset( $args->slug ) && $args->slug === 'image-spotlight-overlay' ) {
        
        $obj = new stdClass();
        $obj->name = 'Image Spotlight Overlay Shorthand';
        $obj->slug = 'image-spotlight-overlay';
        $obj->version = '1.1';
        $obj->author = 'AI Assistant';
        
        // Tab Content Layout
        $obj->sections = array(
            'description' => '
                <h3>Welcome to Image Spotlight Overlay</h3>
                <p>This lightweight plugin adds a dynamic highlight hover effect to your site without any configuration screens or heavy scripts. Simply drop the shortcode into any post or page to get started.</p>
                <h4>How it works:</h4>
                <ul>
                    <li>When the page loads, your target image appears perfectly normal.</li>
                    <li>When a visitor moves their cursor <strong>anywhere</strong> over the frame, the background layer dims down while your custom rectangle region brightens seamlessly.</li>
                </ul>
            ',
            'installation_&_usage' => '
                <h3>Quick Start Guide</h3>
                <p>To use this layout effect, paste the <code>[spotlight_image]</code> shortcode inside your standard WordPress paragraph blocks, shortcode widgets, or page builders.</p>
                
                <h4>Example Shortcode Structure:</h4>
                <pre style="background:#f4f4f4; padding:10px; border-left:4px solid #0073aa; font-family:monospace; display:block; overflow-x:auto;">[spotlight_image src="https://yourwebsite.com" top="25%" left="40%" width="30%" height="20%"]</pre>
                
                <h4 style="margin-top:20px;">Available Shortcode Properties:</h4>
                <table class="widefat fixed" style="width:100%; text-align:left; border-collapse:collapse; margin-top:10px;">
                    <thead>
                        <tr style="background:#eee;">
                            <th style="padding:8px; border:1px solid #ddd;">Attribute</th>
                            <th style="padding:8px; border:1px solid #ddd;">Default Value</th>
                            <th style="padding:8px; border:1px solid #ddd;">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding:8px; border:1px solid #ddd;"><code>src</code></td>
                            <td style="padding:8px; border:1px solid #ddd;"><em>(Required)</em></td>
                            <td style="padding:8px; border:1px solid #ddd;">The full direct link address to your target file from the Media Library.</td>
                        </tr>
                        <tr>
                            <td style="padding:8px; border:1px solid #ddd;"><code>top</code></td>
                            <td style="padding:8px; border:1px solid #ddd;"><code>30%</code></td>
                            <td style="padding:8px; border:1px solid #ddd;">Vertical offset placement measured from the top boundary of the picture container.</td>
                        </tr>
                        <tr>
                            <td style="padding:8px; border:1px solid #ddd;"><code>left</code></td>
                            <td style="padding:8px; border:1px solid #ddd;"><code>45%</code></td>
                            <td style="padding:8px; border:1px solid #ddd;">Horizontal offset placement measured from the left boundary of the picture container.</td>
                        </tr>
                        <tr>
                            <td style="padding:8px; border:1px solid #ddd;"><code>width</code></td>
                            <td style="padding:8px; border:1px solid #ddd;"><code>20%</code></td>
                            <td style="padding:8px; border:1px solid #ddd;">The overall width scale sizing profile for your illuminated focus box.</td>
                        </tr>
                        <tr>
                            <td style="padding:8px; border:1px solid #ddd;"><code>height</code></td>
                            <td style="padding:8px; border:1px solid #ddd;"><code>15%</code></td>
                            <td style="padding:8px; border:1px solid #ddd;">The overall height scale sizing profile for your illuminated focus box.</td>
                        </tr>
                        <tr>
                            <td style="padding:8px; border:1px solid #ddd;"><code>alt</code></td>
                            <td style="padding:8px; border:1px solid #ddd;"><code>Interactive Image</code></td>
                            <td style="padding:8px; border:1px solid #ddd;">Optional accessibility descriptive alternative text string properties.</td>
                        </tr>
                    </tbody>
                </table>
                <p style="margin-top:15px; font-style:italic;">Note: Always keep the percentage (%) symbols inside the tracking attributes to maintain structural responsive layouts across diverse screen sizes.</p>
            '
        );
        
        return $obj;
    }
    return $false;
}
add_filter( 'plugins_api', 'iso_plugin_documentation_tabs', 10, 3 );

