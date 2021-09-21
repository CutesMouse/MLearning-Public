<?php
$name = "editormd-image-file";
if (!isset($_FILES[$name])) return;
function genRID($length) {
    if ($length == 1) return chr(rand(97,122));
    return genRID($length-1).genRID(1);
}
$file = $_FILES[$name];
$file["name"] = genRID(30);
move_uploaded_file($file["tmp_name"],"uploads/".$file["name"]);
$msg = array();
$msg["success"] = $file["error"] ? 0 : 1;
$msg["message"] = $msg["success"] ? "上傳成功!" : "上傳失敗";
$msg["url"] = "attachments/uploads/".$file["name"];
echo json_encode($msg);