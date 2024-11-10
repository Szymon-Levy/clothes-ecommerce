<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Your newsletter subscribtion at <?= SHOP_NAME ?> is active</title>
	
</head>
<body style="margin: 0;">

	<center class="wrapper" style="padding-bottom: 40px; width: 100%; table-layout: fixed; background-color: #f3f3f3;">

		<table class="main" width="100%" style="border-spacing: 0; width: 100%; max-width: 600px; color: #000; font-family: 'Roboto', sans-serif; text-align: center;" align="center">

      <!-- LOGO -->
       <tr>
          <td style="padding: 0;">
            <table width="100%" style="border-spacing: 0;">

              <tr>
                <td style="padding: 40px 0 20px; text-align: center;" align="center">
                  <a href="https://<?= (IS_LOCAL ? FIXED_DOMAIN : DOMAIN) ?>">
                    <img src="https://<?= (IS_LOCAL ? FIXED_DOMAIN : DOMAIN) ?>/images/email/logo.png" width="110" style="border: 0;" alt="<?= SHOP_NAME ?>">
                  </a>
                </td>
              </tr>

            </table>
          </td>
       </tr>


        <!-- BODY -->
        <tr>
          <td style="padding: 0; background-color: #fff;" bgcolor="#fff">
            <table width="100%" style="border-spacing: 0;">

              <tr>
                <td style="padding: 50px 0 0; text-align: center;" align="center">
                  <h1 style="margin: 0; font-size: 35px; font-family: serif; text-transform: uppercase; font-weight: 400;">Welcome <?= $email_data['name'] ?></h1>
                </td>
              </tr> <!-- heading -->

              <tr>
                <td style="padding: 20px 40px 0; text-align: center;" align="center">
                  <p style="margin: 0; font-size: 14px; font-weight: 400; line-height: 22px;">
                    Thank you for joining the <?= SHOP_NAME ?> community!
                  </p>
                </td>
              </tr> <!-- text 1 -->

              <tr>
                <td style="padding: 20px 40px 0; text-align: center;" align="center">
                  <p style="margin: 0; font-size: 14px; font-weight: 400; line-height: 22px;">
                    As a subscriber, you’re now on the inside track to enjoy exclusive updates, early access to new collections, and special offers created just for you. We're here to help you find styles that make you feel confident, comfortable, and completely yourself. Keep an eye on your inbox—we’ll keep you inspired with the latest trends, seasonal essentials, and style tips!
                  </p>
                </td>
              </tr> <!-- text 2 -->

              <tr>
                <td style="padding: 20px 40px 0; text-align: center;" align="center">
                  <p style="margin: 0; font-size: 14px; font-weight: 400; line-height: 22px;">
                    If this was unintentional, you can easily unsubscribe by clicking the button below:
                  </p>
                </td>
              </tr> <!-- text 3 -->

              <tr>
                <td style="padding: 30px 0 0; text-align: center;" align="center">
                  <a href="<?= (IS_LOCAL ? 'http://localhost' .DOC_ROOT : 'https://' . DOMAIN . '/') ?>newsletter-deletion?token=<?= $email_data['token'] ?>">
                    <img src="https://<?= (IS_LOCAL ? FIXED_DOMAIN : DOMAIN) ?>/images/email/btn-delete-subscribtion.png" width="168" style="border: 0; max-width: 181px;" alt="Delete subscribtion">
                  </a>
                </td>
              </tr> <!-- button -->

              <tr>
                <td style="padding: 30px 0 0; text-align: center;" align="center">
                  <p style="margin: 0; font-size: 16px; font-weight: 400; line-height: 24px;">
                    <strong>
                      Best regards, <br>
                      The <?= SHOP_NAME ?> Team
                    </strong>
                  </p>
                </td>
              </tr> <!-- text 4 -->

              <tr>
                <td style="padding: 50px 0 0; text-align: center;" align="center">
                  <img src="https://<?= (IS_LOCAL ? FIXED_DOMAIN : DOMAIN) ?>/images/email/two-young-women-walking.jpg" width="600" style="display: block; border: 0; max-width: 100%;" alt="Woman with shopping bags">
                </td>
              </tr> <!-- image -->

            </table>
          </td>
       </tr>


        <!-- FOOTER -->
        <tr>
          <td style="padding: 0;">
            <table width="100%" style="border-spacing: 0;">

              <tr>
                <td style="padding: 20px 0 0; text-align: center;" align="center">
                  <a href="https://www.facebook.com/" style="padding: 0 5px; display: inline-block;">
                      <img src="https://<?= (IS_LOCAL ? FIXED_DOMAIN : DOMAIN) ?>/images/email/facebook.png" width="30" style="border: 0;" alt="facebook icon">
                  </a>
                  
                  <a href="https://www.instagram.com/" style="padding: 0 5px; display: inline-block;">
                      <img src="https://<?= (IS_LOCAL ? FIXED_DOMAIN : DOMAIN) ?>/images/email/instagram.png" width="30" style="border: 0;" alt="instagram icon">
                  </a>

                  <a href="https://www.tiktok.com/" style="padding: 0 5px; display: inline-block;">
                      <img src="https://<?= (IS_LOCAL ? FIXED_DOMAIN : DOMAIN) ?>/images/email/tiktok.png" width="30" style="border: 0;" alt="tiktok icon">
                  </a>

                  <a href="https://www.linkedin.com/" style="padding: 0 5px; display: inline-block;">
                      <img src="https://<?= (IS_LOCAL ? FIXED_DOMAIN : DOMAIN) ?>/images/email/linkedin.png" width="30" style="border: 0;" alt="linkedin icon">
                  </a>

                  <p style="margin: 0; padding: 10px 0 0;">
                    <a href="https://clothes-ecommerce.com.pl" style="font-size: 16px; color: #000; text-decoration: none;">
                      www.clothes-ecommerce.com.pl
                    </a>
                  </p>
                </td>
              </tr>

            </table>
          </td>
       </tr>


		</table> <!-- End Main Class -->

	</center> <!-- End Wrapper -->

</body>
</html>