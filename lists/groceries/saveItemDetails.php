<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

require('dbConfig.php');

$con = mysqli_connect($localhost, $DBusername, $DBpassword, $database);
if(!$con){
    echo json_encode(array('success' => false, 'message' => 'Failed to connect to the database'));
    exit;
}

$itemID = $_POST['itemID'] ?? '';
$description = $_POST['description'] ?? '';
$removeImage = isset($_POST['removeImage']) && $_POST['removeImage'] === 'true';

if (empty($itemID)) {
    echo json_encode(array('success' => false, 'message' => 'Item ID is required'));
    exit;
}

$image_url = null;

// Handle image upload
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/';
    
    // Create uploads directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'heic', 'heif'];
    
    if (!in_array($fileExtension, $allowedExtensions)) {
        echo json_encode(array('success' => false, 'message' => 'Invalid file type. Only images are allowed.'));
        exit;
    }
    
    // Keep original extension (browsers can handle HEIC now)
    $finalExtension = $fileExtension;
    $needsConversion = ($fileExtension === 'heic' || $fileExtension === 'heif');
    
    // Generate unique filename
    $newFilename = $itemID . '_' . time() . '.' . $finalExtension;
    $uploadPath = $uploadDir . $newFilename;
    
    // Delete old image if exists
    $oldImageQuery = "SELECT image_url FROM Groceries WHERE id = ?";
    $stmt = mysqli_prepare($con, $oldImageQuery);
    mysqli_stmt_bind_param($stmt, 's', $itemID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    
    if ($row && !empty($row['image_url']) && file_exists($row['image_url'])) {
        unlink($row['image_url']);
    }
    mysqli_stmt_close($stmt);
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
        // Try to convert HEIC to JPG if ImageMagick is available
        if ($needsConversion && extension_loaded('imagick')) {
            try {
                $imagick = new Imagick($uploadPath);
                $imagick->setImageFormat('jpg');
                $imagick->setImageCompressionQuality(85);
                
                // Create new filename with .jpg extension
                $jpgPath = preg_replace('/\.(heic|heif)$/i', '.jpg', $uploadPath);
                $imagick->writeImage($jpgPath);
                $imagick->clear();
                $imagick->destroy();
                
                // Delete the original HEIC file and use the JPG
                unlink($uploadPath);
                $uploadPath = $jpgPath;
            } catch (Exception $e) {
                // Keep original HEIC file if conversion fails
            }
        }
        $image_url = $uploadPath;
    } else {
        echo json_encode(array('success' => false, 'message' => 'Failed to upload image'));
        exit;
    }
} elseif ($removeImage) {
    // Delete existing image
    $oldImageQuery = "SELECT image_url FROM Groceries WHERE id = ?";
    $stmt = mysqli_prepare($con, $oldImageQuery);
    mysqli_stmt_bind_param($stmt, 's', $itemID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    
    if ($row && !empty($row['image_url']) && file_exists($row['image_url'])) {
        unlink($row['image_url']);
    }
    mysqli_stmt_close($stmt);
    
    $image_url = '';
}

// Update database
if ($image_url !== null) {
    $sql = "UPDATE Groceries SET description = ?, image_url = ? WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'sss', $description, $image_url, $itemID);
} else {
    $sql = "UPDATE Groceries SET description = ? WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $description, $itemID);
}

if (mysqli_stmt_execute($stmt)) {
    $response = array('success' => true, 'message' => 'Details saved successfully');
    if ($image_url !== null) {
        $response['image_url'] = $image_url;
    }
    echo json_encode($response);
} else {
    echo json_encode(array('success' => false, 'message' => 'Failed to save details: ' . mysqli_error($con)));
}

mysqli_stmt_close($stmt);
mysqli_close($con);
?>

