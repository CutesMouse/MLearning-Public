<div class="main_questions">
    <?php
    require_once "eng_voc_engine.php";
    require_once __DIR__ . "/../mysql/db.php";
    $source = $mysql->query("select content_array from prainfo where id='$id'")
        ->fetch_assoc()["content_array"];
    $vocs = getAllVocObject($source);
    $voc_text = array();
    foreach ($vocs as $voc) {
        array_push($voc_text, $voc->voc);
        array_push($voc_text,toSentenceObject($voc->sentence[0],$voc->voc)->ans);
    }
    if (isset($_GET["answer"])) {
        require_once "english_vocabulary_answer.php";
        return;
    }
    $r = genRandomArray(sizeof($vocs), 5, 5, 5, 5, 5);
    ?>
    <form onkeypress="ignore(event)" method="post" action="index.php?type=practice&id=<?php echo $id?>&answer=true">

        <div class="title">一、配合題</div>

        <?php
        $index = 1;
        for ($part = 1; $part <= 3; $part++) {
            $show = array();
            echo "<div class=\"choose_block\">";
            foreach ($r as $key => $value) {
                if ($value != $part) continue;
                array_push($show, toSentenceObject($vocs[$key]->sentence[0], $vocs[$key]->voc)->ans);
            }
            $show = getRandomVocs(1, $voc_text, $show);
            shuffle($show);
            foreach ($show as $d) echo "<span class='element'>{$d}</span>";
            echo "</div>";
            echo "<br>";
            foreach ($r as $key => $value) {
                if ($value != $part) continue;
                $p = preg_split("[{.*?}]", $vocs[$key]->sentence[0]);
                echo "<span class='quest plain'>{$index}.{$p[0]}<input autocomplete=\"off\" id='$index' type='text' name='{$index}/{$key}' class='question_input'>{$p[1]}</span><br>";
                $index++;
            }
        }
        ?>
        <div class="title">二、文意字彙</div>
        <br>

        <?php
        foreach ($r as $key => $value) {
            if ($value != 4) continue;
            $p = preg_split("[{.*?}]", $vocs[$key]->sentence[0]);
            $s = toSentenceObject($vocs[$key]->sentence[0], $vocs[$key]->voc);
            echo "<span class='quest plain'>{$index}.<input autocomplete=\"off\" id='$index' type='text' name='{$index}/{$key}' class='question_input'> {$p[0]}{$s->hint[0]}_______{$s->hint[1]}{$p[1]}</span><br>";
            $index++;
        }
        ?>
        <div class="title">三、字彙選擇</div>
        <br>
        <?php
        foreach ($r as $key => $value) {
            if ($value != 5) continue;
            $p = preg_split("[{.*?}]", $vocs[$key]->sentence[0]);
            $s = toSentenceObject($vocs[$key]->sentence[0], $vocs[$key]->voc);
            echo "<div class='choose_block'>";
            $ans = $s->ans;
            $pool = getRandomVocs(3, $voc_text, array($ans));
            shuffle($pool);
            foreach ($pool as $d) echo "<span class='element'>{$d}</span>";
            echo "</div>";
            echo "<span class='quest plain'>{$index}.<input autocomplete=\"off\" id='$index' type='text' name='{$index}/{$key}' class='question_input'> {$p[0]}_______{$p[1]}</span><br>";
            $index++;
        }
        ?>
        <input type="submit" class="submit" value="提交">
    </form>
</div>
<script>
    <?php
        for ($id = 2; $id <= 25; $id++) {
            echo "document.getElementById(\"" . ($id - 1) . "\").setAttribute(\"onkeyup\",\"changeTo($id,event)\");";
        }
    ?>
    function ignore(event) {
        if (event.which === 13) {
            event.preventDefault();
            return false;
        }
    }
    function changeTo(id, event) {
        if (event.which != 13) return;
        document.getElementById(id).focus();
    }
</script>


