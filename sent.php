
<?php
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->SMTPAuth   = true;
$mail->Host = "smtp.gmail.com";
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

$mail->Username   = 'crestinemaemendezromano0217@gmail.com';
$mail->Password   = 'zutggtbanddnzquy';

$mail->setFrom('crestinemaemendezromano0217@gmail.com');
$mail->addAddress('fundadordiongie@gmail.com');

$mail->isHTML(true);
$mail->Subject = 'Here is the subject';
$mail->Body    = "Hello ,<br><br>Your OTP is: 123<br><br>Best regards,<br>The Team";
$mail->AltBody = "Hello ,\n\nYour OTP is:  123\n\nBest regards,\nThe Team";

$mail->send();


echo "Hi";