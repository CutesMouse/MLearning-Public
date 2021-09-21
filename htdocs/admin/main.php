<?php
if (!isset($admin)) return;
if (!$admin) return;
?>
<link href="admin/main.css" rel="stylesheet" type="text/css">
<div class="stats">
    <div class="stat-block">
        <div class="number">
            <div class="word">
                <?php echo $mysql->query("select count(*) from prainfo")->fetch_array()[0] ?>
            </div>
        </div>
        <div class="intro">學習投稿數</div>
    </div>
    <div class="stat-block">
        <div class="number">
            <div class="word">
                <?php echo $mysql->query("select count(*) from topic")->fetch_array()[0] ?>
            </div>
        </div>
        <div class="intro">奇文共賞投稿數</div>
    </div>
    <div class="stat-block">
        <div class="number">
            <div class="word">
                <?php echo $mysql->query("select count(*) from type")->fetch_array()[0] ?>
            </div>
        </div>
        <div class="intro">登錄分類數</div>
    </div>
    <div class="stat-block">
        <div class="number">
            <div class="word">
                <?php echo $mysql->query("select count(*) from accounts")->fetch_array()[0] ?>
            </div>
        </div>
        <div class="intro">已創立帳號</div>
    </div>
</div>