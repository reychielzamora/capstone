<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../model/Staff.php';
$model = new Staff();

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid method');
    }

    $id = (int)($_POST['brgy_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    if (empty($title)) {
        throw new Exception('Title required');
    }

    // 🔍 DEBUG: Log $_FILES structure
    error_log('FILES: ' . print_r($_FILES, true));
    
    if (!isset($_FILES['files']) || empty($_FILES['files']['name'][0])) {
        throw new Exception('No files received. $_FILES: ' . json_encode($_FILES));
    }

    $uploadedFiles = $_FILES['files'];
    $fileCount = count($uploadedFiles['name']);
    $savedFiles = [];
    
    $uploadDir = __DIR__ . '/../uploads/activities/';
    error_log('Upload dir: ' . realpath($uploadDir));
    
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            throw new Exception('Cannot create directory: ' . $uploadDir);
        }
    }
    
    if (!is_writable($uploadDir)) {
        throw new Exception('Directory not writable: ' . $uploadDir);
    }

    for ($i = 0; $i < $fileCount; $i++) {
        $errorCode = $uploadedFiles['error'][$i];
        $tmpName = $uploadedFiles['tmp_name'][$i];
        $originalName = $uploadedFiles['name'][$i];
        
        error_log("File $i: name='$originalName', error=$errorCode, tmp=$tmpName");
        
        if ($errorCode !== UPLOAD_ERR_OK) {
            $errorMsg = $this->getUploadError($errorCode);
            error_log("Upload error $i: $errorMsg");
            continue;
        }

        if (empty($tmpName) || !file_exists($tmpName)) {
            error_log("Tmp file missing: $tmpName");
            continue;
        }

        $fileExt = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'pdf', 'docx'];
        
        if (!in_array($fileExt, $allowed)) {
            error_log("Invalid extension: $fileExt (file: $originalName)");
            continue;
        }

        $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
        $newName = time() . '_' . $safeName;
        $destination = $uploadDir . $newName;

        error_log("Attempting move: $tmpName -> $destination");
        
        if (move_uploaded_file($tmpName, $destination)) {
            $savedFiles[] = basename($destination);
            error_log("Saved: $destination");
        } else {
            error_log("Move failed: $destination");
            error_log("Permissions: dir=" . substr(sprintf('%o', fileperms($uploadDir)), -4));
        }
    }

    error_log("Total saved files: " . count($savedFiles));
    
    if (empty($savedFiles)) {
        throw new Exception('No valid files processed. Check error log.');
    }

    $inserted = $model->insertPost($id, $title, $description, $savedFiles);
    
    if ($inserted) {
        $response['success'] = true;
        $response['message'] = 'Post uploaded! Files: ' . count($savedFiles);
    } else {
        throw new Exception('Database insert failed');
    }

} catch (Exception $e) {
    error_log('Post upload error: ' . $e->getMessage());
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;

function getUploadError($code) {
    $errors = [
        UPLOAD_ERR_INI_SIZE => 'File too large (php.ini)',
        UPLOAD_ERR_FORM_SIZE => 'File too large (form)',
        UPLOAD_ERR_PARTIAL => 'Partial upload',
        UPLOAD_ERR_NO_FILE => 'No file',
        UPLOAD_ERR_NO_TMP_DIR => 'No temp dir',
        UPLOAD_ERR_CANT_WRITE => 'Can\'t write',
        UPLOAD_ERR_EXTENSION => 'Extension blocked'
    ];
    return $errors[$code] ?? 'Unknown error';
}
?>