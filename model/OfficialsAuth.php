<?php
require __DIR__ . '/Mails.php';
require_once __DIR__ . '/Connection.php';

class OfficialsAuth extends Dbh
{
    private $mysqli;
    private $sendMail;

    public function __construct()
    {
        $this->mysqli = Dbh::getInstance();
        $this->sendMail = new Mails();
    }

    public function Login($emp_id, $emp_pass)
    {
        session_start();

        $stmt = $this->mysqli->prepare("SELECT EMPLOYEE_ID, PASSWORD, LOG_STATUS, USER_ID, OFFICIALS_LOG_ID FROM tbl_staff_login WHERE EMPLOYEE_ID = ? AND LOG_STATUS = 'active'");
        $stmt->bind_param('s', $emp_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $status = $row["LOG_STATUS"];
                $stored_password = $row["PASSWORD"];

                if ($status == 'no') {
                    return 3;
                }

                if (password_verify($emp_pass, $stored_password)) {

                    $user_id = $row["USER_ID"];

                    $_SESSION['USER_ID'] = $row["USER_ID"];
                    $_SESSION['EMPLOYEE_ID'] = $row["EMPLOYEE_ID"];
                    $_SESSION['OFFICIALS_LOG_ID'] = $row["OFFICIALS_LOG_ID"];

                    $stmt2 = $this->mysqli->prepare("SELECT * FROM 
                    tbl_users u
                    LEFT JOIN tbl_staff_login l ON u.u_id = l.USER_ID
                    LEFT JOIN tbl_officials o ON o.OFFICIAL_ID = l.OFFICIALS_ID
                    WHERE u.u_id = ?");
                    $stmt2->bind_param("i", $user_id);
                    $stmt2->execute();
                    $result2 = $stmt2->get_result();
                    $user = $result2->fetch_assoc();

                    $_SESSION['USER_ID'] = $user['USER_ID'];
                    $_SESSION['OFFICIAL_ID'] = $user['OFFICIAL_ID'];
                    $_SESSION['BRGY_ID'] = $user['BRGY_ID'];
                    $_SESSION['user_role'] = $user['user_role'];
                    $_SESSION['u_email'] = $user['u_email'];
                    $_SESSION['PP'] = $user['PP'];

                    if (!$user) {
                        return 4; // fallback if user not found
                    }

                    $redirect = '';
                    switch ($_SESSION['user_role']) {
                        case 1:
                            $redirect = '../admin/home';
                            break;
                        case 2:
                            $redirect = '../staff/home';
                            break;
                        default:
                            $redirect = '../index.php';
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
}
