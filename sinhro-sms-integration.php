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
        add_action("admin_menu", array($this, "admin_menu"), 10);
        add_action("init", array($this, "load_plugin_textdomain"));
        add_action("admin_init", array($this, "register_sinhro_sms_integration_settings"));
        add_action("admin_init", array($this, "send_test_sms_post"));
        add_action("admin_notices", array($this, "check_test_sms_post_request"));

        // woocommerce related hooks
        // Add to cart
        add_action("woocommerce_add_to_cart", array( $this, "cart_update" ), 10);

        // Remove from cart
        add_action("woocommerce_cart_item_removed", array( $this, "cart_update" ), 10);

        // Restore cart item
        add_action("woocommerce_cart_item_restored", array( $this, "cart_update" ), 10);

        // Quantity update
        add_action("woocommerce_after_cart_item_quantity_update", array( $this, "cart_update" ), 10);

        // create unique cart id for cart
        add_action("woocommerce_init", array($this, "woocommerce_init"), 10);
    }

    public function woocommerce_init()
    {
        if (is_plugin_active("woocommerce/woocommerce.php") && function_exists("WC")) {
            if (WC()->session) {
                $new_cart = WC()->session->get("cart_unique_id");

                if (is_null($new_cart)) {
                    WC()->session->set("cart_unique_id", uniqid());
                } else {
                  echo "Cart unique id is " . WC()->session->get("cart_unique_id");
                }
            }
        }
    }

    public function cart_update()
    {
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
