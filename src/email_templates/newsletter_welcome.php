<!DOCTYPE html>
<html lang="en">
  <style>
    p {
      margin: 10px 0;
    }
  </style>
<body>

  <h3>Hello <?= $email_data['name'] ?></h3>

  <p>Thank you for joining the <?= SHOP_NAME ?> community!</p>

  <p>As a subscriber, you’re now on the inside track to enjoy exclusive updates, early access to new collections, and special offers created just for you. We're here to help you find styles that make you feel confident, comfortable, and completely yourself.
  </p>
  
  <p>Keep an eye on your inbox—we’ll keep you inspired with the latest trends, seasonal essentials, and style tips!</p>
  
  <p>
    <strong>
      Best regards, <br/>
      The <?= SHOP_NAME ?> Team
    </strong>
  </p>
  
  <p>
    <a style="font-size: 12px;" href="<?= (IS_LOCAL ? 'http://localhost' .DOC_ROOT : 'https://' . DOMAIN . '/') ?>newsletter-deletion?token=<?= $email_data['token'] ?>">Delete Subscription</a>
  </p>

</body>
</html>