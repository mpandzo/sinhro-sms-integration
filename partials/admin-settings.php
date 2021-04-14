<?php
defined( "ABSPATH" ) || exit; // Exit if accessed directly

if ( ! current_user_can( 'manage_options' ) ) {
  return;
}

require_once(__DIR__ . "/../lib/class-abandoned-carts-table.php");

//Get the active tab from the $_GET param
$tab = isset($_GET['tab']) ? $_GET['tab'] : "times";
?>
<div class="wrap">
    <h1><?php esc_html_e("Sinhro Integration settings", "sinhro-sms-integration"); ?></h1>

    <!-- Here are our tabs -->
    <nav class="nav-tab-wrapper">
      <a href="<?php echo wc_get_current_admin_url() ?>&tab=times" class="nav-tab <?php if($tab==="times"):?>nav-tab-active<?php endif; ?>"><?php _e('Sending times', "sinhro-sms-integration"); ?></a>
      <a href="<?php echo wc_get_current_admin_url() ?>&tab=sms" class="nav-tab <?php if($tab==="sms"):?>nav-tab-active<?php endif; ?>"><?php _e('Sms settings', "sinhro-sms-integration"); ?></a>
      <a href="<?php echo wc_get_current_admin_url() ?>&tab=test-sms" class="nav-tab <?php if($tab==="test-sms"):?>nav-tab-active<?php endif; ?>"><?php _e('Test sms', "sinhro-sms-integration"); ?></a>
      <a href="<?php echo wc_get_current_admin_url() ?>&tab=email" class="nav-tab <?php if($tab==='email'):?>nav-tab-active<?php endif; ?>"><?php _e('Email settings', "sinhro-sms-integration"); ?></a>
      <a href="<?php echo wc_get_current_admin_url() ?>&tab=test-email" class="nav-tab <?php if($tab==='test-email'):?>nav-tab-active<?php endif; ?>"><?php _e('Test email', "sinhro-sms-integration"); ?></a>
      <a href="<?php echo wc_get_current_admin_url() ?>&tab=browse-abandoned-carts" class="nav-tab <?php if($tab==='browse-abandoned-carts'):?>nav-tab-active<?php endif; ?>"><?php _e('Browse abandoned carts', "sinhro-sms-integration"); ?></a>
    </nav>

    <?php if ($tab === "times") { ?>
      <form method="post" action="options.php">
      <?php settings_fields("sinhro-times-integration-settings"); ?>
      <?php do_settings_sections("sinhro-times-integration-settings"); ?>

        <table class="form-table">
            <tr valign="top">
              <td style="padding: 10px 0;margin: 0;" colspan="2"><h2 style="padding: 0;margin: 0;"><?php esc_html_e("Sending time settings", "sinhro-sms-integration"); ?></h2></td>
            </tr>
            <tr valign="top">
              <th scope="row">
                  <?php esc_html_e("Email 1 time", "sinhro-sms-integration"); ?><br />
                  <small><?php esc_html_e("Time in minutes after cart is abandoned that we send the first email", "sinhro-sms-integration"); ?></small>
              </th>
              <td>
                  <input type="number" name="ssi_email_1_minutes" value="<?php echo esc_attr(get_option("ssi_email_1_minutes")); ?>" />
                  <small><?php esc_html_e("Default: 15", "sinhro-sms-integration"); ?></small>
              </td>
            </tr>
            <tr valign="top">
              <th scope="row">
                  <?php esc_html_e("Sms 1 time", "sinhro-sms-integration"); ?><br />
                  <small><?php esc_html_e("Time in minutes after cart is abandoned that we send the first sms", "sinhro-sms-integration"); ?></small>
              </th>
              <td>
                  <input type="number" name="ssi_sms_1_minutes" value="<?php echo esc_attr(get_option("ssi_sms_1_minutes")); ?>" />
                  <small><?php esc_html_e("Default: 1440 - ie 24 hours (60x24)", "sinhro-sms-integration"); ?></small>
              </td>
            </tr>
            <tr valign="top">
              <th scope="row">
                  <?php esc_html_e("Email 2 time", "sinhro-sms-integration"); ?><br />
                  <small><?php esc_html_e("Time in minutes after cart is abandoned that we send the second email", "sinhro-sms-integration"); ?></small>
              </th>
              <td>
                  <input type="number" name="ssi_email_2_minutes" value="<?php echo esc_attr(get_option("ssi_email_2_minutes")); ?>" />
                  <small><?php esc_html_e("Default: 1920 - ie 32 hours (60x32)", "sinhro-sms-integration"); ?></small>
              </td>
            </tr>
            <tr valign="top">
              <th scope="row">
                  <?php esc_html_e("Sms 2 time", "sinhro-sms-integration"); ?><br />
                  <small><?php esc_html_e("Time in minutes after cart is abandoned that we send the second sms", "sinhro-sms-integration"); ?></small>
              </th>
              <td>
                  <input type="number" name="ssi_sms_2_minutes" value="<?php echo esc_attr(get_option("ssi_sms_2_minutes")); ?>" />
                  <small><?php esc_html_e("Default: 2880 - ie 48 hours (60x48)", "sinhro-sms-integration"); ?></small>
              </td>
            </tr>
            <tr valign="top">
              <th scope="row">
                  <?php esc_html_e("Email 3 time", "sinhro-sms-integration"); ?><br />
                  <small><?php esc_html_e("Time in minutes after cart is abandoned that we send the third email", "sinhro-sms-integration"); ?></small>
              </th>
              <td>
                  <input type="number" name="ssi_email_3_minutes" value="<?php echo esc_attr(get_option("ssi_email_3_minutes")); ?>" />
                  <small><?php esc_html_e("Default: 3840 - ie 64 hours (60x64)", "sinhro-sms-integration"); ?></small>
              </td>
            </tr>
        </table>

        <?php submit_button(); ?>
      </form>

    <?php } else if ($tab === "sms") { ?>
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
        </table>

        <?php submit_button(); ?>
      </form>

      <?php } else if ($tab === "email") { ?>
      <form method="post" action="options.php">
        <?php settings_fields("sinhro-email-integration-settings"); ?>
        <?php do_settings_sections("sinhro-email-integration-settings"); ?>

        <table class="form-table">
          <tr valign="top">
            <td style="padding: 10px 0;margin: 0;" colspan="2"><h2 style="padding: 0;margin: 0;"><?php esc_html_e("Email settings", "sinhro-sms-integration"); ?></h2></td>
          </tr>
          <tr valign="top">
              <th scope="row">
                  <?php esc_html_e("Mandrill api key", "sinhro-sms-integration"); ?><br />
              </th>
              <td>
                  <input type="text" name="ssi_mandrill_api_key" value="<?php echo esc_attr(get_option("ssi_mandrill_api_key")); ?>" />
                  <small><?php esc_html_e("Your Mandrill API key", "sinhro-sms-integration"); ?></small>
              </td>
          </tr>
          <tr valign="top">
              <th scope="row">
                  <?php esc_html_e("Mandrill from address", "sinhro-sms-integration"); ?><br />
              </th>
              <td>
                  <input type="text" name="ssi_mandrill_from_address" value="<?php echo esc_attr(get_option("ssi_mandrill_from_address")); ?>" />
                  <small><?php esc_html_e("Your Mandrill from email address", "sinhro-sms-integration"); ?></small>
              </td>
          </tr>
          <tr valign="top">
              <th scope="row">
                  <?php esc_html_e("First email cart url", "sinhro-sms-integration"); ?><br />
                  <small><?php esc_html_e("Override default first email cart url (woocommerce cart url)", "sinhro-sms-integration"); ?></small>
              </th>
              <td>
                  <input type="text" name="ssi_mandrill_cart_url_1" value="<?php echo esc_attr(get_option("ssi_mandrill_cart_url_1")); ?>" />
                  <small><?php esc_html_e("Default: http://yourdomain.com/cart", "sinhro-sms-integration"); ?></small>
              </td>
          </tr>
          <tr valign="top">
              <th scope="row">
                  <?php esc_html_e("Mandrill first email subject", "sinhro-sms-integration"); ?><br />
              </th>
              <td>
                  <input type="text" name="ssi_mandrill_email_1_subject" value="<?php echo esc_attr(get_option("ssi_mandrill_email_1_subject")); ?>" />
                  <small><?php esc_html_e("The first email subject", "sinhro-sms-integration"); ?></small>
              </td>
          </tr>
          <tr valign="top">
              <th scope="row">
                  <?php esc_html_e("Mandrill first email message", "sinhro-sms-integration"); ?><br />
              </th>
              <td>
                  <textarea rows="5" cols="50" name="ssi_mandrill_email_1_message"><?php echo esc_attr(get_option("ssi_mandrill_email_1_message")); ?></textarea>
                  <small><?php esc_html_e("The first email message", "sinhro-sms-integration"); ?></small>
              </td>
          </tr>
          <tr valign="top">
              <th scope="row">
                  <?php esc_html_e("Second email cart url", "sinhro-sms-integration"); ?><br />
                  <small><?php esc_html_e("Override default second email cart url (woocommerce cart url)", "sinhro-sms-integration"); ?></small>
              </th>
              <td>
                  <input type="text" name="ssi_mandrill_cart_url_2" value="<?php echo esc_attr(get_option("ssi_mandrill_cart_url_2")); ?>" />
                  <small><?php esc_html_e("Default: http://yourdomain.com/cart", "sinhro-sms-integration"); ?></small>
              </td>
          </tr>
          <tr valign="top">
              <th scope="row">
                  <?php esc_html_e("Mandrill second email subject", "sinhro-sms-integration"); ?><br />
              </th>
              <td>
                  <input type="text" name="ssi_mandrill_email_2_subject" value="<?php echo esc_attr(get_option("ssi_mandrill_email_2_subject")); ?>" />
                  <small><?php esc_html_e("The second email subject", "sinhro-sms-integration"); ?></small>
              </td>
          </tr>
          <tr valign="top">
              <th scope="row">
                  <?php esc_html_e("Mandrill second email message", "sinhro-sms-integration"); ?><br />
              </th>
              <td>
                  <textarea rows="5" cols="50" name="ssi_mandrill_email_2_message"><?php echo esc_attr(get_option("ssi_mandrill_email_2_message")); ?></textarea>
                  <small><?php esc_html_e("The second email message", "sinhro-sms-integration"); ?></small>
              </td>
          </tr>
          <tr valign="top">
              <th scope="row">
                  <?php esc_html_e("Third email cart url", "sinhro-sms-integration"); ?><br />
                  <small><?php esc_html_e("Override default third email cart url (woocommerce cart url)", "sinhro-sms-integration"); ?></small>
              </th>
              <td>
                  <input type="text" name="ssi_mandrill_cart_url_3" value="<?php echo esc_attr(get_option("ssi_mandrill_cart_url_3")); ?>" />
                  <small><?php esc_html_e("Default: http://yourdomain.com/cart", "sinhro-sms-integration"); ?></small>
              </td>
          </tr>
          <tr valign="top">
              <th scope="row">
                  <?php esc_html_e("Mandrill third email subject", "sinhro-sms-integration"); ?><br />
              </th>
              <td>
                  <input type="text" name="ssi_mandrill_email_3_subject" value="<?php echo esc_attr(get_option("ssi_mandrill_email_3_subject")); ?>" />
                  <small><?php esc_html_e("The third email subject", "sinhro-sms-integration"); ?></small>
              </td>
          </tr>
          <tr valign="top">
              <th scope="row">
                  <?php esc_html_e("Mandrill third email message", "sinhro-sms-integration"); ?><br />
              </th>
              <td>
                  <textarea rows="5" cols="50" name="ssi_mandrill_email_3_message"><?php echo esc_attr(get_option("ssi_mandrill_email_3_message")); ?></textarea>
                  <small><?php esc_html_e("The third email message", "sinhro-sms-integration"); ?></small>
              </td>
          </tr>
      </table>

      <?php submit_button(); ?>
    </form>

    <?php } else if ($tab === "test-sms") { ?>

      <?php if (get_option("ssi_api_username") && get_option("ssi_api_password")) { ?>
        <form method="post" action="<?php echo wc_get_current_admin_url() ?>">
          <input type="hidden" name="ssi_api_send_test_sms" value="1" />
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
          <?php submit_button("Send test sms"); ?>
        </form>
      <?php } else { ?>
        <p><?php _e("Please make sure your sinhro sms username and password are provided in the Sms settings tab!", "sinhro-sms-integration"); ?></p>
      <?php } ?>

    <?php } else if ($tab === "test-email") { ?>

      <?php if (get_option("ssi_mandrill_api_key") && get_option("ssi_mandrill_from_address")) { ?>
        <form method="post" action="<?php echo wc_get_current_admin_url() ?>">
          <input type="hidden" name="ssi_send_test_email" value="1" />
          <h3><?php esc_html_e("Send test email", "sinhro-sms-integration"); ?><h3>
          <table class="form-table">
            <tr valign="top">
                <th scope="row">
                    <?php esc_html_e("To email address", "sinhro-sms-integration"); ?><br />
                </th>
                <td>
                    <input type="text" name="ssi_test_to_email" />
                    <small><?php esc_html_e("The email address to send the test message to", "sinhro-sms-integration"); ?></small>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php esc_html_e("Email subject", "sinhro-sms-integration"); ?><br />
                </th>
                <td>
                    <input type="text" name="ssi_test_email_subject" />
                    <small><?php esc_html_e("The email test subject to send", "sinhro-sms-integration"); ?></small>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php esc_html_e("Email message", "sinhro-sms-integration"); ?><br />
                </th>
                <td>
                    <textarea rows="5" cols="50" name="ssi_test_email_message"></textarea>
                    <small><?php esc_html_e("The email test message to send", "sinhro-sms-integration"); ?></small>
                </td>
            </tr>
          </table>
          <?php submit_button("Send test email"); ?>
        </form>
      <?php } else { ?>
        <p><?php _e("Please make sure your mandrill api key and from email address are provided in the Email settings tab!", "sinhro-sms-integration"); ?></p>
      <?php } ?>

    <?php } else if ($tab === "browse-abandoned-carts") {
      $abandoned_cart_table = new Abandoned_Cart_Admin_List_Table();
			$abandoned_cart_table->prepare_items();
    ?>

      <h3><?php esc_html_e("Currently abandoned carts", "sinhro-sms-integration"); ?><h3>

      <?php 			$abandoned_cart_table->display(); ?>

    <?php } ?>

</div>
