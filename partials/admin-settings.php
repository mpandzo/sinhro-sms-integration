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
                    <input type="text" name="ssi_api_password" value="<?php echo esc_attr(get_option("ssi_api_password")); ?>" />
                    <small><?php esc_html_e("Your gw.sinhro.si API password", "sinhro-sms-integration"); ?></small>
                </td>
            </tr>
        </table>

        <?php submit_button(); ?>
    </form>
</div>