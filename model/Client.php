<?php require_once __DIR__ . "/Connection.php";

class Client extends Dbh
{
    private $mysqli;

    public function __construct()
    {
        $this->mysqli = Dbh::getInstance();
    }

    public function viewInfo($uid)
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM tbl_personal_info WHERE PI_ID = ?");
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }
    public function checkUser($user_id)
    {
        $sql = "SELECT * FROM tbl_users u INNER JOIN tbl_personal_info p ON u.u_id = p.USER_ID WHERE u.u_id = ?";

        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) {
            die("Query preparation failed: " . $this->mysqli->error);
        }

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }

    public function cert()
    {
        $result = $this->mysqli->query("SELECT CERT_ID, CERT_NAME FROM tbl_certificates ORDER BY CERT_NAME ASC");

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function controlNumber($format = '4-4-4')
    {
        // Generate a random number with enough digits
        $totalDigits = array_sum(explode('-', $format));
        $min = pow(10, $totalDigits - 1);
        $max = pow(10, $totalDigits) - 1;

        $number = str_pad(mt_rand($min, $max), $totalDigits, '0', STR_PAD_LEFT);

        // Parse the format and build the parts
        $parts = [];
        $position = 0;
        $segments = explode('-', $format);

        foreach ($segments as $segment) {
            $length = (int)$segment;
            $parts[] = substr($number, $position, $length);
            $position += $length;
        }

        return implode('-', $parts);
    }

    public function debugPhoto($photo)
    {
        error_log("=== PHOTO DEBUG ===");
        error_log("Raw photo: '" . $photo . "'");
        error_log("is_string: " . (is_string($photo) ? 'YES' : 'NO'));
        error_log("!empty: " . (!empty($photo) ? 'YES' : 'NO'));
        error_log("strlen: " . strlen($photo ?? ''));
        error_log("trim length: " . strlen(trim($photo ?? '')));
        error_log("==================");
    }

    public function getBrgy()
    {
        $result = $this->mysqli->query("SELECT BRGY_ID, BARANGAY FROM tbl_brgy ORDER BY BARANGAY ASC");

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function insertPersonalInfo($pid, $purpose, $userid, $keyId, $fname, $mname, $lname, $citizen, $sex, $civil, $age, $contact, $email, $street, int $brgy, $city, $type, $photo, $signature, $letter)
    {
        date_default_timezone_set('Asia/Manila');
        $date = date('Y-m-d H:i:s');

        $check = $this->mysqli->prepare('SELECT PI_ID, USER_ID FROM tbl_personal_info WHERE PI_ID = ? AND USER_ID = ?');
        $check->bind_param("ii", $pid, $userid);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $this->update($keyId, $purpose, $letter, $date, $pid, $userid, $fname, $mname, $lname, $citizen, $sex, $civil, $age, $contact, $email, $street, $brgy, $city, $photo, $signature);
            return 5;
        }

        $stmt = $this->mysqli->prepare("INSERT INTO tbl_personal_info (USER_ID, FNAME,MNAME,LNAME,CITIZEN,SEX,CIVIL,AGE,CONTACT,EMAIL,STREET,BRGY_ID,CITY,TYPE,PHOTO,SIGNATURE,PI_STATUS) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,'active')");

        if (!$stmt) {
            error_log($this->mysqli->error);
            return 1;
        }
        $stmt->bind_param(
            "issssssisssissss",
            $userid,
            $fname,
            $mname,
            $lname,
            $citizen,
            $sex,
            $civil,
            $age,
            $contact,
            $email,
            $street,
            $brgy,
            $city,
            $type,
            $photo,
            $signature
        );

        if (!$stmt->execute()) {
            error_log($stmt->error);
            return 2;
        }
        $this->insertReq($userid, $keyId, $purpose, $letter, $date,);
        return 5;
    }

    public function insertReq($userid, $keyId, $purpose, $letter, $date)
    {
        $num = $this->controlNumber();
        $stmt2 = $this->mysqli->prepare("INSERT INTO tbl_requests (USER_ID, CERT_ID,PURPOSE, LETTER, CTRL_NUM, REQ_DATE, REQ_STATUS) VALUES (?,?,?,?,?,?,?)");

        if (!$stmt2) {
            error_log($this->mysqli->error);
            return 3;
        }

        $status = "pending";

        $stmt2->bind_param(
            "iisssss",
            $userid,
            $keyId,
            $purpose,
            $letter,
            $num,
            $date,
            $status
        );

        if (!$stmt2->execute()) {
            return 4;
        }

        return true;
    }

    public function update($keyId, $purpose, $letter, $date, $pid, $userid, $fname, $mname, $lname, $citizen, $sex, $civil, $age, $contact, $email, $street, int $brgy, $city, $photo, $signature)
    {
        $this->debugPhoto($photo);

        // ⭐ GET CURRENT PHOTO & SIGNATURE
        $check = $this->mysqli->prepare("
        SELECT PHOTO, SIGNATURE 
        FROM tbl_personal_info 
        WHERE PI_ID=? AND USER_ID=?
    ");
        $check->bind_param("ii", $pid, $userid);
        $check->execute();
        $res = $check->get_result()->fetch_assoc();

        $dbPhoto = $res['PHOTO'] ?? '';
        $dbSignature = $res['SIGNATURE'] ?? '';

        $sql = "
        UPDATE tbl_personal_info SET
            FNAME=?,
            MNAME=?,
            LNAME=?,
            CITIZEN=?,
            SEX=?,
            CIVIL=?,
            AGE=?,
            CONTACT=?,
            EMAIL=?,
            STREET=?,
            BRGY_ID=?,
            CITY=?";

        $types = "ssssssisssis";
        $params = [
            $fname,
            $mname,
            $lname,
            $citizen,
            $sex,
            $civil,
            $age,
            $contact,
            $email,
            $street,
            $brgy,
            $city
        ];

        // ⭐ FIXED PHOTO LOGIC - Check if NEW FILE UPLOAD (not empty filename)
        if (!empty($photo) && is_string($photo) && strlen($photo) > 0 && $photo !== '') {
            // New photo uploaded → always update
            $sql .= ", PHOTO=?";
            $types .= "s";
            $params[] = $photo;
            error_log("PHOTO UPDATED: " . $photo); // Debug log
        }

        // ⭐ FIXED SIGNATURE LOGIC
        if (!empty($signature) && is_string($signature) && strlen($signature) > 0 && $signature !== '') {
            // New signature uploaded → always update
            $sql .= ", SIGNATURE=?";
            $types .= "s";
            $params[] = $signature;
            error_log("SIGNATURE UPDATED: " . $signature); // Debug log
        }

        $sql .= " WHERE USER_ID=? AND PI_ID=?";
        $types .= "ii";
        $params[] = $userid;
        $params[] = $pid;

        error_log("SQL: " . $sql); // Debug log
        error_log("TYPES: " . $types); // Debug log

        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) {
            error_log("PREPARE FAILED: " . $this->mysqli->error);
            return false;
        }

        $stmt->bind_param($types, ...$params);

        if (!$stmt->execute()) {
            error_log("EXECUTE FAILED: " . $stmt->error);
            return false;
        }

        // Verify update worked
        $rowsAffected = $stmt->affected_rows;
        error_log("ROWS AFFECTED: " . $rowsAffected);

        $stmt->close();

        // ⭐ INSERT REQUEST AFTER UPDATE
        $this->insertReq($userid, $keyId, $purpose, $letter, $date);
        return true;
    }
    public function getUserRequest($user_id)
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM tbl_requests r
        INNER JOIN tbl_personal_info p
        ON r.USER_ID = p.USER_ID
        INNER JOIN tbl_certificates c
        ON r.CERT_ID = c.CERT_ID
        WHERE p.USER_ID = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }
    public function getPosts(int $limit = 3, int $offset = 0): array
    {
        $stmt = $this->mysqli->prepare(
            "SELECT id, title, description, date_created, FILES 
         FROM tbl_posts 
         ORDER BY id DESC 
         LIMIT ? OFFSET ?"
        );
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $row['FILES'] = json_decode($row['FILES'], true) ?: [];
            $posts[] = $row;
        }

        $stmt->close();
        return $posts;
    }

    public function searchStatus()
    {
        $stmt = $this->mysqli->query("SELECT STATUS_NAME FROM tbl_status");
        $rows = [];
        while ($row = $stmt->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function getAllBrgy()
    {
        $result = $this->mysqli->query("SELECT BRGY_ID, BARANGAY FROM tbl_brgy");

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function activities()
    {
        $result = $this->mysqli->query("
        SELECT * 
        FROM tbl_posts 
        WHERE STATUS != 3
    ");

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function genders()
    {
        $result = $this->mysqli->query("SELECT * FROM tbl_gender");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function seeYourInfo($id)
    {
        $stmt = $this->mysqli->prepare("SELECT u.*, p.*, b.*
        FROM tbl_users u
        INNER JOIN tbl_personal_info p ON u.u_id = p.USER_ID
        INNER JOIN tbl_brgy b ON p.BRGY_ID = b.BRGY_ID
        WHERE u.u_id = ?
    ");

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function updateInfo($id, $fname, $mname, $lname, $dob, $pob, $cs, $gender, $email, $contact, $brgy, $street, $city, $signaturePath, $avatarPath)
    {
        try {
            $this->mysqli->begin_transaction();

            $stmt1 = $this->mysqli->prepare("UPDATE tbl_personal_info SET 
            FNAME = ?,
            MNAME = ?, 
            LNAME = ?,
            SEX = ?,
            CIVIL = ?,
            DOB = ?,
            POB = ?,
            CONTACT = ?,
            EMAIL = ?,
            STREET = ?,
            BRGY_ID = ?,
            CITY = ?,
            SIGNATURE = ?
            WHERE USER_ID = ?
        ");

            if (!$stmt1) {
                throw new Exception($this->mysqli->error);
            }

            $stmt1->bind_param(
                "ssssssssssissi",
                $fname,
                $mname,
                $lname,
                $gender,
                $cs,
                $dob,
                $pob,
                $contact,
                $email,
                $street,
                $brgy,
                $city,
                $signaturePath,
                $id
            );

            if (!$stmt1->execute()) {
                throw new Exception($stmt1->error);
            }

            $stmt2 = $this->mysqli->prepare("UPDATE tbl_users SET PP = ? WHERE u_id = ?");

            if (!$stmt2) {
                throw new Exception($this->mysqli->error);
            }

            $stmt2->bind_param("si", $avatarPath, $id);

            if (!$stmt2->execute()) {
                throw new Exception($stmt2->error);
            }

            $this->mysqli->commit();
            return true;
        } catch (Exception $e) {

            $this->mysqli->rollback();

            error_log("Update failed: " . $e->getMessage());

            return false;
        }
    }
}
