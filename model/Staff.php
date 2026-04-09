<?php
// model/OfficialModel.php

require_once __DIR__ . '/Connection.php';

class Staff extends Dbh
{
    private $mysqli;

    public function __construct()
    {
        $this->mysqli = Dbh::getInstance();
    }
    public function getAllBrgy(int $brgyID)
    {
        $stmt = $this->mysqli->prepare("
        SELECT 
            b.BRGY_ID, 
            b.BARANGAY,
            COUNT(DISTINCT p.BRGY_ID) AS total_persons,
            COUNT(DISTINCT CASE WHEN o.DATE_ENDED IS NULL THEN o.BRGY_ID END) AS total_officials
        FROM tbl_brgy b
        LEFT JOIN tbl_personal_info p ON b.BRGY_ID = p.BRGY_ID
        LEFT JOIN tbl_officials o ON b.BRGY_ID = o.BRGY_ID
        WHERE b.BRGY_ID = ? 
        GROUP BY b.BRGY_ID, b.BARANGAY
    ");
        $stmt->bind_param('i', $brgyID);
        $stmt->execute();
        $res = $stmt->get_result();

        $rows = [];
        while ($row = $res->fetch_assoc()) {
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

    public function getOfficialsBrgy($brgyID)
    {
        $stmt = $this->mysqli->prepare("SELECT b.*, o.*, p.POSITION_NAME 
        FROM tbl_brgy b 
        LEFT JOIN tbl_officials o ON b.BRGY_ID = o.BRGY_ID 
        LEFT JOIN tbl_position p ON o.POSITION = p.POSITION_ID WHERE b.BRGY_ID = ?");

        $stmt->bind_param("i", $brgyID);
        $stmt->execute();

        $result = $stmt->get_result();

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $_SESSION['BARANGAY'] = $row['BARANGAY'];
            $_SESSION['BRGY_ID'] = $row['BRGY_ID'];
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


    public function addOfficials($fname, $lname, $mname, $dob, $pob, $cs, $email, $contact, $position, $brgy, $title, $photoName, $emp_id)
    {

        date_default_timezone_set('Asia/Manila');
        $date = date('Y-m-d H:i:s');

        $stmt = $this->mysqli->prepare("INSERT INTO tbl_officials (PHOTO, EMP_ID, F_NAME, L_NAME, M_NAME, DOB, POB, CIVIL_STATUS, EMAIL, CONTACT, POSITION, BRGY_ID, TITLE, DATE_STARTED, DATE_ENDED, STATUS) VALUES (?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,NULL , 'active')");

        $stmt->bind_param(
            "sssssssisssiis",
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

        $result = $stmt->execute();

        return $result;
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

    public function requests($brgyID)
    {
        $stmt = $this->mysqli->prepare("SELECT 
            r.*,
            c.*,u.PI_ID, u.USER_ID, u.FNAME, u.MNAME, u.LNAME, u.CONTACT, u.EMAIL, u.BRGY_ID FROM tbl_requests r INNER JOIN tbl_personal_info u ON r.USER_ID = u.USER_ID INNER JOIN tbl_certificates c ON r.CERT_ID = c.CERT_ID WHERE u.BRGY_ID = ? AND r.REQ_STATUS != 'archived' ORDER BY r.REQ_DATE DESC");

        $stmt->bind_param('i', $brgyID);
        $stmt->execute();

        $result = $stmt->get_result();

        if (!$result) {
            error_log("MySQL Error: " . $this->mysqli->error);
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
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

    public function activities($brgyID)
    {
        $stmt = $this->mysqli->prepare("SELECT * 
        FROM tbl_posts WHERE BRGY_ID = ? AND STATUS != 3");
        $stmt->bind_param("i", $brgyID);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

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

    public function getAllReq()
    {
        $result = $this->mysqli->query("SELECT COUNT(REQ_ID) AS total_req FROM tbl_requests");

        if ($row = $result->fetch_assoc()) {
            return $row['total_req']; // return integer directly
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

    public function insertSignature(int $official_id, $fname, $mname, $lname, $dob, $pob, $cs, $email, $contact, $signaturePath, $avatarPath)
    {
        $stmt = $this->mysqli->prepare("UPDATE tbl_officials SET 
        PHOTO = ?,
        F_NAME  = ?,
        L_NAME = ?,
        M_NAME = ?,
        DOB = ?,
        POB = ?,
        CIVIL_STATUS  = ?,
        EMAIL = ?,
        CONTACT = ?,
        OFF_SIGNATURE = ?
        WHERE OFFICIAL_ID = ?
");
        // $stmt = $this->mysqli->prepare("UPDATE tbl_officials SET OFF_SIGNATURE = ? WHERE OFFICIAL_ID = ?");
        $stmt->bind_param("ssssssisssi", $avatarPath, $fname, $lname, $mname,  $dob, $pob, $cs, $email, $contact, $signaturePath, $official_id);
        $result = $stmt->execute();

        if ($result) {
            error_log("Signature saved for OFFICIAL_ID: $official_id, Path: $signaturePath");
        }

        return $result;
    }

    public function declineRequests($id)
    {
        $stmt = $this->mysqli->prepare("UPDATE tbl_requests SET REQ_STATUS = 'declined' WHERE CTRL_NUM = ?");
        $stmt->bind_param("s", $id);
        $result = $stmt->execute();

        return $result;
    }

    public function archiveRequests($id)
    {
        $stmt = $this->mysqli->prepare("UPDATE tbl_requests SET REQ_STATUS = 'archived' WHERE CTRL_NUM = ?");
        $stmt->bind_param("s", $id);
        $result = $stmt->execute();

        return $result;
    }

    public function updatePostStatus(int $postId, int $status)
    {
        $stmt = $this->mysqli->prepare("UPDATE tbl_posts SET STATUS = ? WHERE ID = ?");
        $stmt->bind_param("ii", $status, $postId);

        return $stmt->execute();
    }
    public function approveRequest($user_id)
    {
        $this->mysqli->begin_transaction();

        try {
            $now = date('Y-m-d H:i:s');

            $stmt1 = $this->mysqli->prepare("
            UPDATE tbl_requests 
            SET REQ_STATUS = 'approved' 
            WHERE REQ_ID = ?
        ");
            $stmt1->bind_param("i", $user_id);
            $stmt1->execute();

            $this->mysqli->commit();

            return [
                'success' => true,
                'message' => 'Request approved successfully'
            ];
        } catch (Exception $e) {
            $this->mysqli->rollback();

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
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
}
