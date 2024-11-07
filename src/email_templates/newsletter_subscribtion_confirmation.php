<!DOCTYPE html>
<html lang="en">
  <style>
    p {
      margin: 10px 0;
    }
  </style>
<body>

  <h3>Hello <?= $email_data['name'] ?></h3>

  <p>Thank you for subscribing to <?= SHOP_NAME ?>'s newsletter! We're thrilled to have you on board and can’t wait to keep you updated with our latest collections, exclusive offers, and special discounts tailored just for you.</p>

  <p>To start receiving our updates, please confirm your subscription by clicking the link below:</p>

  <p>
    <a style="display: inline-block; padding: 6px 15px; background-color: black; color: white; text-decoration: none; font-weight: 700;" href="<?= (IS_LOCAL ? 'http://localhost' .DOC_ROOT : 'https://' . DOMAIN . '/') ?>newsletter-confirmation?token=<?= $email_data['token'] ?>">Activate Subscription</a>
  </p>

  <p>Once confirmed, you’ll be the first to know about our exciting new arrivals and member-only perks! If you have any questions, feel free to reach out to our support team.</p>

  <p>
    <strong>
      Best regards, <br/>
      The <?= SHOP_NAME ?> Team
    </strong>
  </p>

</body>
</html>