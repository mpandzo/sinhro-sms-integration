(function ($) {

  $(document).ready(function () {
    if ($('.woocommerce-checkout')) {

      // on load, if phone is already entered (from session)
      if ($('#billing_phone').val()) {
        recordCartPhoneNumber();
      }

      $('#billing_phone').on('blur', function () {
        recordCartPhoneNumber();
      });

      function recordCartPhoneNumber() {
        var nonce = $('#woocommerce-process-checkout-nonce').val();
        var phone = $('#billing_phone').val();
        var uniqueCartId = $('#ssi-unique-cart-id').val();

        // optional + and 00 at the start and at least 10 digits
        var phonePattern = /^\+?(00)?\d{5,}$/;

        if (nonce && phone && uniqueCartId && phonePattern.test(phone)) {
          var dataObj = {
            'action': 'record_checkout_phone',
            'nonce': nonce,
            'phone': phone,
            'unique_cart_id': uniqueCartId,
          };

          $.ajax({
            url: ssiAjax.ajaxurl,
            data: dataObj,
            success: function () {
              console.log('success!');
            },
            error: function (xhr, ajaxOptions, thrownError) {
              console.log(xhr);
              console.log(ajaxOptions);
              console.log(thrownError);
            }
          });
        }

      }

    }
  });

}(jQuery));
