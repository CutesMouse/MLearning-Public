<?php
if (isset($_GET["viewer"])) {
    $viewid = $_GET["viewer"];
    require_once "viewer.php";
    return;
}
?>
<head>
    <link type="text/css" rel="stylesheet" href="student/index.css">
</head>
<body>
<?php
// $mysql data
/** TABLE: eduTopic
 *      id: dsadasdasda (length = 15) @VARCHAR(20)
 *      content_array: sdadadasda @TEXT
 *      time: 2020/11/23 @TEXT
 *      author: CutesMouse @TEXT
 *      type: education @TEXT => typeID enum
 *      title: The voc @TEXT
 *      comments: -- @TEXT split by "/"
 *
 */
require_once __DIR__ . "/../mysql/db.php";
$cmd = $mysql->query("select type,id,title,author,content,time from edutopic order by time");
$data = array();
$typeTable = array();
while ($i = $cmd->fetch_assoc()) {
    array_push($data, $i);
}
$cmd = $mysql->query("select * from type");
while ($i = $cmd->fetch_assoc()) {
    $typeTable[$i["id"]] = $i["name"];
}
if (!sizeof($data)) {
    ?>
    <div class="empty">
        目前沒有任何項目! 快投稿一篇吧!
        <div class="post-pre post">
            <button>發表</button>
        </div>
    </div> <?php
} else {
    ?>
    <div class="topic">
        <div class="post">
            <button onclick="url('?type=post&id=student')">發表</button>
        </div>
        <table>
            <thead>
            <tr class="head">
                <th class="table-title">標題</th>
                <th class="table-time">發布時間</th>
                <th class="table-type">分類</th>
                <th class="table-author">作者</th>
            </tr>
            </thead>
            <tbody>
            <?php
            // max 29
            function text_solve($text)
            {
                $length = mb_strlen($text, "utf8");
                if ($length <= 29) return $text;
                $ns = mb_substr($text, 0, 29, "utf8");
                return $ns . "...";
            }

            for ($p = sizeof($data) - 1; $p >= 0; $p--) {
                $listItem = $data[$p];
                echo "<tr class='table-tr'>";
                $tname = isset($typeTable[$listItem["type"]]) ? $typeTable[$listItem["type"]] : "不存在的分類";
                $text = text_solve($listItem["title"]);
                echo "<td class='table-element'><a href='?type=student&viewer={$listItem["id"]}'>{$text}</a></td>";
                echo "<td class='table-element'>{$listItem["time"]}</td>";
                echo "<td class='table-element'>{$tname}</td>";
                echo "<td class='table-element'>{$listItem["author"]}</td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div><br>
    <?php
}

?>
<div class="warming">
    <div class="warming-title">注意</div>
    <div class="warming-content">這裡所有文章都必須分類，並確保內容為<span class="warming-content-emphasis">有意義的而非廢文</span>
        <br>請確保內容都是與學習相關，否則將會刪文。
    </div>
</div>
</body>
