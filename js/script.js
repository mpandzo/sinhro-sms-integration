(function ($) {

  $(document).ready(function () {
    if ($('.woocommerce-checkout')) {

      $('#billing_phone').on('blur', function () {
        var nonce = $('#woocommerce-process-checkout-nonce').val();
        var phone = $('#billing_phone').val();
        var uniqueCartId = $('#ssi-unique-cart-id').val();

        if (nonce && phone && uniqueCartId) {
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
      });

    }
  });

}(jQuery));
