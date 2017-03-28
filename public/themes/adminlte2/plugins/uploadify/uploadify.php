<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php>
 */

// Define a destination
$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '1';
$cate = isset($_POST['cate']) ? $_POST['cate'] : '';
$targetFolder = 'public/uploads/' . $member_id . '/' . $cate; // Relative to the root

$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
    $random = rand(0, 9);
    $datetime = date("YmdHis");
    $image_code = $member_id . $cate . $datetime . $random;
    $image_code = base64_encode($image_code);

    $tempFile = $_FILES['Filedata']['tmp_name'];
    $targetPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $targetFolder;

    // Validate the file type
    $fileTypes = array('jpg', 'jpeg', 'gif', 'png'); // File extensions
    $fileParts = pathinfo($_FILES['Filedata']['name']);

    $fileName = $image_code . '.' . $fileParts['extension']; // renameing image
    $targetFile = rtrim($targetPath, '/') . '/' . $fileName;

    if (in_array($fileParts['extension'], $fileTypes)) {
        if (move_uploaded_file($tempFile, $targetFile)) {
            echo $fileName;
        } else {
            echo 'Errors uploded.';
        }
    } else {
        echo 'Invalid file type.';
    }
}
