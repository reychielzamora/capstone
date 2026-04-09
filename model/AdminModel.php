<?php
require __DIR__ . '/Mails.php';
require_once __DIR__ . '/Connection.php';

class AdminModel extends Dbh
{
    private $mysqli;
    private $sendMail;

    public function __construct()
    {
        $this->mysqli = Dbh::getInstance();
        $this->sendMail = new Mails();
    }

    public function getAllBrgy()
    {
        $result = $this->mysqli->query(" SELECT  b.BRGY_ID, b.BARANGAY, 
        COUNT(p.BRGY_ID) AS total_persons,
        COUNT(o.BRGY_ID) AS total_officials
        FROM tbl_brgy b
        LEFT JOIN tbl_personal_info p ON b.BRGY_ID = p.BRGY_ID
        LEFT JOIN tbl_officials o ON b.BRGY_ID = o.BRGY_ID
        GROUP BY b.BRGY_ID
    ");

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }
    public function getOfficials()
    {
        $result = $this->mysqli->query("SELECT  * FROM tbl_brgy b 
        INNER JOIN tbl_officials o ON b.BRGY_ID = o.BRGY_ID
        INNER JOIN tbl_position p ON o.POSITION = p.POSITION_ID
        GROUP BY b.BRGY_ID");

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function getOfficialsBrgy($brgy)
    {
        // Get barangay info
        $stmt = $this->mysqli->prepare("SELECT BRGY_ID, BARANGAY FROM tbl_brgy WHERE BRGY_ID = ?");
        $stmt->bind_param("i", $brgy);
        $stmt->execute();
        $brgyResult = $stmt->get_result()->fetch_assoc();

        if ($brgyResult) {
            $_SESSION['BARANGAY'] = $brgyResult['BARANGAY'];
            $_SESSION['BRGY_ID'] = $brgyResult['BRGY_ID'];
        }

        // Now get officials
        $stmt = $this->mysqli->prepare("SELECT o.*, p.POSITION_NAME 
        FROM tbl_officials o 
        LEFT JOIN tbl_position p ON o.POSITION = p.POSITION_ID 
        WHERE o.BRGY_ID = ? AND o.STATUS != ''");
        $stmt->bind_param("i", $brgy);
        $stmt->execute();
        $result = $stmt->get_result();

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function getStatus()
    {
        $result = $this->mysqli->query("SELECT STATUS_ID, STATUS_NAME FROM tbl_status ORDER BY STATUS_NAME ASC");

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }
    public function getPosition()
    {
        $result = $this->mysqli->query("SELECT POSITION_ID, POSITION_NAME, DATE_ADDED  FROM tbl_position ORDER BY DATE_ADDED ASC");

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function emailExists($email)
    {
        $count = 0;

        $stmt = $this->mysqli->prepare("SELECT COUNT(*) FROM tbl_officials WHERE EMAIL = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    }

    public function brgyExists($id)
    {
        $stmt = $this->mysqli->prepare("SELECT BRGY_ID FROM tbl_brgy WHERE BRGY_ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();

        $exists = $stmt->num_rows > 0;
        $stmt->close();

        return $exists;
    }

    public function generateToken($length = 32)
    {
        return bin2hex(random_bytes($length));
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

    public function addOfficials($fname, $lname, $mname, $dob, $pob, $cs, $email, $contact, int $position, $brgy, $title, $photoName, $emp_id)
    {

        date_default_timezone_set('Asia/Manila');
        $date = date('Y-m-d H:i:s');

        $newpass = $this->generatePassword();
        $hashed = password_hash($newpass, PASSWORD_DEFAULT);

        $check = $this->mysqli->prepare("SELECT u_email FROM tbl_users WHERE u_email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            return 1;
        }

        $stmt = $this->mysqli->prepare("INSERT INTO tbl_officials (PHOTO, EMP_ID, F_NAME, L_NAME, M_NAME, DOB, POB, CIVIL_STATUS, EMAIL, CONTACT, POSITION, BRGY_ID, TITLE, OFF_SIGNATURE, DATE_STARTED, DATE_ENDED, STATUS) VALUES (?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL ,?, NULL, 'active')");

        $stmt->bind_param(
            "sssssssissiiss",
            $photoName,
            $emp_id,
            $fname,
            $lname,
            $mname,
            $dob,
            $pob,
            $cs,
            $email,
            $contact,
            $position,
            $brgy,
            $title,
            $date
        );

        if (!$stmt->execute()) return 2;

        $official_id = $this->mysqli->insert_id;
        $token = $this->generateToken();

        if ($position === 1) {
            $stmt = $this->mysqli->prepare("INSERT INTO tbl_users (google_uid, PP, u_email, u_username, u_password, user_role,u_otp, OTP_DATE, DATE_CREATED, u_status) VALUES (NULL,NULL, ?, NULL, ?, 2, NULL, ?, ?, 'yes')");

            $stmt->bind_param(
                "ssss",
                $email,
                $hashed,
                $date,
                $date
            );

            if (!$stmt->execute()) return 3;

            $user_id = $this->mysqli->insert_id;

            $stmt2 = $this->mysqli->prepare("INSERT INTO tbl_staff_login (USER_ID, OFFICIALS_ID, EMPLOYEE_ID, PASSWORD, TOKEN, LOG_STATUS) VALUES (?,?,?,?,?, 'active')");

            $stmt2->bind_param(
                "iisss",
                $user_id,
                $official_id,
                $emp_id,
                $hashed,
                $token
            );

            if (!$stmt2->execute()) return 4;

            $this->sendMail->sendGenPass($email, $newpass);
        }

        return true;
    }

    public function updateInfoOfficial($id, $fname, $mname, $lname, $dob, $pob, $cs, $email, $contact, $position, $brgy, $title, $emp_id, $photoToSave)
    {

        date_default_timezone_set('Asia/Manila');
        $date = date('Y-m-d H:i:s');

        $stmt = $this->mysqli->prepare(" UPDATE tbl_officials SET  PHOTO = ?, EMP_ID = ?, F_NAME = ?, L_NAME = ?, M_NAME = ?, DOB = ?, POB = ?, CIVIL_STATUS = ?, EMAIL = ?, CONTACT = ?, POSITION = ?, BRGY_ID = ?, TITLE = ? WHERE OFFICIAL_ID = ?");

        $stmt->bind_param("ssssssssssiisi", $photoToSave, $emp_id, $fname,  $lname, $mname, $dob, $pob, $cs, $email, $contact, $position, $brgy, $title, $id);

        $result = $stmt->execute();

        return $result;
    }

    public function toggleUserStatus(int $id)
    {
        $this->mysqli->begin_transaction();
        try {
            // Get current status
            $stmt = $this->mysqli->prepare("SELECT u_status FROM tbl_users WHERE u_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();

            if (!$result) {
                throw new Exception("User not found.");
            }

            $newStatus = ($result['u_status'] === 'yes') ? 'no' : 'yes';
            $piStatus = ($newStatus === 'yes') ? 'active' : 'deactivated';

            // Update both tables
            $stmt1 = $this->mysqli->prepare("UPDATE tbl_personal_info SET PI_STATUS = ? WHERE USER_ID = ?");
            $stmt1->bind_param("si", $piStatus, $id);
            $stmt1->execute();

            $stmt2 = $this->mysqli->prepare("UPDATE tbl_users SET u_status = ? WHERE u_id = ?");
            $stmt2->bind_param("si", $newStatus, $id);
            $stmt2->execute();

            $this->mysqli->commit();
            return $piStatus;
        } catch (Exception $e) {
            $this->mysqli->rollback();
            return false;
        }
    }

    public function requests()
    {
        $query = "SELECT 
                r.*,
                c.*, 
                u.PI_ID,u.USER_ID, u.FNAME, u.MNAME, u.LNAME, u.CONTACT, u.EMAIL
              FROM tbl_requests r
              INNER JOIN tbl_personal_info u
              ON r.USER_ID = u.USER_ID
              INNER JOIN tbl_certificates c 
              ON r.CERT_ID = c.CERT_ID
              ORDER BY r.REQ_DATE DESC";

        $stmt = $this->mysqli->query($query);

        if (!$stmt) {
            error_log("MySQL Error: " . $this->mysqli->error);
            return [];
        }

        return $stmt->fetch_all(MYSQLI_ASSOC);
    }
    public function officials()
    {
        $query = "SELECT * FROM tbl_officials o INNER JOIN tbl_brgy b ON o.BRGY_ID = b.BRGY_ID";

        $stmt = $this->mysqli->query($query);

        if (!$stmt) {
            error_log("MySQL Error: " . $this->mysqli->error);
            return [];
        }

        return $stmt->fetch_all(MYSQLI_ASSOC);
    }
    public function users()
    {
        $query = "SELECT * FROM tbl_users u INNER JOIN tbl_personal_info p ON u.u_id = p.USER_ID";

        $stmt = $this->mysqli->query($query);

        if (!$stmt) {
            error_log("MySQL Error: " . $this->mysqli->error);
            return [];
        }

        return $stmt->fetch_all(MYSQLI_ASSOC);
    }

    public function getRequests($rid, $uid)
    {
        $stmt = $this->mysqli->prepare("
        SELECT 
            r.*, 
            p.*, 
            c.*, 
            b.*
        FROM tbl_requests r 
        INNER JOIN tbl_personal_info p ON r.USER_ID = p.USER_ID 
        INNER JOIN tbl_certificates c ON r.CERT_ID = c.CERT_ID
        INNER JOIN tbl_brgy b ON p.BRGY_ID = b.BRGY_ID
        WHERE r.REQ_ID = ? AND p.USER_ID = ?
    ");

        $stmt->bind_param("ii", $rid, $uid);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }
    public function generate($uid, $pid, $rid, $cid)
    {
        $stmt = $this->mysqli->prepare("SELECT * 
        FROM tbl_requests r 
        INNER JOIN tbl_personal_info p 
        ON r.USER_ID = p.USER_ID 
        INNER JOIN tbl_certificates c
        ON r.CERT_ID = c.CERT_ID
        INNER JOIN tbl_brgy b
        ON p.BRGY_ID = b.BRGY_ID
        WHERE r.REQ_ID = ? AND p.USER_ID = ?");
        $stmt->bind_param("ii", $rid, $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        return $data;
    }

    public function insertPost(int $id, $title, $description, $files = [])
    {
        $files_json = json_encode($files);
        $stmt = $this->mysqli->prepare("INSERT INTO tbl_posts (BRGY_ID, TITLE, DESCRIPTION, FILES, DATE_CREATED, STATUS) VALUES (?, ?, ?, ?, NOW(), 1)");
        $stmt->bind_param("isss", $id, $title, $description, $files_json);

        return $stmt->execute();
    }

    public function getBrgy()
    {
        $result = $this->mysqli->query("SELECT COUNT(*) AS total_brgy FROM tbl_brgy");

        if ($row = $result->fetch_assoc()) {
            return $row['total_brgy']; // return integer directly
        }

        return 0;
    }

    public function getAllUsers()
    {
        $result = $this->mysqli->query("SELECT COUNT(u_id) AS total_users FROM tbl_users");

        if ($row = $result->fetch_assoc()) {
            return $row['total_users']; // return integer directly
        }

        return 0;
    }

    public function getAllUsersBrgy()
    {
        $result = $this->mysqli->query("SELECT p.*,b.*,u.*, COUNT(u.u_id) AS total_users
        FROM tbl_users u 
        LEFT JOIN tbl_personal_info p ON u.u_id = p.USER_ID 
        LEFT JOIN tbl_brgy b ON p.BRGY_ID = b.BRGY_ID");

        $row = $result->fetch_all(MYSQLI_ASSOC);

        return $row;
    }

    public function seeUserInfo(int $id)
    {
        $stmt = $this->mysqli->prepare("SELECT 
        u.*, 
        p.*, 
        r.*, 
        c.CERT_NAME,
        b.BARANGAY AS BARANGAY_NAME
    FROM tbl_users u
    LEFT JOIN tbl_personal_info p ON u.u_id = p.USER_ID
    LEFT JOIN tbl_requests r ON u.u_id = r.USER_ID
    LEFT JOIN tbl_certificates c ON r.CERT_ID = c.CERT_ID
    LEFT JOIN tbl_brgy b ON p.BRGY_ID = b.BRGY_ID
    WHERE u.u_id = ?
    ORDER BY r.REQ_DATE DESC
    LIMIT 1");

        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        $row = $result->fetch_assoc();

        return $row ?? [];
    }

    public function getAllReq()
    {
        $result = $this->mysqli->query("SELECT COUNT(REQ_ID) AS total_req FROM tbl_requests");

        if ($row = $result->fetch_assoc()) {
            return $row['total_req']; 
        }

        return 0;
    }

    public function seeOfficialData($id)
    {
        $stmt = $this->mysqli->prepare("SELECT o.*, s.STATUS_ID, s.STATUS_NAME, b.BRGY_ID, b.BARANGAY
        FROM tbl_officials o
        LEFT JOIN tbl_status s ON o.CIVIL_STATUS = s.STATUS_ID
        LEFT JOIN tbl_brgy b ON o.BRGY_ID = b.BRGY_ID
        WHERE o.OFFICIAL_ID = ?
    ");

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function activities(int $role)
    {
        $stmt = $this->mysqli->prepare("SELECT * 
        FROM tbl_posts WHERE BRGY_ID = ? AND STATUS != 3");
        $stmt->bind_param("i", $role);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function updatePostStatus(int $postId, int $status)
    {
        $stmt = $this->mysqli->prepare("UPDATE tbl_posts SET STATUS = ? WHERE ID = ?");
        $stmt->bind_param("ii", $status, $postId);

        return $stmt->execute();
    }

    public function deletePost(int $id)
    {
        $stmt = $this->mysqli->prepare("UPDATE tbl_posts SET STATUS = 3 WHERE ID = ?");
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    public function getUsersPerMonth($year)
    {
        $stmt = $this->mysqli->prepare("
        SELECT 
            MONTH(DATE_CREATED) AS month,
            COUNT(*) AS total
        FROM tbl_users
        WHERE YEAR(DATE_CREATED) = ?
        GROUP BY MONTH(DATE_CREATED)
        ORDER BY month ASC
    ");

        $stmt->bind_param("i", $year);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getYearRange()
    {
        $result = $this->mysqli->query("
        SELECT 
            MIN(YEAR(DATE_CREATED)) AS min_year,
            MAX(YEAR(DATE_CREATED)) AS max_year
        FROM tbl_users
    ");

        return $result->fetch_assoc();
    }
    public function getPostsPerBarangay()
    {
        $stmt = $this->mysqli->prepare("
        SELECT 
            b.BARANGAY,
            COUNT(p.BRGY_ID) AS total
        FROM tbl_posts p
        INNER JOIN tbl_brgy b ON b.BRGY_ID = p.BRGY_ID
        GROUP BY p.BRGY_ID
        ORDER BY total DESC
    ");

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function changePassword($u_id, $current, $newpass)
    {
        // 1. Get user password
        $stmt = $this->mysqli->prepare("
        SELECT u_password 
        FROM tbl_users 
        WHERE u_id = ?
    ");
        $stmt->bind_param("i", $u_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            return ['status' => 'error', 'message' => 'User not found'];
        }

        // 2. Verify current password
        if (!password_verify($current, $user['u_password'])) {
            return ['status' => 'error', 'message' => 'Current password is incorrect'];
        }

        // 3. Hash new password
        $hashed = password_hash($newpass, PASSWORD_DEFAULT);

        // 4. Update tbl_users
        $stmt1 = $this->mysqli->prepare("
        UPDATE tbl_users SET u_password = ? WHERE u_id = ?
    ");
        $stmt1->bind_param("si", $hashed, $u_id);
        $stmt1->execute();

        // 5. Update tbl_staff_login (if exists)
        $stmt2 = $this->mysqli->prepare("
        UPDATE tbl_staff_login SET PASSWORD = ? WHERE USER_ID = ?
    ");
        $stmt2->bind_param("si", $hashed, $u_id);
        $stmt2->execute();

        return [
            'status' => 'success',
            'message' => 'Password updated successfully'
        ];
    }

    public function endTerm($off_id, $user_id, $log_id)
    {
        $now = date('Y-m-d H:i:s');
        $this->mysqli->begin_transaction();

        try {
            // Only update staff login and user if log_id exists
            if (!empty($log_id)) {
                // 1️⃣ Update staff login
                $stmt1 = $this->mysqli->prepare("UPDATE tbl_staff_login SET LOG_STATUS = 'ended' WHERE OFFICIALS_LOG_ID = ?");
                $stmt1->bind_param("i", $log_id);
                $stmt1->execute();

                // 2️⃣ Update user
                $stmt2 = $this->mysqli->prepare("UPDATE tbl_users SET u_status = 'no', DATE_CREATED = ? WHERE u_id = ?");
                $stmt2->bind_param("si", $now, $user_id);
                $stmt2->execute();
            }

            // Always update officials
            $stmt3 = $this->mysqli->prepare("UPDATE tbl_officials SET STATUS = 'ended', DATE_ENDED = ? WHERE OFFICIAL_ID = ?");
            $stmt3->bind_param("si", $now, $off_id);
            $stmt3->execute();

            $this->mysqli->commit();
            return ['success' => true];
        } catch (Exception $e) {
            $this->mysqli->rollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getOfficialInfo($off_id)
    {
        $stmt = $this->mysqli->prepare("SELECT * 
        FROM tbl_officials o 
        INNER JOIN tbl_status s ON o.CIVIL_STATUS = s.STATUS_ID
        INNER JOIN tbl_position p ON o.POSITION = p.POSITION_ID
        WHERE o.OFFICIAL_ID = ? AND o.STATUS = 'active'");
        $stmt->bind_param("i", $off_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        return $data;
    }
}
