<?php
require_once "mysql/db.php";
require_once "mysql/acmanager.php";
$acc = getCurrentAcc($mysql);
if (!$acc || $acc["perm"] !== "admin") {
    echo "你沒有權限!";
    return;
}
?>

<style>
    .input {

    }
    .inner {
    }
    .body {
        display: flex;
        left: 50%;
        top: 50%;
        transform: translateX(-50%) translateY(-50%);
        position: absolute;
    }
    textarea {
        width: 1000px;
        height: 400px;
        font-size: 20px;
    }
    input {
        width: 1000px;
        height: 40px;
        font-size: 25px;
    }
</style>
<body><div class="body"><div class="inner">
<form method="post" action="database.php">
    <div class="input">
    <label for="main">語法</label>
    <input autocomplete="off" name="query" type="text" id="main">
    </div>
</form>
<div class="output">
    <label for="output">輸出</label>
<?php
if (!isset($_POST["query"])) {
    echo "
    <textarea id=\"output\"></textarea>
</div></div></div></body>";
    return;
}
$res = $mysql->query($_POST["query"]);
echo "
    <textarea id=\"output\">Result: \n";
var_dump($res);echo "\n";
$id = 0;
while ($i = $res->fetch_assoc()) {
    echo "fetch->assoc ({$id}):\n";
    var_dump($i);
    echo "\n";
    $id++;
}
echo "</textarea>
</div></div></div></body>";
