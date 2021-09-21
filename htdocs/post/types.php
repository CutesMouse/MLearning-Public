
<?php
require_once __DIR__."/../mysql/db.php";
if (isset($_POST["id"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    if (!$id || !$name) {
        echo "沒有輸入完整資訊!<br>";
        echo "<a href='javascript:history.go(-1)'>回上頁</a>";
        return;
    }
    $checkIfC = $mysql->query("select * from type where id='{$id}'")->fetch_assoc();
    if ($checkIfC) {
        echo "已存在的ID 請更換!";
        echo "<a href='javascript:history.go(-1)'>回上頁</a>";
        return;
    }
    $mysql->query("insert into type (name,id) values ('{$name}','{$id}')");
    echo "新增成功! 現在可以關閉視窗";
    echo "<div id='result' style='display: none'>{$id}</div>";
    return;
}
?>
<link rel="stylesheet" type="text/css" href="types.css">
<script>
    function add() {
        window.document.write("<link rel=\"stylesheet\" type=\"text/css\" href=\"types.css\"><form action='types.php' method='post'>" +
            "<label for='name'>顯示名稱</label><input id='name' name='name'><br><label for='id'>代號(限英文)</label><input id='id' name='id'><br><button>送出</form>")
    }
    function submit() {
        window.document.write('<div id="result" style="display: none">'+document.getElementById("selection").value+'</div>');
        window.document.write("已選擇完成! 可以關閉此視窗!<br>")
        window.document.write("<a href='javascript:window.close()'>點此關閉</a>")
        window.document.close();
    }
</script>
<label>
    <select id="selection">
        <?php
    require_once __DIR__."/../mysql/db.php";
    $res = $mysql->query("select * from type");
    while ($i = $res->fetch_assoc()) {
        echo "<option value='{$i["id"]}'>{$i["id"]}/{$i["name"]}</option>";
    }?>
    </select>
</label><br>
<button onclick="submit()">送出</button><br>
<button onclick="add()">新增新分類</button>
