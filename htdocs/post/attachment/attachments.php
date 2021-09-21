<?php
if (!isset($_GET["type"])) return;
$type = $_GET["type"];
switch ($type) {
    case "rid":
        $id = genRID(15);
        echo json_encode(array("id" => $id));
        break;
}

function genRID($length) {
    if ($length == 1) return chr(rand(97,122));
    return genRID($length-1).genRID(1);
}
