<?php
if (!isset($_GET["id"])) {
    return;
}
$id = $_GET["id"];
require_once __DIR__ . "/../mysql/db.php";
$attach = $mysql->query("select attach from edutopic where id='{$_GET["viewer"]}'")->fetch_assoc()["attach"];
$attach = json_decode($attach)->{$id};
$correct = 0;
$wrong = 0;
$answer_array = array();
foreach ($attach->questions as $index => $value) {
    $input = isset($_POST[$index+1]) ? $_POST[$index+1] : null;
    $answer = $value->answer;
    $answer_array[$index+1] = $answer;
    if ($input == $answer) {
        $correct++;
    } else $wrong++;
}
$answer_array["point"] = intval($correct*100/($wrong+$correct));
echo json_encode($answer_array);
