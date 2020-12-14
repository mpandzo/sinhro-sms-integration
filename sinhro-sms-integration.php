<?php
/*
Plugin Name:  Sinhro Sms Integration
Plugin URI:   https://github.com/mpandzo/sinhro-sms-integration
Description:  A WordPress plugin that allows integration with the http://gw.sinhro.si/api/http api for sending SMSs
Version:      1.0.0
Author:       mpandzo
Author URI:   https://mthit.com
License:      MIT License
*/

namespace MPandzo\SinhroSmsIntegration;

defined("ABSPATH") || exit; // Exit if accessed directly

if (!defined("SINHRO_SMS_INTEGRATION_VERSION")) {
    define("SINHRO_SMS_INTEGRATION_VERSION", "1.0.0");
}

class SinhroSmsIntegration
{
    private $plugin_name = "SinhroSmsIntegration";

    public function __construct()
    {
        // Check if WooCommerce is active
        require_once(ABSPATH . "/wp-admin/includes/plugin.php");
        if (!is_plugin_active("woocommerce/woocommerce.php") && !function_exists("WC")) {
            return false;
        }

        $this->hooks();
    }

    public function hooks()
    {
        register_activation_hook(__FILE__, array($this, "plugin_activate"));
        register_deactivation_hook(__FILE__, array($this, "plugin_deactivate"));

        add_action("admin_menu", array($this, "admin_menu"), 10);
        add_action("init", array($this, "load_plugin_textdomain"));
        add_action("admin_init", array($this, "register_sinhro_sms_integration_settings"));
        add_action("admin_init", array($this, "send_test_sms_post"));
        add_action("admin_notices", array($this, "check_test_sms_post_request"));

        // woocommerce related hooks
        // create unique cart id for cart
        add_action("woocommerce_init", array($this, "woocommerce_init"), 10);

        // order is processed so remove any temporary references
        add_action("woocommerce_checkout_order_processed", array($this, "woocommerce_order_processed"), 10);
    }

    public function plugin_activate()
    {
        global $wpdb;

        $wcap_collate = "";
        if ($wpdb->has_cap("collation")) {
            $wcap_collate = $wpdb->get_charset_collate();
        }

        $temp_cart_table_name = $wpdb->prefix . "ssi_temp_cart";
        $wpdb->query( // phpcs:ignore
          "CREATE TABLE IF NOT EXISTS $temp_cart_table_name (
            `id` int(11) NOT NULL auto_increment,
            `abandone_cart_id` varchar(20) collate utf8_unicode_ci NOT NULL,
            `abandoned_order_id` int(11) NOT NULL,
            `time` TIMESTAMP NOT NULL,
            `phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
            PRIMARY KEY  (`id`)
          ) $wcap_collate AUTO_INCREMENT=1 "
        );
    }

    public function plugin_deactivate()
    {
        global $wpdb;

        require_once ABSPATH . "wp-admin/includes/upgrade.php";

        $temp_cart_table_name = $wpdb->prefix . "ssi_temp_cart";
        $wpdb->query("DROP TABLE " . $temp_cart_table_name);
    }

    public function woocommerce_order_processed($order_id)
    {
    }

    public function woocommerce_init()
    {
        if (is_plugin_active("woocommerce/woocommerce.php") && function_exists("WC")) {
            if (WC()->session) {
                $new_cart = WC()->session->get("cart_unique_id");

                if (is_null($new_cart)) {
                    WC()->session->set("cart_unique_id", uniqid());
                }
            }
        }
    }

    public function send_test_sms_post()
    {
        if (isset($_POST["ssi_send_test_sms"]) && isset($_POST["ssi_api_test_message"]) && !empty($_POST["ssi_api_test_message"]) && isset($_POST["ssi_api_test_phone_number"]) && !empty($_POST["ssi_api_test_phone_number"])) {
            $body = array(
          "username"    => get_option("ssi_api_username"),
          "password"    => get_option("ssi_api_password"),
          "text"        => sanitize_text_field($_POST["ssi_api_test_message"]),
          "call-number" => sanitize_text_field($_POST["ssi_api_test_phone_number"]),
        );

            $args = array(
          "body"        => $body,
        );

            $api_host = isset($_POST["ssi_api_host"]) && !empty($_POST["ssi_api_host"]) ? sanitize_text_field($_POST["ssi_api_host"]) : "http://gw.sinhro.si/api/http";

            $response = wp_remote_post($api_host, $args);

            if ($response && isset($response["body"]) && $response["body"] == "Result_code: 00, Message OK") {
                ?>
<div class="updated notice">
  <p><?php _e("Success. Test SMS sent!", "sinhro-sms-integration"); ?>
  </p>
</div>
<?php
            } else {
                error_log(serialize($response)); ?>
<div class="error notice">
  <p><?php _e("Error. Test SMS failed to send!", "sinhro-sms-integration"); ?>
  </p>
  <textarea rows="10" style="width:100%;margin-bottom:20px;" disabled>
            <?php print_r($response); ?>
          </textarea>
  <br />
</div>
<?php
            }
        }
    }

    public function check_test_sms_post_request()
    {
        if (isset($_POST["ssi_send_test_sms"]) && (!isset($_POST["ssi_api_test_message"]) || empty($_POST["ssi_api_test_message"]) || !isset($_POST["ssi_api_test_phone_number"]) || empty($_POST["ssi_api_test_phone_number"]))) {
            ?>
<div class="error notice">
  <p><?php _e("There has been an error. Please make sure all test SMS fields are filled in before attempting to send test SMS!", "sinhro-sms-integration"); ?>
  </p>
</div>
<?php
        }
    }

    public function register_sinhro_sms_integration_settings()
    {
        register_setting("sinhro-sms-integration-settings", "ssi_api_host");
        register_setting("sinhro-sms-integration-settings", "ssi_api_username");
        register_setting("sinhro-sms-integration-settings", "ssi_api_password");
    }

    public function load_plugin_textdomain()
    {
        load_plugin_textdomain("sinhro-sms-integration", false, dirname(plugin_basename(__FILE__)) . "/languages");
    }

    public function admin_menu()
    {
        add_menu_page($this->plugin_name, __("Sinhro Sms Integration", "sinhro-sms-integration"), "administrator", $this->plugin_name, array($this, "display_plugin_dashboard" ), "dashicons-admin-network", 20);
    }

    public function display_plugin_dashboard()
    {
        require_once plugin_dir_path(__FILE__) . "/partials/admin-settings.php";
    }
}

new SinhroSmsIntegration();
