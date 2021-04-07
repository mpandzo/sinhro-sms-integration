# sinhro-sms-integration
A WordPress plugin that allows integration with the http://gw.sinhro.si/api/http api for sending SMSs

To begin using please navigate to /wp-admin/admin.php?page=SinhroIntegration and enter your api credentials

Note: the plugin is only usable when WooCommerce is installed and activated.

Ideas for further development:

- gdpr compliance for temp data stored in abandoned carts table
- option for message that is sent as reminder via sms for abandoned carts... currently it is hardcoded as a constant SINHRO_SMS_REMINDER_MESSAGE
