<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
  <title>
  </title>
  <!--[if !mso]><!-->
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!--<![endif]-->
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style type="text/css">
    #outlook a {
      padding: 0;
    }

    body {
      margin: 0;
      padding: 0;
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
    }

    table,
    td {
      border-collapse: collapse;
      mso-table-lspace: 0pt;
      mso-table-rspace: 0pt;
    }

    img {
      border: 0;
      height: auto;
      line-height: 100%;
      outline: none;
      text-decoration: none;
      -ms-interpolation-mode: bicubic;
    }

    p {
      display: block;
      margin: 13px 0;
    }
  </style>
  <!--[if mso]>
        <noscript>
        <xml>
        <o:OfficeDocumentSettings>
          <o:AllowPNG/>
          <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
        </xml>
        </noscript>
        <![endif]-->
  <!--[if lte mso 11]>
        <style type="text/css">
          .mj-outlook-group-fix { width:100% !important; }
        </style>
        <![endif]-->
  <style type="text/css">
    @media only screen and (min-width:480px) {
      .mj-column-per-100 {
        width: 100% !important;
        max-width: 100%;
      }
    }
  </style>
  <style media="screen and (min-width:480px)">
    .moz-text-html .mj-column-per-100 {
      width: 100% !important;
      max-width: 100%;
    }
  </style>
  <style type="text/css">
    @media only screen and (max-width:480px) {
      table.mj-full-width-mobile {
        width: 100% !important;
      }

      td.mj-full-width-mobile {
        width: auto !important;
      }
    }
  </style>
</head>

<?php
  $template_sent_by = isset($options['sent_by']) && strlen($options['sent_by']) > 0 ? $options['sent_by'] : 'placeholder@email.com';
  $template_info_mail = isset($options['info_mail']) && strlen($options['info_mail']) > 0 ? $options['info_mail'] : 'placeholder@email.com';
  $template_footer_headline = isset($options['footer_headline']) && strlen($options['footer_headline']) > 0 ? $options['footer_headline'] : 'Get in touch';
  $template_footer_first_link_url = isset($options['footer_first_link_url']) && strlen($options['footer_first_link_url']) > 0 ? $options['footer_first_link_url'] : '/';
  $template_footer_first_link_text = isset($options['footer_first_link_text']) && strlen($options['footer_first_link_text']) > 0 ? $options['footer_first_link_text'] : 'Link 1';
  $template_footer_second_link_url = isset($options['footer_second_link_url']) && strlen($options['footer_second_link_url']) > 0 ? $options['footer_second_link_url'] : '/';
  $template_footer_second_link_text = isset($options['footer_second_link_text']) && strlen($options['footer_second_link_text']) > 0 ? $options['footer_second_link_text'] : 'Link 2';

  $template_header_color = isset($options['header_color']) && strlen($options['header_color']) > 0 ? $options['header_color'] : '#000000';
  $template_footer_color = isset($options['footer_color']) && strlen($options['footer_color']) > 0 ? $options['footer_color'] : '#000000';
  $template_header_logo = isset($options['header_logo']) && strlen($options['header_logo']) > 0 ? $options['header_logo'] : 'https://via.placeholder.com/300x150';
  $template_footer_logo = isset($options['footer_logo']) && strlen($options['footer_logo']) > 0 ? $options['footer_logo'] : 'https://via.placeholder.com/1000x500';

  $template_facebook_url = isset($options['facebook_url']) && strlen($options['facebook_url']) > 0 ? $options['facebook_url'] : 'https://facebook.com';
  $template_facebook_img = isset($options['facebook_img']) && strlen($options['facebook_img']) > 0 ? $options['header_color'] : 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/51/Facebook_f_logo_%282019%29.svg/1365px-Facebook_f_logo_%282019%29.svg.png';
  $template_instagram_url = isset($options['instagram_url']) && strlen($options['instagram_url']) > 0 ? $options['instagram_url'] : 'https://instagram.com';
  $template_instagram_img = isset($options['instagram_img']) && strlen($options['instagram_img']) > 0 ? $options['instagram_img'] : 'https://img.favpng.com/9/25/24/computer-icons-instagram-logo-sticker-png-favpng-LZmXr3KPyVbr8LkxNML458QV3.jpg';
  $template_twitter_url = isset($options['twitter_url']) && strlen($options['twitter_url']) > 0 ? $options['twitter_url'] : 'https://twitter.com';
  $template_twitter_img = isset($options['twitter_img']) && strlen($options['twitter_img']) > 0 ? $options['twitter_img'] : 'https://logos-world.net/wp-content/uploads/2020/04/Twitter-Logo.png';

  if (isset($options['content'])) {
    $template_content = explode('|', $options['content']);
  } else {
    $template_content = array();
  }
?>

<body style="word-spacing:normal;">
  <div style="">
    <!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:800px;" width="800" bgcolor="<?php echo $template_header_color ?>" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
    <div style="background:<?php echo $template_header_color ?>;background-color:<?php echo $template_header_color ?>;margin:0px auto;max-width:800px;">
      <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:<?php echo $template_header_color ?>;background-color:<?php echo $template_header_color ?>;width:100%;">
        <tbody>
          <tr>
            <td style="direction:ltr;font-size:0px;padding:10px 0;text-align:center;">
              <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:800px;" ><![endif]-->
              <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                  <tbody>
                    <tr>
                      <td align="left" style="font-size:0px;padding:10px 40px;word-break:break-word;">
                        <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
                          <tbody>
                            <tr>
                              <td style="width:180px;">
                                <img height="auto" src="<?php echo $template_header_logo ?>" style="border:none;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px;" title="" width="180" />
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!--[if mso | IE]></td></tr></table><![endif]-->
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!--[if mso | IE]></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:800px;" width="800" bgcolor="#ffffff" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
    <div style="background:#ffffff;background-color:#ffffff;margin:0px auto;max-width:800px;">
      <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;background-color:#ffffff;width:100%;">
        <tbody>
          <tr>
            <td style="direction:ltr;font-size:0px;padding:32px 14px;text-align:left;">
              <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:572px;" ><![endif]-->
              <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                  <tbody>

                    <?php if (count($template_content) > 0) :?>
                      <?php foreach($template_content as $key => $content_part) { ?>
                      <tr>
                        <td align="left" style="font-size:0px;padding:0 26px 10px 26px;word-break:break-word;">
                          <div style="font-family:Arial, sans-serif;font-size:13px;line-height:22px;text-align:left;color:#000000;">
                            <p style="line-height: 22px; text-align: left; margin: 0;color:#000000;font-size:16px;font-family:Helvetica,Arial,sans-serif"><b><?php echo trim($content_part) ?></b></p>
                          </div>
                        </td>
                      </tr>
                      <?php } ?>
                    <?php endif ?>
                  </tbody>
                </table>
              </div>
              <!--[if mso | IE]></td></tr></table><![endif]-->
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!--[if mso | IE]></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:800px;" width="800" bgcolor="#ffffff" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
    <div style="background:#ffffff;background-color:#ffffff;margin:0px auto;max-width:800px;">
      <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;background-color:#ffffff;width:100%;">
        <tbody>
          <tr>
            <td style="direction:ltr;font-size:0px;padding:0 0 0 0;text-align:center;">
              <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:800px;" ><![endif]-->
              <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                  <tbody>
                    <tr>
                      <td align="center" style="font-size:0px;padding:0px 25px;padding-right:0px;padding-bottom:0px;padding-left:0px;word-break:break-word;">
                        <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
                          <tbody>
                            <tr>
                              <td style="width:800px;">
                                <img height="auto" src="<?php echo $template_footer_logo ?>" style="border:none;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px;" title="" width="800" />
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!--[if mso | IE]></td></tr></table><![endif]-->
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!--[if mso | IE]></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:800px;" width="800" bgcolor="<?php echo $template_footer_color ?>" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
    <div style="background:<?php echo $template_footer_color ?>;background-color:<?php echo $template_footer_color ?>;margin:0px auto;max-width:800px;">
      <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:<?php echo $template_footer_color ?>;background-color:#<?php echo $template_footer_color ?>;width:100%;">
        <tbody>
          <tr>
            <td style="direction:ltr;font-size:0px;padding:16px 0 0 0;text-align:center;">
              <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:800px;" ><![endif]-->
              <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                  <tbody>
                    <tr>
                      <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                        <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:1;text-align:left;color:#000000;">
                          <p style="line-height: 32px; text-align: center; margin: 0;color:#ffffff;font-size:24px;font-family:Helvetica,Arial,sans-serif;"><?php echo $template_footer_headline ?></p>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!--[if mso | IE]></td></tr></table><![endif]-->
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!--[if mso | IE]></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:800px;" width="800" bgcolor="<?php echo $template_footer_color ?>" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
    <div style="background:<?php echo $template_footer_color ?>;background-color:<?php echo $template_footer_color ?>;margin:0px auto;max-width:800px;">
      <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:<?php echo $template_footer_color ?>;background-color:#<?php echo $template_footer_color ?>;width:100%;">
        <tbody>
          <tr>
            <td style="direction:ltr;font-size:0px;padding:0 0 12px;text-align:center;">
              <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:800px;" ><![endif]-->
              <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                  <tbody>
                    <tr>
                      <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                        <!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" ><tr><td><![endif]-->
                        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="float:none;display:inline-table;">
                          <tr>
                            <td style="padding:4px;vertical-align:middle;">
                              <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-radius:3px;width:30px;">
                                <tr>
                                  <td style="font-size:0;height:30px;vertical-align:middle;width:30px;">
                                    <a href="<?php echo $template_facebook_url ?>">
                                      <img height="30" src="<?php echo $template_facebook_img ?>" style="border-radius:3px;display:block;" width="30" />
                                    </a>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                        <!--[if mso | IE]></td><td><![endif]-->
                        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="float:none;display:inline-table;">
                          <tr>
                            <td style="padding:4px;vertical-align:middle;">
                              <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-radius:3px;width:30px;">
                                <tr>
                                  <td style="font-size:0;height:30px;vertical-align:middle;width:30px;">
                                    <a href="<?php echo $template_instagram_url ?>">
                                      <img height="30" src="<?php echo $template_instagram_img ?>" style="border-radius:3px;display:block;" width="30" />
                                    </a>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                        <!--[if mso | IE]></td><td><![endif]-->
                        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="float:none;display:inline-table;">
                          <tr>
                            <td style="padding:4px;vertical-align:middle;">
                              <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-radius:3px;width:30px;">
                                <tr>
                                  <td style="font-size:0;height:30px;vertical-align:middle;width:30px;">
                                    <a href="<?php echo $template_twitter_url ?>">
                                      <img height="30" src="<?php echo $template_twitter_img ?>" style="border-radius:3px;display:block;" width="30" />
                                    </a>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                        <!--[if mso | IE]></td></tr></table><![endif]-->
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!--[if mso | IE]></td></tr></table><![endif]-->
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!--[if mso | IE]></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:800px;" width="800" bgcolor="<?php echo $template_footer_color ?>" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
    <div style="background:<?php echo $template_footer_color ?>;background-color:<?php echo $template_footer_color ?>;margin:0px auto;max-width:800px;">
      <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:<?php echo $template_footer_color ?>;background-color:#<?php echo $template_footer_color ?>;width:100%;">
        <tbody>
          <tr>
            <td style="direction:ltr;font-size:0px;padding:0 0 0 0;text-align:center;">
              <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:800px;" ><![endif]-->
              <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                  <tbody>
                    <tr>
                      <td align="left" style="font-size:0px;padding:0 0 12px 0;word-break:break-word;">
                        <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:1;text-align:left;color:#000000;">
                          <p style="line-height: 16px; text-align: center; margin: 0;color:#ffffff;font-size:14px;font-family:Helvetica,Arial,sans-serif;">This email was sent by: <a style="color: #ffffff;" href="mailto:<?php echo $template_sent_by ?>"><?php echo $template_sent_by ?></a></p>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td align="left" style="font-size:0px;padding:0 0 24px 0;word-break:break-word;">
                        <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:1;text-align:left;color:#000000;">
                          <p style="line-height: 16px; text-align: center; margin: 0;color:#ffffff;font-size:14px;font-family:Helvetica,Arial,sans-serif;">For any questions please send an email to: <a style="color: #ffffff;" href="mailto:<?php echo $template_info_mail ?>"><?php echo $template_info_mail ?></a></p>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!--[if mso | IE]></td></tr></table><![endif]-->
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!--[if mso | IE]></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:800px;" width="800" bgcolor="<?php echo $template_footer_color ?>" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
    <div style="background:<?php echo $template_footer_color ?>;background-color:<?php echo $template_footer_color ?>;margin:0px auto;max-width:800px;">
      <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:<?php echo $template_footer_color ?>;background-color:#<?php echo $template_footer_color ?>;width:100%;">
        <tbody>
          <tr>
            <td style="direction:ltr;font-size:0px;padding:0 0 12px;text-align:center;">
              <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:800px;" ><![endif]-->
              <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                  <tbody>
                    <tr>
                      <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                        <!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" ><tr><td><![endif]-->
                        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="float:none;display:inline-table;">
                          <tr>
                            <td style="padding:0;vertical-align:middle;">
                              <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:120px;">
                                <tr>
                                  <td style="font-size:0;vertical-align:middle;width:120px;">
                                    <a style="color:#ffffff;" href="<?php echo $template_footer_first_link_url ?>">
                                      <p style="line-height: 16px; text-align: right; margin: 0;color:#ffffff;font-size:14px;font-family:Helvetica,Arial,sans-serif;"><?php echo $template_footer_first_link_text ?> |&nbsp;</p>
                                    </a>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                        <!--[if mso | IE]></td><td><![endif]-->
                        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="float:none;display:inline-table;">
                          <tr>
                            <td style="padding:0;vertical-align:middle;">
                              <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:120px;">
                                <tr>
                                  <td style="font-size:0;vertical-align:middle;width:120px;">
                                    <a style="color:#ffffff;" href="<?php echo $template_footer_second_link_url ?>">
                                      <p style="line-height: 16px; text-align: left; margin: 0;color:#ffffff;font-size:14px;font-family:Helvetica,Arial,sans-serif;"><?php echo $template_footer_second_link_text?></p>
                                    </a>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                        <!--[if mso | IE]></td></tr></table><![endif]-->
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!--[if mso | IE]></td></tr></table><![endif]-->
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!--[if mso | IE]></td></tr></table><![endif]-->
  </div>
</body>

</html>
