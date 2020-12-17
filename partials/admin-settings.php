<?php
defined( "ABSPATH" ) || exit; // Exit if accessed directly
?>
<div class="wrap">
    <h1><?php esc_html_e("Sinhro Sms Integration settings", "sinhro-sms-integration"); ?></h1>
    <form method="post" action="options.php">
        <?php settings_fields("sinhro-sms-integration-settings"); ?>
        <?php do_settings_sections("sinhro-sms-integration-settings"); ?>

        <table class="form-table">
            <tr valign="top">
                <th scope="row">
                    <?php esc_html_e("Api host", "sinhro-sms-integration"); ?><br />
                    <small><?php esc_html_e("Override default host", "sinhro-sms-integration"); ?></small>
                </th>
                <td>
                    <input type="text" name="ssi_api_host" value="<?php echo esc_attr(get_option("ssi_api_host")); ?>" />
                    <small><?php esc_html_e("Default: http://gw.sinhro.si/api/http", "sinhro-sms-integration"); ?></small>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php esc_html_e("Discount value", "sinhro-sms-integration"); ?><br />
                    <small><?php esc_html_e("Override default discout value string (20%)", "sinhro-sms-integration"); ?></small>
                </th>
                <td>
                    <input type="text" name="ssi_api_discount_value" value="<?php echo esc_attr(get_option("ssi_api_discount_value")); ?>" />
                    <small><?php esc_html_e("Default: 20%", "sinhro-sms-integration"); ?></small>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php esc_html_e("Api username", "sinhro-sms-integration"); ?><br />
                </th>
                <td>
                    <input type="text" name="ssi_api_username" value="<?php echo esc_attr(get_option("ssi_api_username")); ?>" />
                    <small><?php esc_html_e("Your gw.sinhro.si API username", "sinhro-sms-integration"); ?></small>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php esc_html_e("Api password", "sinhro-sms-integration"); ?><br />
                </th>
                <td>
                    <input type="password" name="ssi_api_password" value="<?php echo esc_attr(get_option("ssi_api_password")); ?>" />
                    <small><?php esc_html_e("Your gw.sinhro.si API password", "sinhro-sms-integration"); ?></small>
                </td>
            </tr>
        </table>

        <?php submit_button(); ?>
    </form>

    <?php if (get_option("ssi_api_username") && get_option("ssi_api_password")) { ?>
    <form method="post" action="<?php echo admin_url('/admin.php?page=SinhroSmsIntegration'); ?>">
      <input type="hidden" name="ssi_send_test_sms" value="1" />
      <h3><?php esc_html_e("Send test sms", "sinhro-sms-integration"); ?><h3>
      <table class="form-table">
        <tr valign="top">
            <th scope="row">
                <?php esc_html_e("Phone number", "sinhro-sms-integration"); ?><br />
            </th>
            <td>
                <input type="text" name="ssi_api_test_phone_number" />
                <small><?php esc_html_e("The phone number to send test SMS to with leading 0s, e.g. 003861234567", "sinhro-sms-integration"); ?></small>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <?php esc_html_e("SMS message", "sinhro-sms-integration"); ?><br />
            </th>
            <td>
                <input type="text" name="ssi_api_test_message" />
                <small><?php esc_html_e("Default: 12345", "sinhro-sms-integration"); ?></small>
            </td>
        </tr>
      </table>
      <?php submit_button("Send sms"); ?>
    </form>
    <?php } ?>

</div>
