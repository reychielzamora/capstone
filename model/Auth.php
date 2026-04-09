<?php
require_once(__DIR__ . '/Connection.php');
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Signup extends Dbh
{

    private $mysqli;

    public function __construct()
    {
        $this->mysqli = Dbh::getInstance();
    }


    public function generateOtp()
    {
        return str_pad(mt_rand(100000, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function generatePassword($length = 12)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+';
        $password = '';
        $maxIndex = strlen($chars) - 1;

        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, $maxIndex)];
        }

        return $password;
    }

    public function signup($email, $hashedPassword)
    {
        $otp = $this->generateOtp();

        $dt = new DateTime('now', new DateTimeZone('Asia/Manila'));
        $nowdate = $dt->format('Y-m-d H:i:s');

        $checkStmt = $this->mysqli->prepare("SELECT u_email FROM tbl_users WHERE u_email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            return 1; // email exists
        }

        session_start();

        $stmt = $this->mysqli->prepare("INSERT INTO tbl_users (u_email, u_password, u_otp, OTP_DATE, DATE_CREATED, u_status) VALUES (?,?,?,?,?,'no')");
        if (!$stmt) {
            error_log("Prepare failed: " . $this->mysqli->error);
            return 2;
        }

        $stmt->bind_param("sssss", $email, $hashedPassword, $otp, $nowdate, $nowdate);

        if (!$stmt->execute()) {
            error_log("Execute failed: " . $stmt->error);
            return 3;
        }

        $userId = $stmt->insert_id;
        $result = $this->mysqli->query("SELECT u_email, u_otp, u_id FROM tbl_users WHERE u_id = $userId");

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['u_email'] = $row['u_email'];
            $_SESSION['u_id'] = $row['u_id'];

            $this->sendMail($row['u_email'], $row['u_otp']);

            return 4;
        }

        return false;
    }

    public function insertEmail($email, $name)
    {
        session_start();

        $stmt = $this->mysqli->prepare("SELECT * FROM tbl_users WHERE u_email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();

            $_SESSION['u_id'] = $row['u_id'];
            $_SESSION['user_role'] = $row['user_role'];
            $_SESSION['u_email'] = $row['u_email'];

            $routes = [
                1 => '/bais-documents/public/admin/home.php',
                2 => '/bais-documents/public/staff/home.php',
                3 => '/bais-documents/public/users/home.php'
            ];

            return [
                'id' => $row['u_id'],
                'redirect' => $routes[$row['user_role']]
            ];
        }

        $role = 3;
        $status = 'yes';

        $insert = $this->mysqli->prepare(
            "INSERT INTO tbl_users(u_email,u_name,user_role,u_status) VALUES(?,?,?,?)"
        );
        $insert->bind_param("ssis", $email, $name, $role, $status);
        $insert->execute();

        $newId = $insert->insert_id;

        $_SESSION['u_id'] = $newId;
        $_SESSION['user_role'] = 3;
        $_SESSION['u_email'] = $email;

        return [
            'id' => $newId,
            'redirect' => '/bais-documents/public/users/home.php'
        ];
    }

    public function sendMail($email, $otp)
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
        $mail->Body    = "Hello $email,<br><br>Your OTP is: $otp<br><br>Best regards,<br>The Team";
        $mail->AltBody = "Hello ,\n\nYour OTP is:  $otp\n\nBest regards,\nThe Team";

        $mail->send();
    }

    public function verify($otp, $email)
    {
        $stmt = $this->mysqli->prepare("SELECT u_email, u_otp, OTP_DATE FROM tbl_users WHERE u_email = ? AND u_otp = ?");
        if (!$stmt) {
            error_log("Prepare failed: " . $this->mysqli->error);
            return 3;
        }

        $stmt->bind_param("ss", $email, $otp);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $otpCreatedTime = strtotime($row['OTP_DATE']);
            $currentTime = time();

            // Check if OTP is within 20 minutes (1200 seconds)
            if (($currentTime - $otpCreatedTime) <= 1200) {
                if ($this->setVerified($email)) {
                    return 1; // Success
                } else {
                    return 2; // Failed to set verified
                }
            } else {
                return 2; // OTP expired
            }
        }
        return 3; // OTP not found
    }

    public function setVerified($email)
    {
        $stmt = $this->mysqli->prepare("UPDATE tbl_users SET u_status = 'verified' WHERE u_email = ?");
        if (!$stmt) {
            error_log("Update prepare failed: " . $this->mysqli->error);
            return false;
        }

        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;
        }

        error_log("Update execute failed: " . $stmt->error);
        return false;
    }

    public function resendOtp($email)
    {
        $otp = $this->generateOtp();

        session_start();
        $pass = $this->generatePassword($length = 12);
        $dt = new DateTime('now', new DateTimeZone('Asia/Manila'));
        $nowdate = $dt->format('Y-m-d H:i:s');

        $stmt = $this->mysqli->prepare("UPDATE tbl_users SET u_otp = ?, OTP_DATE = ? WHERE u_email = ?");
        $stmt->bind_param("sss", $otp, $nowdate, $email);

        if ($stmt->execute()) {
            $this->sendMail($email, $otp);
            return true;
        }

        return false;
    }

    public function generateToken($length = 32)
    {
        return bin2hex(random_bytes($length));
    }

    public function login($password, $email)
    {
        session_start();

        $stmt = $this->mysqli->prepare("SELECT * FROM tbl_users WHERE u_email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $status = $row["u_status"];
                $stored_password = $row["u_password"];

                if ($status == 'no') {
                    return 3;
                }

                if (password_verify($password, $stored_password)) {
                    $_SESSION['u_id'] = $row["u_id"];
                    $_SESSION['user_role'] = $row["user_role"];
                    $_SESSION['u_email'] = $row["u_email"];
                    $_SESSION['PP'] = $row["PP"];

                    $_SESSION['token_login'] = $this->generateToken();

                    $redirect = '';

                    switch ($_SESSION['user_role']) {
                        case 1:
                            $redirect = '../public/verification/';
                            break;

                        case 2:
                            $redirect = '../public/verification/';
                            break;

                        case 3:
                            $redirect = '../public/users/home';
                            break;

                        default:
                            $redirect = '../login.php';
                            break;
                    }

                    return $redirect;
                } else {
                    return 1;
                }
            }
        } else {
            return 2;
        }
    }

    public function insertGoogleUser($uid, $email, $fname, $lname, $photo)
    {
        session_start();
        $pass = $this->generatePassword();
        $dt = new DateTime('now', new DateTimeZone('Asia/Manila'));
        $nowdate = $dt->format('Y-m-d H:i:s');

        $check = $this->mysqli->prepare("SELECT google_uid FROM tbl_users WHERE google_uid=?");
        $check->bind_param("s", $uid);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            return 1;
        }

        $hashed = password_hash($pass, PASSWORD_DEFAULT);

        $stmt = $this->mysqli->prepare("INSERT INTO tbl_users (google_uid, PP, u_email, u_password, DATE_CREATED, u_status)
        VALUES (?,?,?,?,?,'yes')");

        if (!$stmt) return 2;

        $stmt->bind_param("sssss", $uid, $photo, $email, $hashed, $nowdate);

        if (!$stmt->execute()) return 3;

        $user_id = $this->mysqli->insert_id;
        $_SESSION['u_id'] = $user_id;
        $_SESSION['PP'] = $photo;
        $_SESSION['u_email'] = $email;

        $this->sendGenPass($email, $pass);
        $redirect = '../public/users/';

        return $redirect;
    }

    public function sendGenPass($email, $pass)
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
        $mail->Body    = "Hello $email,<br><br>Your Password is: $pass<br><br>Best regards,<br>The Team";
        $mail->AltBody = "Hello ,\n\nYour OTP is:  $pass\n\nBest regards,\nThe Team";

        $mail->send();
    }

    public function resetOtp($email, $otp)
    {
        try {
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
            $mail->Subject = 'OTP Code';
            $mail->Body    = "Your OTP is: <b>$otp</b>";

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function verifyChangePassword($otp, $email, $password)
    {
        $this->mysqli->begin_transaction();

        try {

            // 1. Get OTP + role + user id
            $stmt1 = $this->mysqli->prepare(
                "SELECT u_id, u_otp, user_role FROM tbl_users WHERE u_email = ?"
            );
            $stmt1->bind_param("s", $email);
            $stmt1->execute();
            $res1 = $stmt1->get_result();

            if (!$res1->num_rows) {
                $this->mysqli->rollback();
                return 1;
            }

            $row = $res1->fetch_assoc();

            // 2. Verify OTP
            if ($row['u_otp'] != $otp) {
                $this->mysqli->rollback();
                return 2;
            }

            $u_id = $row['u_id'];
            $role = $row['user_role'];

            // 3. Hash password
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            // 4. Update tbl_users
            $stmt2 = $this->mysqli->prepare(
                "UPDATE tbl_users SET u_password = ? WHERE u_email = ?"
            );
            $stmt2->bind_param("ss", $hashed, $email);
            $stmt2->execute();

            if ($stmt2->affected_rows === 0) {
                $this->mysqli->rollback();
                return 3;
            }

            // 🔥 5. If role is 1 or 3 → update tbl_staff_login
            if ($role == 1 || $role == 2) {
                $stmtStaff = $this->mysqli->prepare(
                    "UPDATE tbl_staff_login SET PASSWORD = ? WHERE USER_ID = ?"
                );
                $stmtStaff->bind_param("si", $hashed, $u_id);
                $stmtStaff->execute();
            }

            // 6. Clear OTP
            $stmt3 = $this->mysqli->prepare(
                "UPDATE tbl_users SET u_otp = NULL WHERE u_email = ?"
            );
            $stmt3->bind_param("s", $email);
            $stmt3->execute();

            // ✅ COMMIT
            $this->mysqli->commit();

            return true;
        } catch (Exception $e) {
            $this->mysqli->rollback();
            return "Error: " . $e->getMessage();
        }
    }

    public function requestPasswordReset($email)
    {
        $stmt = $this->mysqli->prepare("SELECT u_id, u_email FROM tbl_users WHERE u_email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if (!$res->num_rows) {
            return 1;
        }

        $otp = $this->generateOtp();

        $stmt = $this->mysqli->prepare("UPDATE tbl_users SET u_otp=? WHERE u_email=?");
        $stmt->bind_param("ss", $otp, $email);

        if (!$stmt->execute()) {
            return 0;
        }

        if (!$this->resetOtp($email, $otp)) {
            return 0;
        }

        return 2;
    }
}
