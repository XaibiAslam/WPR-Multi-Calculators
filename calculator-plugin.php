<?php
/**
 * Plugin Name: WPR Multi Calculators
 * Plugin URI: https://wprevise.com
 * Description: Create and manage multiple types of calculators with shortcodes
 * Version: 1.0.0
 * Author: Xaib Aslam
 * Author URI: https://wprevise.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: wpr-calculators
 * Domain Path: /languages
 * Requires PHP: 7.1
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('WPR_CALC_VERSION', '1.0.0');
define('WPR_CALC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WPR_CALC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WPR_CALC_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Autoload classes
spl_autoload_register(function ($class) {
    $prefix = 'WPR_Calculators\\';
    $base_dir = WPR_CALC_PLUGIN_DIR . 'includes/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    
    // Check if it's a calculator class
    if (strpos($relative_class, 'Calculators\\') === 0) {
        $file = $base_dir . 'calculators/' . str_replace('\\', '/', substr($relative_class, 11)) . '.php';
    } else {
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    }

    if (file_exists($file)) {
        require $file;
    }
});

// Activation hook
register_activation_hook(__FILE__, function() {
    require_once WPR_CALC_PLUGIN_DIR . 'includes/class-calculator-activator.php';
    WPR_Calculators\Calculator_Activator::activate();
});

// Deactivation hook
register_deactivation_hook(__FILE__, function() {
    require_once WPR_CALC_PLUGIN_DIR . 'includes/class-calculator-deactivator.php';
    WPR_Calculators\Calculator_Deactivator::deactivate();
});

// Initialize plugin
function run_wpr_calculators() {
    require_once WPR_CALC_PLUGIN_DIR . 'includes/class-calculator.php';
    $plugin = new WPR_Calculators\Calculator();
    $plugin->run();
}
run_wpr_calculators();