<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Copy of message sent to <?= SHOP_NAME ?> administrator</title>
	
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
                <td style="padding: 50px 40px 0; text-align: center;" align="center">
                  <p style="margin: 0; font-size: 14px; font-weight: 400; line-height: 22px;">
                    <strong>Sender</strong> <br>
                    <?= $email_data['name'] ?>
                  </p>
                </td>
              </tr> <!-- text 1 -->

              <tr>
                <td style="padding: 20px 40px 0; text-align: center;" align="center">
                  <p style="margin: 0; font-size: 14px; font-weight: 400; line-height: 22px;">
                    <strong>From email</strong> <br>
                    <?= $email_data['email'] ?>
                  </p>
                </td>
              </tr> <!-- text 2 -->

              <tr>
                <td style="padding: 20px 40px 0; text-align: center;" align="center">
                  <p style="margin: 0; font-size: 14px; font-weight: 400; line-height: 22px;">
                    <strong>Subject</strong> <br>
                    <?= $email_data['subject'] ?>
                  </p>
                </td>
              </tr> <!-- text 3 -->

              <tr>
                <td style="padding: 20px 40px 0; text-align: center;" align="center">
                  <p style="margin: 0; font-size: 14px; font-weight: 400; line-height: 22px;">
                    <strong>Message content</strong> <br>
                    <?= $email_data['message'] ?>
                  </p>
                </td>
              </tr> <!-- text 4 -->

              <tr>
                <td style="padding: 20px 40px 50px; text-align: center;" align="center">
                  <p style="margin: 0; font-size: 16px; font-weight: 400; line-height: 24px;">
                    <strong>
                      Best regards, <br>
                      The <?= SHOP_NAME ?> Team
                    </strong>
                  </p>
                </td>
              </tr> <!-- text 5 -->
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