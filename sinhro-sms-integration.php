<?php
/*
Plugin Name:  Sinhro Integration
Plugin URI:   https://github.com/mpandzo/sinhro-sms-integration
Description:  A WordPress plugin that allows integration with the http://gw.sinhro.si/api/http api for sending SMSs
Version:      1.0.3
Author:       adstar
Author URI:   https://adstar-agency.com
License:      MIT License
*/

namespace Adstar\SinhroIntegration;

defined("ABSPATH") || exit; // Exit if accessed directly

# Include the Autoloader (see "Libraries" for install instructions)
require __DIR__ . "/mandrill/vendor/autoload.php";

if (!defined("SINHRO_INTEGRATION_VERSION")) {
    define("SINHRO_INTEGRATION_VERSION", "1.0.3");
}

if (!defined("SINHRO_INTEGRATION_CART_TABLE_NAME")) {
  define("SINHRO_INTEGRATION_CART_TABLE_NAME", "ssi_temp_cart");
}

class SinhroIntegration
{
    private $plugin_name = "SinhroIntegration";
    private $plugin_log_file = "";

    public function __construct()
    {
        $this->plugin_log_file = plugin_dir_path(__FILE__) . "ssi-debug.log";

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
        add_action("admin_init", array($this, "send_test_sms"));
        add_action("admin_init", array($this, "send_test_email"));

        // woocommerce related hooks
        // create unique cart id for cart
        add_action("woocommerce_init", array($this, "woocommerce_init"), 10);

        // order is processed so remove any temporary references
        add_action("woocommerce_checkout_order_processed", array($this, "woocommerce_order_processed"), 10);

        // add cart unique hidden field to checkout form
        add_action("woocommerce_review_order_after_submit", array($this, "woocommerce_review_order_after_submit"));

        // ajax hooks
        add_action("wp_ajax_save_checkout_info", array($this, "save_checkout_info"));
        add_action("wp_ajax_nopriv_save_checkout_info", array($this, "save_checkout_info"));

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

    public function get_email_step_1_cart_entries($interval_minutes) {
      return $this->get_step_x_cart_entries(sprintf("(email_1_sent=0 AND email_address!='') AND email_2_sent=0 AND email_3_sent=0 AND
        created < DATE_SUB(NOW(), INTERVAL %d MINUTE)", $interval_minutes));
    }

    public function get_email_step_2_cart_entries($interval_minutes) {
      return $this->get_step_x_cart_entries(sprintf("email_1_sent=1 AND (email_2_sent=0 AND email_address!='') AND email_3_sent=0 AND
        created < DATE_SUB(NOW(), INTERVAL %d MINUTE)", $interval_minutes));
    }

    public function get_email_step_3_cart_entries($interval_minutes) {
      return $this->get_step_x_cart_entries(sprintf("email_1_sent=1 AND email_2_sent=1 AND (email_3_sent=0 AND email_address != '') AND
        created < DATE_SUB(NOW(), INTERVAL %d MINUTE)", $interval_minutes));
    }

    public function get_sms_step_1_cart_entries($interval_minutes) {
      return $this->get_step_x_cart_entries(sprintf("sms_1_sent=0 AND sms_send_errors < 3 AND
        created < DATE_SUB(NOW(), INTERVAL %d MINUTE)", $interval_minutes));
    }

    public function get_sms_step_2_cart_entries($interval_minutes) {
      return $this->get_step_x_cart_entries(sprintf("sms_1_sent=1 AND sms_2_sent=0 AND sms_send_errors < 3 AND
        created < DATE_SUB(NOW(), INTERVAL %d MINUTE)", $interval_minutes));
    }

    public function get_step_x_cart_entries($where_query) {
      global $wpdb;

      $temp_cart_table_name = $wpdb->prefix . SINHRO_INTEGRATION_CART_TABLE_NAME;

      $results = $wpdb->get_results("SELECT * FROM {$temp_cart_table_name} WHERE ${where_query}");

      return $results;
    }

    // send email 1 15 after checkout screen reached
    // if link from email 1 is not opened, send sms 1 24 hours later
    // if link from sms 1 is not opened send email 2 after another 12 hours
    // if link from email 2 is not opened send sms 2 after another 12 hours
    // if link from sms 2 is not opened send email 3 after 24 hours later
    public function cart_process_sms()
    {
        global $wpdb;

        $temp_cart_table_name = $wpdb->prefix . SINHRO_INTEGRATION_CART_TABLE_NAME;

        $this->check_and_create_db_table();

        $mandrill_api_key = get_option("ssi_mandrill_api_key");
        $email_1_subject = get_option("ssi_mandrill_email_1_subject");
        $email_1_message = get_option("ssi_mandrill_email_1_message");
        $email_2_subject = get_option("ssi_mandrill_email_2_subject");
        $email_2_message = get_option("ssi_mandrill_email_2_message");
        $email_3_subject = get_option("ssi_mandrill_email_3_subject");
        $email_3_message = get_option("ssi_mandrill_email_3_message");

        $email_1_minutes = get_option("ssi_email_1_minutes");
        $email_2_minutes = get_option("ssi_email_2_minutes");
        $email_3_minutes = get_option("ssi_email_3_minutes");
        $sms_1_minutes = get_option("ssi_sms_1_minutes");
        $sms_2_minutes = get_option("ssi_sms_2_minutes");

        $options_header_color = get_option("ssi_mandrill_options_header_color");
        $options_header_logo = get_option("ssi_mandrill_options_header_logo");
        $options_footer_logo = get_option("ssi_mandrill_options_footer_logo");

        $options = [
          'header_color' => $options_header_color,
          'header_logo' => $options_header_logo,
          'footer_logo' => $options_footer_logo
        ];

        if (strlen($mandrill_api_key) > 0) {

          $results = $this->get_email_step_1_cart_entries($email_1_minutes ? $email_1_minutes : 15);

          if ($results && !is_wp_error($results) && count($results) > 0) {
            foreach ($results as $result) {
              $cart_url = wc_get_cart_url();
              if (!empty(get_option("ssi_mandrill_cart_url_1"))) {
                $cart_url = get_option("ssi_mandrill_cart_url_1");
              }

              $email_1_message = sprintf($email_1_message, $cart_url);
              $options['content'] = stripslashes($email_1_message);
              $this->send_email($result->email_address, $email_1_subject, $options);

              $wpdb->query($wpdb->prepare("UPDATE $temp_cart_table_name SET email_1_sent=1 WHERE id=%d", $result->id));
            }
          }
        }

        $results = $this->get_sms_step_1_cart_entries($sms_1_minutes ? $sms_1_minutes : 1440);

        if ($results && !is_wp_error($results) && count($results) > 0) {
          foreach ($results as $result) {
            $cart_url = wc_get_cart_url();
            if (!empty(get_option("ssi_api_cart_url_1"))) {
              $cart_url = get_option("ssi_api_cart_url_1");
            }

            $response = $this->send_sms($result->phone, sprintf(esc_html__("Oops! You left something in your cart! You can finish what you started here: %s", "sinhro-sms-integration"), $cart_url));

            if (!is_wp_error($response) && $response && isset($response["body"]) && $response["body"] == "Result_code: 00, Message OK") {
                $wpdb->query($wpdb->prepare("UPDATE $temp_cart_table_name SET sms_1_sent=1 WHERE id=%d", $result->id));
            } else {
                $wpdb->query($wpdb->prepare("UPDATE $temp_cart_table_name SET sms_send_errors=sms_send_errors+1 WHERE id=%d", $result->id));
                error_log("Error, sms 1 not sent to $result->phone\n\r", 3, $this->plugin_log_file);
                error_log(serialize($response), 3, $this->plugin_log_file);
            }
          }
        }

        if (strlen($mandrill_api_key) > 0) {
          $results = $this->get_email_step_2_cart_entries($email_2_minutes ? $email_2_minutes : 1920);

          if ($results && !is_wp_error($results) && count($results) > 0) {
            foreach ($results as $result) {
              $cart_url = wc_get_cart_url();
              if (!empty(get_option("ssi_mandrill_cart_url_2"))) {
                $cart_url = get_option("ssi_mandrill_cart_url_2");
              }

              $customer_first_name = isset($result->first_name) ? $result->first_name : "";
              $discount_value = get_option("ssi_api_discount_value") ? get_option("ssi_api_discount_value") : "20";

              $email_2_message = sprintf($email_2_message, $customer_first_name, $discount_value, $cart_url);
              $options['content'] = stripslashes($email_2_message);
              $this->send_email($result->email_address, $email_2_subject, $options);

              $wpdb->query($wpdb->prepare("UPDATE $temp_cart_table_name SET email_2_sent=1 WHERE id=%d", $result->id));
            }
          }
        }

        $results = $this->get_sms_step_2_cart_entries($sms_2_minutes ? $sms_2_minutes : 2880);

        if ($results && !is_wp_error($results) && count($results) > 0) {
          foreach ($results as $result) {
            $customer_first_name = isset($result->first_name) ? $result->first_name : "";
            $discount_value = get_option("ssi_api_discount_value") ? get_option("ssi_api_discount_value") : "20";
            $cart_url = wc_get_cart_url();
            $cart_url = add_query_arg("c", `${discount_value}off`, $cart_url);

            if (!empty(get_option("ssi_api_cart_url_2"))) {
              $cart_url = get_option("ssi_api_cart_url_2");
            }

            $response = $this->send_sms($result->phone, sprintf(esc_html__("Hey %s, get %d%% OFF your purchase. Hurry, before it expires: %s", "sinhro-sms-integration"), $customer_first_name, $discount_value, $cart_url));

            if ($response && isset($response["body"]) && $response["body"] == "Result_code: 00, Message OK") {
                $wpdb->query($wpdb->prepare("UPDATE $temp_cart_table_name SET sms_2_sent=1 WHERE id=%d", $result->id));
            } else {
                $wpdb->query($wpdb->prepare("UPDATE $temp_cart_table_name SET sms_send_errors=sms_send_errors+1 WHERE id=%d", $result->id));
                error_log("Error, sms 2 not sent to $result->phone\n\r", 3, $this->plugin_log_file);
                error_log(serialize($response), 3, $this->plugin_log_file);
            }
          }
        }


        if (strlen($mandrill_api_key) > 0) {
          $results = $this->get_email_step_3_cart_entries($email_3_minutes ? $email_3_minutes : 3840);

          if ($results && !is_wp_error($results) && count($results) > 0) {
            foreach ($results as $result) {
              $customer_first_name = isset($result->first_name) ? $result->first_name : "";

              $cart_url = wc_get_cart_url();
              if (!empty(get_option("ssi_api_cart_url_3"))) {
                $cart_url = get_option("ssi_api_cart_url_3");
              }

              $email_3_message = sprintf($email_3_message, $customer_first_name, $cart_url);
              $options['content'] = stripslashes($email_3_message);
              $this->send_email($result->email_address, $email_3_subject, $options);
              $wpdb->query($wpdb->prepare("UPDATE $temp_cart_table_name SET email_3_sent=1 WHERE id=%d", $result->id));
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
        wp_enqueue_script("sinhro-sms-integration-script", plugin_dir_url(__FILE__) . "js/script.js", array("jquery"), SINHRO_INTEGRATION_VERSION, true);
        wp_localize_script("sinhro-sms-integration-script", "ssiAjax", array( "ajaxurl" => admin_url("admin-ajax.php")));
    }

    public function save_checkout_info()
    {
        global $wpdb;

        $nonce_value = isset($_REQUEST["nonce"]) ? $_REQUEST["nonce"] : "";
        $phone = isset($_REQUEST["phone"]) ? sanitize_text_field($_REQUEST["phone"]) : "";
        $email = isset($_REQUEST["email"]) ? sanitize_text_field($_REQUEST["email"]) : "";
        $first_name = isset($_REQUEST["first_name"]) ? sanitize_text_field($_REQUEST["first_name"]) : "";
        $unique_cart_id = isset($_REQUEST["unique_cart_id"]) ? sanitize_text_field($_REQUEST["unique_cart_id"]) : "";

        if (wp_verify_nonce($nonce_value, "woocommerce-process_checkout")) {
            // nonce passed, we can record the phone number and cart unique id
            $this->check_and_create_db_table();

            $temp_cart_table_name = $wpdb->prefix . SINHRO_INTEGRATION_CART_TABLE_NAME;

            $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$temp_cart_table_name} WHERE abandoned_cart_id=%s", $unique_cart_id));

            $phone = str_replace("+", "", $phone);
            if (substr($phone, 0, strlen("00")) == "00") {
                $phone = substr($phone, strlen("00"));
            }

            if (!$row) {
                $wpdb->query($wpdb->prepare("INSERT INTO $temp_cart_table_name (abandoned_cart_id, phone, email_address, first_name) VALUES (%s, %s, %s, %s)", $unique_cart_id, $phone, $email, $first_name));
            }
        }

        die();
    }

    public function plugin_activate()
    {
        $this->check_and_create_db_table();
    }

    public function check_and_create_db_table()
    {
        global $wpdb;

        $wpdb_collate = $wpdb->collate;

        $temp_cart_table_name = $wpdb->prefix . SINHRO_INTEGRATION_CART_TABLE_NAME;

        $sql = "CREATE TABLE {$temp_cart_table_name} (
          id int(11) NOT NULL auto_increment,
          abandoned_cart_id varchar(20) NOT NULL,
          created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          email_1_sent BIT NOT NULL DEFAULT 0,
          email_2_sent BIT NOT NULL DEFAULT 0,
          email_3_sent BIT NOT NULL DEFAULT 0,
          sms_1_sent BIT NOT NULL DEFAULT 0,
          sms_2_sent BIT NOT NULL DEFAULT 0,
          sms_send_errors INT(1) NOT NULL DEFAULT 0,
          phone varchar(20) NOT NULL,
          email_address varchar(100) NOT NULL,
          first_name varchar(100) NOT NULL,
          PRIMARY KEY  (`id`)
        ) COLLATE {$wpdb_collate}";

        require_once(ABSPATH . "wp-admin/includes/upgrade.php");

        dbDelta($sql);
    }

    public function plugin_deactivate()
    {
        global $wpdb;

        require_once ABSPATH . "wp-admin/includes/upgrade.php";

        $temp_cart_table_name = $wpdb->prefix . SINHRO_INTEGRATION_CART_TABLE_NAME;
        $wpdb->query("DROP TABLE IF EXISTS " . $temp_cart_table_name);
    }

    public function woocommerce_order_processed($order_id)
    {
        global $wpdb;

        if (WC()->session) {
            $this->check_and_create_db_table();

            $temp_cart_table_name = $wpdb->prefix . SINHRO_INTEGRATION_CART_TABLE_NAME;
            $unique_cart_id = WC()->session->get("cart_unique_id");
            $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$temp_cart_table_name} WHERE abandoned_cart_id=%s", $unique_cart_id));

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

    public static function i18n_country_calling_codes()
    {
        $codes = [
            "bg_BG" => "359",
            "bs_BA" => "387",
            "cs_CZ" => "420",
            "de_DE" => "49",
            "el" => "30",
            "es_ES" => "34",
            "fr_FR" => "33",
            "hr" => "385",
            "hu_HU" => "36",
            "it_IT" => "39",
            "pl_PL" => "48",
            "pt_PT" => "351",
            "ro_RO" => "40",
            "sk_SK" => "421",
            "sl_SI" => "386",
            "sr_RS" => "381",
        ];

        return $codes;
    }

    public static function i18n_country_phone_lengths()
    {
        $lengths = [
            "bg_BG" => 9,
            "bs_BA" => 8,
            "cs_CZ" => 9,
            "de_DE" => 11,
            "el" => 10,
            "es_ES" => 9,
            "fr_FR" => 9,
            "hr" => 9,
            "hu_HU" => 9,
            "it_IT" => 9,
            "pl_PL" => 9,
            "pt_PT" => 9,
            "ro_RO" => 10,
            "sk_SK" => 9,
            "sl_SI" => 8,
            "sr_RS" => 9,
        ];

        return $lengths;
    }

    public function i18n_country_calling_code($lcid)
    {
        $codes = self::i18n_country_calling_codes();

        return isset($codes[$lcid]) ? $codes[$lcid] : "386";
    }

    public function i18n_country_phone_length($lcid)
    {
        $lengths = self::i18n_country_phone_lengths();

        return isset($lengths[$lcid]) ? $lengths[$lcid] : 8;
    }

    public function parse_template($options) {
      ob_start();
      include plugin_dir_path(__FILE__) . './templates/default.php';
      return ob_get_clean();
    }

    public function send_email($to_email_address, $email_subject, $options = []) {

        $mandrill_api_key = get_option("ssi_mandrill_api_key");
        $from_email_address = get_option("ssi_mandrill_from_address");

       if (strlen($mandrill_api_key) > 0 && strlen($from_email_address) > 0) {
          $mailchimp = new \MailchimpTransactional\ApiClient();
          $mailchimp->setApiKey($mandrill_api_key);

          $response = $mailchimp->messages->send(
          [
            "message" => [
              "subject" => $email_subject,
              "from_email" => $from_email_address,
              "to" => array(array("email" => $to_email_address)),
              "html" => $this->parse_template($options),
              "auto_html" => true,
            ]
          ]);
      }

      return $response;
    }

    public function send_sms($phone, $text, $override_host = "", $override_i18n = false)
    {
        $response = null;

        if ($phone && $text) {
            $phone = str_replace("+", "", $phone);
            if (substr($phone, 0, strlen("00")) == "00") {
                $phone = substr($phone, strlen("00"));
            }

            if (!$override_i18n) {
                $country_code = $this->i18n_country_calling_code(get_locale());
                if (substr($phone, 0, strlen($country_code)) == $country_code) {
                    // strip the country code as we will add it based on locale instead
                    $phone = substr($phone, strlen($country_code));
                }

                if (substr($phone, 0, 1) == "0") {
                    // if the number starts with 0 like 06112313, remove the 0
                    $phone = substr($phone, 1);
                }

                $country_phone_length = $this->i18n_country_phone_length(get_locale());
                if (strlen($phone) >= $country_phone_length && strlen($phone) <= ($country_phone_length + 2)) {
                    $phone = "00" . $country_code . $phone;

                    $ssi_api_username = get_option("ssi_api_username");
                    $ssi_api_password = get_option("ssi_api_password");

                    if (strlen($ssi_api_password) > 0 && strlen($ssi_api_username) > 0) {
                      $body = array(
                        "username"    => get_option("ssi_api_username"),
                        "password"    => get_option("ssi_api_password"),
                        "text"        => sanitize_text_field($text),
                        "call-number" => sanitize_text_field($phone),
                      );

                      $args = array(
                          "body"        => $body,
                      );

                      $api_host = isset($override_host) && !empty($override_host) ? sanitize_text_field($override_host) : "http://gw.sinhro.si/api/http/";

                      $response = wp_remote_post($api_host, $args);
                    }
                }
            } else {
                $phone = "00" . $phone;

                $ssi_api_username = get_option("ssi_api_username");
                $ssi_api_password = get_option("ssi_api_password");

                if (strlen($ssi_api_password) > 0 && strlen($ssi_api_username) > 0) {
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
            }
        }

        return $response;
    }

    public function send_test_email()
    {
        if (isset($_POST["ssi_send_test_email"])) {
            if ($this->validate_test_email_post_request()) {
              $test_to_email = $_POST["ssi_test_to_email"];
              $test_email_subject = $_POST["ssi_test_email_subject"];

              $options_header_color = get_option("ssi_mandrill_options_header_color");
              $options_header_logo = get_option("ssi_mandrill_options_header_logo");
              $options_footer_logo = get_option("ssi_mandrill_options_footer_logo");
              $options_content = $_POST["mail_content"];

              $options = [
                'header_color' => $options_header_color,
                'header_logo' => $options_header_logo,
                'footer_logo' => $options_footer_logo,
                'content' => stripslashes($options_content)
              ];

              $response = $this->send_email($test_to_email, $test_email_subject, $options);

              if (!is_array($response) && strpos($response, "error") !== false) {
                ?>
                <div class="error notice">
                  <p><?php var_dump($response); ?>
                  </p>
                </div>
                <?php
              } else {
                ?>
                <div class="succes notice">
                  <?php var_dump($response); ?>
                  <p><?php _e("Success: test message successfully sent!", "sinhro-sms-integration"); ?></p>
                </div>
                <?php
              }

            } else { ?>
              <div class="error notice">
                <p><?php _e("There has been an error when trying to send a test SMS. Please make sure all test SMS fields are filled in before attempting to send!", "sinhro-sms-integration"); ?>
                </p>
              </div>
              <?php
            }
        }
    }

    public function validate_test_email_post_request()
    {
        if (!isset($_POST["ssi_test_to_email"]) || empty($_POST["ssi_test_to_email"])) {
          return false;
        }

        if (!isset($_POST["ssi_test_email_subject"]) || empty($_POST["ssi_test_email_subject"])) {
          return false;
        }

        return true;
    }

    public function send_test_sms()
    {
        if (isset($_POST["ssi_api_send_test_sms"])) {
            if ($this->validate_test_sms_post_request()) {
              $ssi_api_host = get_option("ssi_api_host");
              $test_phone = $_POST["ssi_api_test_phone_number"];
              $test_message = $_POST["ssi_api_test_message"];

              $response = $this->send_sms($test_phone, $test_message, $ssi_api_host, true);

              if ($response && isset($response["body"]) && $response["body"] == "Result_code: 00, Message OK") { ?>
                <div class="updated notice">
                  <p><?php _e("Success. Test SMS sent!", "sinhro-sms-integration"); ?></p>
                </div>
              <?php
              } else {
                  error_log(serialize($response), 3, $this->plugin_log_file); ?>
                  <div class="error notice">
                    <p><?php _e("Error. Test SMS failed to send!", "sinhro-sms-integration"); ?></p>
                    <textarea rows="10" style="width:100%;margin-bottom:20px;" disabled><?php print_r($response); ?></textarea>
                    <br />
                  </div>
              <?php
              }
            } else { ?>
              <div class="error notice">
                <p><?php _e("There has been an error when trying to send a test SMS. Please make sure all test SMS fields are filled in before attempting to send!", "sinhro-sms-integration"); ?>
                </p>
              </div>
              <?php
            }
        }
    }

    public function validate_test_sms_post_request()
    {
        if (!isset($_POST["ssi_api_test_message"]) || empty($_POST["ssi_api_test_message"])) {
          return false;
        }

        if (!isset($_POST["ssi_api_test_phone_number"]) || empty($_POST["ssi_api_test_phone_number"])) {
          return false;
        }

        return true;
    }

    public function register_sinhro_sms_integration_settings()
    {
        register_setting("sinhro-times-integration-settings", "ssi_email_1_minutes");
        register_setting("sinhro-times-integration-settings", "ssi_email_2_minutes");
        register_setting("sinhro-times-integration-settings", "ssi_email_3_minutes");
        register_setting("sinhro-times-integration-settings", "ssi_sms_1_minutes");
        register_setting("sinhro-times-integration-settings", "ssi_sms_2_minutes");

        register_setting("sinhro-sms-integration-settings", "ssi_api_cart_url_1");
        register_setting("sinhro-sms-integration-settings", "ssi_api_cart_url_2");
        register_setting("sinhro-sms-integration-settings", "ssi_api_host");
        register_setting("sinhro-sms-integration-settings", "ssi_api_username");
        register_setting("sinhro-sms-integration-settings", "ssi_api_discount_value");
        register_setting("sinhro-sms-integration-settings", "ssi_api_password");

        register_setting("sinhro-email-integration-settings", "ssi_mandrill_cart_url_1");
        register_setting("sinhro-email-integration-settings", "ssi_mandrill_cart_url_2");
        register_setting("sinhro-email-integration-settings", "ssi_mandrill_cart_url_3");
        register_setting("sinhro-email-integration-settings", "ssi_mandrill_api_key");
        register_setting("sinhro-email-integration-settings", "ssi_mandrill_from_address");
        register_setting("sinhro-email-integration-settings", "ssi_mandrill_options_header_color");
        register_setting("sinhro-email-integration-settings", "ssi_mandrill_options_header_logo");
        register_setting("sinhro-email-integration-settings", "ssi_mandrill_options_footer_logo");
        register_setting("sinhro-email-integration-settings", "ssi_mandrill_email_1_subject");
        register_setting("sinhro-email-integration-settings", "ssi_mandrill_email_1_message");
        register_setting("sinhro-email-integration-settings", "ssi_mandrill_email_2_subject");
        register_setting("sinhro-email-integration-settings", "ssi_mandrill_email_2_message");
        register_setting("sinhro-email-integration-settings", "ssi_mandrill_email_3_subject");
        register_setting("sinhro-email-integration-settings", "ssi_mandrill_email_3_message");
    }

    public function load_plugin_textdomain()
    {
        $this->check_and_create_db_table();

        load_plugin_textdomain("sinhro-sms-integration", false, dirname(plugin_basename(__FILE__)) . "/languages");
    }

    public function admin_menu()
    {
        add_menu_page($this->plugin_name, __("Sinhro Integration", "sinhro-sms-integration"), "administrator", $this->plugin_name, array($this, "display_plugin_dashboard" ), "dashicons-admin-network", 20);
    }

    public function display_plugin_dashboard()
    {
        require_once plugin_dir_path(__FILE__) . "/partials/admin-settings.php";
    }

    function hooks_for($hook = "", $return = false)
    {
        global $wp_filter;

        if (empty($hook) || !isset($wp_filter[$hook])) {
            return;
        }

        if ($return) {
            ob_start();
        }

        print "<pre>";
        print_r ($wp_filter[$hook]);
        print "</pre>";

        if ($return) {
            return ob_get_clean();
        }
    }
}

new SinhroIntegration();
