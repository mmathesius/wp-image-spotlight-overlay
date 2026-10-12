<?php
/**
 * Plugin Name: Image Spotlight Overlay Shorthand
 * Description: High-contrast image focus effect that brightens a custom rectangle and dims the background on hover using a shortcode.
 * Version: 1.3
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

// 3. FORCE a custom "View details" link to display beneath the plugin name
function iso_add_view_details_link( $plugin_meta, $plugin_file ) {
    if ( strpos( $plugin_file, 'image-spotlight-overlay.php' ) !== false ) {
        // Targets an inline HTML div id instead of an external wordpress.org URL
        $link = '<a href="#TB_inline?width=700&height=550&inlineId=iso-documentation-modal" class="thickbox" aria-label="View details about this plugin">View details</a>';
        $plugin_meta[] = $link;
    }
    return $plugin_meta;
}
add_filter( 'plugin_row_meta', 'iso_add_view_details_link', 10, 2 );

// 4. Inject a highly reliable inline HTML documentation container directly onto the admin page
function iso_inject_inline_documentation() {
    $screen = get_current_screen();
    // Only load this data on the main plugins administration page to maximize performance
    if ( isset( $screen->id ) && $screen->id === 'plugins' ) {
        add_thickbox(); // Ensure core WordPress modal scripts are loaded
        ?>
        <div id="iso-documentation-modal" style="display:none;">
            <div style="padding:20px; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Oxygen-Sans,Ubuntu,Cantarell,'Helvetica Neue',sans-serif; color:#3c434a; line-height:1.6;">
                <h2 style="border-bottom:1px solid #ccc; padding-bottom:10px; margin-top:0;">Image Spotlight Overlay Shorthand — Documentation</h2>
                
                <h3>Welcome to Image Spotlight Overlay</h3>
                <p>This lightweight plugin adds a dynamic highlight hover effect to your site without any configurations or heavy external scripts. Simply drop the shortcode into any post or page to get started.</p>
                
                <h4>How it works:</h4>
                <ul style="list-style-type:disc; padding-left:20px;">
                    <li>When the page loads, your target image appears perfectly normal.</li>
                    <li>When a visitor moves their cursor <strong>anywhere</strong> over the frame, the background layer dims down while your custom rectangle region brightens seamlessly.</li>
                </ul>

                <h3 style="margin-top:30px; border-bottom:1px solid #ccc; padding-bottom:5px;">Quick Start Guide</h3>
                <p>To use this layout effect, paste the <code>[spotlight_image]</code> shortcode inside your standard WordPress paragraph blocks, shortcode widgets, or page builders.</p>
                
                <h4>Example Shortcode Structure:</h4>
                <pre style="background:#f4f4f4; padding:12px; border-left:4px solid #2271b1; font-family:monospace; display:block; overflow-x:auto; font-size:13px; color:#000;">[spotlight_image src="https://yourwebsite.com" top="25%" left="40%" width="30%" height="20%"]</pre>
                
                <h4 style="margin-top:25px;">Available Shortcode Properties:</h4>
                <table style="width:100%; text-align:left; border-collapse:collapse; margin-top:10px; font-size:13px;">
                    <thead>
                        <tr style="background:#eaeaea; border-bottom:2px solid #ccc;">
                            <th style="padding:10px; border:1px solid #ddd; font-weight:600;">Attribute</th>
                            <th style="padding:10px; border:1px solid #ddd; font-weight:600;">Default Value</th>
                            <th style="padding:10px; border:1px solid #ddd; font-weight:600;">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding:10px; border:1px solid #ddd;"><code>src</code></td>
                            <td style="padding:10px; border:1px solid #ddd; color:#d63638;"><em>(Required)</em></td>
                            <td style="padding:10px; border:1px solid #ddd;">The full direct link address to your target file from the Media Library.</td>
                        </tr>
                        <tr>
                            <td style="padding:10px; border:1px solid #ddd;"><code>top</code></td>
                            <td style="padding:10px; border:1px solid #ddd;"><code>30%</code></td>
                            <td style="padding:10px; border:1px solid #ddd;">Vertical offset placement measured from the top boundary of the picture container.</td>
                        </tr>
                        <tr>
                            <td style="padding:10px; border:1px solid #ddd;"><code>left</code></td>
                            <td style="padding:10px; border:1px solid #ddd;"><code>45%</code></td>
                            <td style="padding:10px; border:1px solid #ddd;">Horizontal offset placement measured from the left boundary of the picture container.</td>
                        </tr>
                        <tr>
                            <td style="padding:10px; border:1px solid #ddd;"><code>width</code></td>
                            <td style="padding:10px; border:1px solid #ddd;"><code>20%</code></td>
                            <td style="padding:10px; border:1px solid #ddd;">The overall width scale sizing profile for your illuminated focus box.</td>
                        </tr>
                        <tr>
                            <td style="padding:10px; border:1px solid #ddd;"><code>height</code></td>
                            <td style="padding:10px; border:1px solid #ddd;"><code>15%</code></td>
                            <td style="padding:10px; border:1px solid #ddd;">The overall height scale sizing profile for your illuminated focus box.</td>
                        </tr>
                        <tr>
                            <td style="padding:10px; border:1px solid #ddd;"><code>alt</code></td>
                            <td style="padding:10px; border:1px solid #ddd;"><code>Interactive Image</code></td>
                            <td style="padding:10px; border:1px solid #ddd;">Optional accessibility descriptive alternative text string properties.</td>
                        </tr>
                    </tbody>
                </table>
                <p style="margin-top:20px; font-style:italic; font-size:12px; color:#646970;">Note: Always keep the percentage (%) symbols inside the tracking attributes to maintain structural responsive layouts across diverse screen sizes.</p>
            </div>
        </div>
        <?php
    }
}
add_action( 'admin_footer', 'iso_inject_inline_documentation' );
