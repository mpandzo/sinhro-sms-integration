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
              <td style="padding: 10px 0;margin: 0;" colspan="2"><h2 style="padding: 0;margin: 0;"><?php esc_html_e("Sms settings", "sinhro-sms-integration"); ?></h2></td>
            </tr>
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
                    <?php esc_html_e("Discount value override", "sinhro-sms-integration"); ?><br />
                    <small><?php esc_html_e("Override default discount value", "sinhro-sms-integration"); ?></small>
                </th>
                <td>
                    <input type="text" name="ssi_api_discount_value" value="<?php echo esc_attr(get_option("ssi_api_discount_value")); ?>" />
                    <small><?php esc_html_e("Default: 20", "sinhro-sms-integration"); ?></small>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php esc_html_e("First sms cart url", "sinhro-sms-integration"); ?><br />
                    <small><?php esc_html_e("Override default first sms cart url (woocommerce cart url)", "sinhro-sms-integration"); ?></small>
                </th>
                <td>
                    <input type="text" name="ssi_api_cart_url_1" value="<?php echo esc_attr(get_option("ssi_api_cart_url_1")); ?>" />
                    <small><?php esc_html_e("Default: http://yourdomain.com/cart", "sinhro-sms-integration"); ?></small>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php esc_html_e("Second sms cart url", "sinhro-sms-integration"); ?><br />
                    <small><?php esc_html_e("Override default second cart url (woocommerce cart url)", "sinhro-sms-integration"); ?></small>
                </th>
                <td>
                    <input type="text" name="ssi_api_cart_url_2" value="<?php echo esc_attr(get_option("ssi_api_cart_url_2")); ?>" />
                    <small><?php esc_html_e("Default: http://yourdomain.com/cart?c=%s", "sinhro-sms-integration"); ?></small>
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
            <tr valign="top">
              <td style="padding: 10px 0;margin: 0;" colspan="2"><h2 style="padding: 0;margin: 0;"><?php esc_html_e("Email settings", "sinhro-sms-integration"); ?></h2></td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php esc_html_e("Mailgun api key", "sinhro-sms-integration"); ?><br />
                </th>
                <td>
                    <input type="text" name="ssi_mailgun_api_key" value="<?php echo esc_attr(get_option("ssi_mailgun_api_key")); ?>" />
                    <small><?php esc_html_e("Your mailgun API key", "sinhro-sms-integration"); ?></small>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php esc_html_e("Mailgun api domain", "sinhro-sms-integration"); ?><br />
                </th>
                <td>
                    <input type="text" name="ssi_mailgun_api_domain" value="<?php echo esc_attr(get_option("ssi_mailgun_api_domain")); ?>" />
                    <small><?php esc_html_e("Your mailgun API domain", "sinhro-sms-integration"); ?></small>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php esc_html_e("Mailgun from address", "sinhro-sms-integration"); ?><br />
                </th>
                <td>
                    <input type="text" name="ssi_mailgun_from_address" value="<?php echo esc_attr(get_option("ssi_mailgun_from_address")); ?>" />
                    <small><?php esc_html_e("Your mailgun configured from email address", "sinhro-sms-integration"); ?></small>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php esc_html_e("Mailgun first email subject", "sinhro-sms-integration"); ?><br />
                </th>
                <td>
                    <input type="text" name="ssi_mailgun_email_1_subject" value="<?php echo esc_attr(get_option("ssi_mailgun_email_1_subject")); ?>" />
                    <small><?php esc_html_e("The first email subject", "sinhro-sms-integration"); ?></small>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php esc_html_e("Mailgun first email message", "sinhro-sms-integration"); ?><br />
                </th>
                <td>
                    <textarea rows="5" cols="50" name="ssi_mailgun_email_1_message"><?php echo esc_attr(get_option("ssi_mailgun_email_1_message")); ?></textarea>
                    <small><?php esc_html_e("The first email message", "sinhro-sms-integration"); ?></small>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php esc_html_e("Mailgun second email subject", "sinhro-sms-integration"); ?><br />
                </th>
                <td>
                    <input type="text" name="ssi_mailgun_email_2_subject" value="<?php echo esc_attr(get_option("ssi_mailgun_email_2_subject")); ?>" />
                    <small><?php esc_html_e("The second email subject", "sinhro-sms-integration"); ?></small>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php esc_html_e("Mailgun second email message", "sinhro-sms-integration"); ?><br />
                </th>
                <td>
                    <textarea rows="5" cols="50" name="ssi_mailgun_email_2_message"><?php echo esc_attr(get_option("ssi_mailgun_email_2_message")); ?></textarea>
                    <small><?php esc_html_e("The second email message", "sinhro-sms-integration"); ?></small>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php esc_html_e("Mailgun third email subject", "sinhro-sms-integration"); ?><br />
                </th>
                <td>
                    <input type="text" name="ssi_mailgun_email_3_subject" value="<?php echo esc_attr(get_option("ssi_mailgun_email_3_subject")); ?>" />
                    <small><?php esc_html_e("The third email subject", "sinhro-sms-integration"); ?></small>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php esc_html_e("Mailgun third email message", "sinhro-sms-integration"); ?><br />
                </th>
                <td>
                    <textarea rows="5" cols="50" name="ssi_mailgun_email_3_message"><?php echo esc_attr(get_option("ssi_mailgun_email_3_message")); ?></textarea>
                    <small><?php esc_html_e("The third email message", "sinhro-sms-integration"); ?></small>
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
