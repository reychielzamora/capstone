<?php
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mails
{
    public function sendGenPass($email, $newpass)
    {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->SMTPAuth   = true;
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->Username   = 'crestinemaemendezromano0217@gmail.com';
        $mail->Password   = 'zutggtbanddnzquy';

        $mail->setFrom('crestinemaemendezromano0217@gmail.com');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Here is the subject';
        $mail->Body    = "Hello $email,<br><br>Your Password is: $newpass<br><br>Best regards,<br>The Team";
        $mail->AltBody = "Hello ,\n\nYour OTP is:  $newpass\n\nBest regards,\nThe Team";

        $mail->send();
    }

    public function sendFile($email, $filePath)
    {
        try {
            if (!file_exists($filePath)) {
                throw new Exception('File not found: ' . $filePath);
            }

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            // ✅ Your Gmail credentials
            $mail->Username = 'crestinemaemendezromano0217@gmail.com';
            $mail->Password = 'zutggtbanddnzquy'; // Use App Password!
            $mail->setFrom('crestinemaemendezromano0217@gmail.com', 'Bais City Barangay');
            $mail->addAddress($email);

            // ✅ Professional email
            $mail->Subject = 'Your Official Certificate - Bais City Barangay';
            $mail->isHTML(true);
            $mail->Body = "
            <h2>📄 Official Certificate</h2>
            <p>Dear Resident,</p>
            <p>Please find your requested certificate attached.</p>
            <p><strong>Issued by:</strong> Office of the Punong Barangay</p>
            <p><em>This is an automated message. Do not reply.</em></p>
            <hr>
            <small>Bais City Barangay Clearance System</small>
        ";

            // ✅ Attach file
            $mail->addAttachment($filePath, basename($filePath));

            $mail->send();

            // ✅ Log success
            error_log("Certificate emailed to: $email | File: " . basename($filePath));

            return json_encode(['success' => true, 'message' => 'Email sent successfully']);
        } catch (Exception $e) {
            error_log("Email error: " . $e->getMessage());
            return json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function sendOfficialsEmail($email, $bodyHtml)
    {
        try {

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            $mail->Username = 'crestinemaemendezromano0217@gmail.com';
            $mail->Password = 'zutggtbanddnzquy';
            $mail->setFrom('crestinemaemendezromano0217@gmail.com', 'Bais City Barangay');
            $mail->addAddress($email);

            $mail->Subject = 'Your Official Certificate - Bais City Barangay';
            $mail->isHTML(true);
            $mail->Body = $bodyHtml;

            $mail->send();

            return ['success' => 'success'];
        } catch (Exception $e) {
            return [
                'error' => $mail->ErrorInfo
            ];
        }
    }
}
