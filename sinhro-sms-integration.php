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

if (!defined("SINHRO_SMS_REMINDER_MESSAGE")) {
  define("SINHRO_SMS_REMINDER_MESSAGE", "This is a reminder that you abandoned a cart on our website");
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
        // activation/deactivation
        register_activation_hook(__FILE__, array($this, "plugin_activate"));
        register_deactivation_hook(__FILE__, array($this, "plugin_deactivate"));

        // frontend hooks
        add_action("init", array($this, "load_plugin_textdomain"));
        add_action("wp_enqueue_scripts", array($this, "wp_enqueue_scripts"));

        // admin hooks
        add_action("admin_menu", array($this, "admin_menu"), 10);
        add_action("admin_init", array($this, "register_sinhro_sms_integration_settings"));
        add_action("admin_init", array($this, "send_test_sms_post"));
        add_action("admin_init", array($this, "check_test_sms_post_request"));

        // woocommerce related hooks
        // create unique cart id for cart
        add_action("woocommerce_init", array($this, "woocommerce_init"), 10);

        // order is processed so remove any temporary references
        add_action("woocommerce_checkout_order_processed", array($this, "woocommerce_order_processed"), 10);

        // add cart unique hidden field to checkout form
        add_action("woocommerce_review_order_after_submit", array($this, "woocommerce_review_order_after_submit"));

        // ajax hooks
        add_action("wp_ajax_record_checkout_info", array($this, "record_checkout_info"));
        add_action("wp_ajax_nopriv_record_checkout_info", array($this, "record_checkout_info"));

        // cron job code
        add_action("wp", array($this, "register_cart_cron_job"));
        add_action("ssi_cart_process_sms", array($this, "cart_process_sms"));
        add_filter("cron_schedules", array($this, "add_cron_interval"));
    }

    public function add_cron_interval($schedules)
    {
        $schedules["five_minutes"] = array(
            "interval" => 5 * 60,
            "display"  => esc_html__("Every Five Minutes", "sinhro-sms-integration")
        );

        return $schedules;
    }

    // send 1 reminder sms 15 after checkout screen reached, another one 24 hours after
    public function cart_process_sms()
    {
        global $wpdb;

        $temp_cart_table_name = $wpdb->prefix . "ssi_temp_cart";

        // process carts that have passed 15 minutes
        $results = $wpdb->get_results("SELECT * FROM $temp_cart_table_name WHERE sms_1_sent=0 AND created < DATE_SUB(NOW(), INTERVAL 15 MINUTE) AND created > DATE_SUB(NOW(), INTERVAL 24 HOUR)");

        if ($results) {
          foreach ($results as $result) {
            if (function_exists("wc_get_cart_url")) {
                $cart_url = wc_get_cart_url();
                $response = $this->send_sms($result->phone, sprintf(esc_html__("Oops! You left something in your cart! You can finish what you started here: %s", "sinhro-sms-integration"), $cart_url), "");

                if ($response && isset($response["body"]) && $response["body"] == "Result_code: 00, Message OK") {
                    error_log("Success, sms sent to $result->phone after 15 minutes");

                    $wpdb->query($wpdb->prepare("UPDATE $temp_cart_table_name SET sms_1_sent=1 WHERE id=%d", $result->id));
                } else {
                    error_log("Error, sms sent not sent to $result->phone after 15 minutes");
                    error_log(serialize($response));
                }
            }
          }
        }

        // process carts that have passed 24 hours
        $results = $wpdb->get_results("SELECT * FROM $temp_cart_table_name WHERE sms_2_sent=0 AND created < DATE_SUB(NOW(), INTERVAL 24 HOUR)");

        if ($results) {
          foreach ($results as $result) {
            if (function_exists("wc_get_cart_url")) {
                $cart_url = wc_get_cart_url();
                $customer_first_name = isset($result->first_name) ? $result->first_name : "";
                $discount_value = get_option("ssi_api_discount_value") ? get_option("ssi_api_discount_value") : "20%";
                $response = $this->send_sms($result->phone, sprintf(esc_html__("Hey %s, get %s OFF your purchase. Hurry, before it expires: %s", "sinhro-sms-integration"), $customer_first_name, $discount_value, $cart_url), "");

                if ($response && isset($response["body"]) && $response["body"] == "Result_code: 00, Message OK") {
                    error_log("Success, sms sent to $result->phone after 24 hours");

                    $wpdb->query($wpdb->prepare("UPDATE $temp_cart_table_name SET sms_2_sent=1 WHERE id=%d", $result->id));
                } else {
                    error_log("Error, sms sent not sent to $result->phone after 24 hours");
                    error_log(serialize($response));
                }
            }
          }
        }

    }

    public function register_cart_cron_job()
    {
        if (! wp_next_scheduled("ssi_cart_process_sms")) {
            wp_schedule_event(time(), "five_minutes", "ssi_cart_process_sms");
        }
    }


    public function woocommerce_review_order_after_submit()
    {
        if (WC()->session) {
            $unique_cart_id = WC()->session->get("cart_unique_id");
            echo "<input type='hidden' id='ssi-unique-cart-id' name='ssi-unique-cart-id' value='$unique_cart_id' />";
        }
    }

    public function wp_enqueue_scripts()
    {
        wp_enqueue_script("singhro-sms-integration-script", plugin_dir_url(__FILE__) . "js/script.js", array("jquery"), SINHRO_SMS_INTEGRATION_VERSION, true);
        wp_localize_script("singhro-sms-integration-script", "ssiAjax", array( "ajaxurl" => admin_url("admin-ajax.php")));
    }

    public function record_checkout_info()
    {
        global $wpdb;

        $nonce_value = isset($_REQUEST["nonce"]) ? $_REQUEST["nonce"] : "";
        $phone = isset($_REQUEST["phone"]) ? sanitize_text_field($_REQUEST["phone"]) : "";
        $first_name = isset($_REQUEST["first_name"]) ? sanitize_text_field($_REQUEST["first_name"]) : "";
        $unique_cart_id = isset($_REQUEST["unique_cart_id"]) ? $_REQUEST["unique_cart_id"] : "";

        if (wp_verify_nonce($nonce_value, "woocommerce-process_checkout")) {
            // nonce passed, we can record the phone number and cart unique id
            $temp_cart_table_name = $wpdb->prefix . "ssi_temp_cart";

            $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $temp_cart_table_name WHERE abandoned_cart_id=%s", $unique_cart_id));

            $phone = str_replace("+", "", $phone);
            if (substr($phone, 0, strlen("00")) == "00") {
                $phone = substr($phone, strlen("00"));
            }

            if (!$row) {
                $wpdb->query($wpdb->prepare("INSERT INTO $temp_cart_table_name (abandoned_cart_id, phone, first_name) VALUES (%s, %s, %s)", $unique_cart_id, $phone, $first_name));
            }
        }

        die();
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
            `abandoned_cart_id` varchar(20) collate utf8_unicode_ci NOT NULL,
            `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `sms_1_sent` BIT NOT NULL DEFAULT 0,
            `sms_2_sent` BIT NOT NULL DEFAULT 0,
            `phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
            `first_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
            PRIMARY KEY  (`id`)
          ) $wcap_collate AUTO_INCREMENT=1 "
        );
    }

    public function plugin_deactivate()
    {
        global $wpdb;

        require_once ABSPATH . "wp-admin/includes/upgrade.php";

        $temp_cart_table_name = $wpdb->prefix . "ssi_temp_cart";
        $wpdb->query("DROP TABLE IF EXISTS " . $temp_cart_table_name);
    }

    public function woocommerce_order_processed($order_id)
    {
        global $wpdb;
        if (WC()->session) {
            $temp_cart_table_name = $wpdb->prefix . "ssi_temp_cart";
            $unique_cart_id = WC()->session->get("cart_unique_id");
            $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $temp_cart_table_name WHERE abandoned_cart_id=%s", $unique_cart_id));

            if ($row) {
                $wpdb->query($wpdb->prepare("DELETE FROM " . $temp_cart_table_name . " WHERE abandoned_cart_id=%s", $unique_cart_id));
            }
        }
    }

    public function woocommerce_init()
    {
        if (is_plugin_active("woocommerce/woocommerce.php") && function_exists("WC")) {
            if (WC()->session) {
                $unique_cart_id = WC()->session->get("cart_unique_id");

                if (is_null($unique_cart_id)) {
                    WC()->session->set("cart_unique_id", uniqid());
                }
            }
        }
    }

    public function send_sms($phone, $text, $override_host) {
        $response = null;

        if ($phone && $text) {
            $body = array(
                "username"    => get_option("ssi_api_username"),
                "password"    => get_option("ssi_api_password"),
                "text"        => sanitize_text_field($text),
                "call-number" => sanitize_text_field($phone),
            );

            $args = array(
                "body"        => $body,
            );

            $api_host = isset($override_host) && !empty($override_host) ? sanitize_text_field($override_host) : "http://gw.sinhro.si/api/http";

            $response = wp_remote_post($api_host, $args);
        }

        return $response;
    }

    public function send_test_sms_post()
    {
        if (isset($_POST["ssi_send_test_sms"]) && isset($_POST["ssi_api_test_message"]) && !empty($_POST["ssi_api_test_message"]) && isset($_POST["ssi_api_test_phone_number"]) && !empty($_POST["ssi_api_test_phone_number"])) {

            $override_api_host = "";
            if (isset($_POST["ssi_api_host"]) && !empty($_POST["ssi_api_host"])) {
              $override_api_host = $_POST["ssi_api_host"];
            }
            $response = $this->send_sms($_POST["ssi_api_test_phone_number"], $_POST["ssi_api_test_message"], $override_api_host);

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
  <p><?php _e("There has been an error when trying to send a test SMS. Please make sure all test SMS fields are filled in before attempting to send!", "sinhro-sms-integration"); ?>
  </p>
</div>
<?php
        }
    }

    public function register_sinhro_sms_integration_settings()
    {
        register_setting("sinhro-sms-integration-settings", "ssi_api_host");
        register_setting("sinhro-sms-integration-settings", "ssi_api_username");
        register_setting("sinhro-sms-integration-settings", "ssi_api_discount_value");
        register_setting("sinhro-sms-integration-settings", "ssi_api_password");
    }

    public function load_plugin_textdomain()
    {
        $this->cart_process_sms();
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
