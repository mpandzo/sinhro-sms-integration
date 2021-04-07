(function ($) {

  $(document).ready(function () {
    if ($('.woocommerce-checkout')) {

      // on load, if phone is already entered (from session)
      if ($('#billing_email').val()) {
        recordCartInfo();
      }

      $('#billing_email').on('blur', function () {
        recordCartInfo();
      });

      function recordCartInfo() {
        var nonce = $('#woocommerce-process-checkout-nonce').val();
        var phone = $('#billing_phone').val();
        var email = $('#billing_email').val();
        var firstName = $('#billing_first_name').val();
        var uniqueCartId = $('#ssi-unique-cart-id').val();

        // optional + and 00 at the start and at least 10 digits
        var phonePattern = /^\+?(00)?\d{5,}$/;

        if (nonce && phone && email && uniqueCartId && phonePattern.test(phone)) {
          var dataObj = {
            'action': 'save_checkout_info',
            'nonce': nonce,
            'phone': phone,
            'email': email,
            'first_name': firstName,
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
