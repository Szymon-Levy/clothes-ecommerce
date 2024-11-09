<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Welcome to <?= SHOP_NAME ?> - Confirm Your Newsletter Subscription</title>
	<style type="text/css">
		body {
			margin: 0;
		}
		table {
			border-spacing: 0;
		}
		td {
			padding: 0;
		}
		img {
			border: 0;
		}
		.wrapper {
      padding-bottom: 40px;
      width: 100%;
      table-layout: fixed;
      background-color: #f3f3f3;
		}
		.main {
      width: 100%;
      max-width: 600px;
      color: #000;
      font-family: 'Roboto', sans-serif;
      text-align: center;
		}
		@import url('https://fonts.googleapis.com/css2?family=Nanum+Myeongjo&family=Roboto:ital,wght@0,300;0,700;1,300&display=swap');

	</style>
</head>
<body>

	<center class="wrapper">

		<table class="main" width="100%">

      <!-- LOGO -->
       <tr>
          <td style="padding: 0;">
            <table width="100%" style="border-spacing: 0;">

              <tr>
                <td style="padding: 40px 0 20px; text-align: center;">
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
          <td style="padding: 0; background-color: #fff;">
            <table width="100%" style="border-spacing: 0;">

              <tr>
                <td style="padding: 50px 0 0; text-align: center;">
                  <h1 style="margin: 0; font-size: 35px; font-family: serif; text-transform: uppercase; font-weight: 400;">Hello <?= $email_data['name'] ?></h1>
                </td>
              </tr> <!-- heading -->

              <tr>
                <td style="padding: 20px 40px 0; text-align: center;">
                  <p style="margin: 0; font-size: 14px; font-weight: 400; line-height: 22px;">
                    Thank you for subscribing to <?= SHOP_NAME ?>'s newsletter! We're thrilled to have you on board and can’t wait to keep you updated with our latest collections, exclusive offers, and special discounts tailored just for you. To start receiving our updates, please confirm your subscription by clicking the link below:
                  </p>
                </td>
              </tr> <!-- text 1 -->

              <tr>
                <td style="padding: 30px 0 0; text-align: center;">
                  <a href="<?= (IS_LOCAL ? 'http://localhost' .DOC_ROOT : 'https://' . DOMAIN . '/') ?>newsletter-confirmation?token=<?= $email_data['token'] ?>">
                    <img src="https://<?= (IS_LOCAL ? FIXED_DOMAIN : DOMAIN) ?>/images/email/btn-activate-subscribtion.png" width="181" style="border: 0; max-width: 181px;" alt="Activate subscribtion">
                  </a>
                </td>
              </tr> <!-- button -->

              <tr>
                <td style="padding: 30px 40px 0; text-align: center;">
                  <p style="margin: 0; font-size: 14px; font-weight: 400; line-height: 22px;">
                    Once confirmed, you’ll be the first to know about our exciting new arrivals and member-only perks! If you have any questions, feel free to reach out to our support team.
                  </p>
                </td>
              </tr> <!-- text 2 -->

              <tr>
                <td style="padding: 20px 0 0; text-align: center;">
                  <p style="margin: 0; font-size: 16px; font-weight: 400; line-height: 24px;">
                    <strong>
                      Best regards, <br/>
                      The <?= SHOP_NAME ?> Team
                    </strong>
                  </p>
                </td>
              </tr> <!-- text 3 -->

              <tr>
                <td style="padding: 50px 0 0; text-align: center;">
                  <img src="https://<?= (IS_LOCAL ? FIXED_DOMAIN : DOMAIN) ?>/images/email/shopping-bags-woman.jpg" width="600" style="display: block; border: 0; max-width: 100%;" alt="Woman with shopping bags">
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
                <td style="padding: 20px 0 0; text-align: center;">
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