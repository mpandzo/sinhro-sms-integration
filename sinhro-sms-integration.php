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
        add_action("admin_menu", array($this, "admin_menu"), 10);
        add_action("init", array($this, "load_plugin_textdomain"));
        add_action("admin_init", array($this, "register_sinhro_sms_integration_settings"));
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
