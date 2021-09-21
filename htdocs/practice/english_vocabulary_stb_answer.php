<div class="content">
    <div class="left"><?php
        /*
         * $source = $mysql->query("select content_array from prainfo where id='$id'")
                ->fetch_assoc()["content_array"];
            $vocs = getAllVocObject($source);
            $voc_text = array();
         */
        $GLOBALS["correct"] = array();
        $GLOBALS["wrong"] = array();
        $GLOBALS["qt_ans"] = array();
        $GLOBALS["qt_inp"] = array();
        foreach ($_POST as $key => $value) {
            $qid = mb_split("/", $key)[0];
            $qans = mb_split("/", $key)[1];
            $qinp = $value;
            $GLOBALS["qt_ans"][$qid] = $qans;
            $GLOBALS["qt_inp"][$qid] = $qinp;
        }

        function analyse($index, $sentence, $voc)
        {
            if ($GLOBALS["qt_inp"][$index] === $sentence->ans) {
                array_push($GLOBALS["correct"], $index);
                outputCorrect($sentence->ans, $GLOBALS["qt_inp"][$index], $voc);
            } else {
                array_push($GLOBALS["wrong"], $index);
                outputWrong($sentence->ans, $GLOBALS["qt_inp"][$index], $voc);
            }
        }

        function outputCorrect($i, $input, $voc)
        {
            $display = "原型: {$voc->voc}<br>";
            $display .= "中文: {$voc->chinese}<br>";
            echo "<span class='correct'>$i<span class='correct-tooltip'>$display</span></span>";
        }

        function outputWrong($i, $input, $voc)
        {
            $display = "原型: {$voc->voc}<br>";
            $display .= "中文: {$voc->chinese}<br>";
            $display .= ($input ? "您的輸入: " . $input : "您沒有輸入任何文字!");
            echo "<span class='wrong'>$i<span class='wrong-tooltip'>$display</span></span>";
        }

        for ($index = 1; $index <= 15; $index++) {
            if ($index == 1) {
                echo "<div class=\"title\">文意字彙</div><br>";
                echo "<div class='sen_box'>";
            }
            $v = $GLOBALS["qt_ans"][$index];
            $sentence = toSentenceObject($vocs[$v]->sentence[0], $vocs[$v]->voc);
            $p = $sentence->p();
            echo "<div class='sen_form'><span class='eng_sen'>{$index}. {$p[0]}</span>";
            analyse($index, $sentence, $vocs[$v]);
            echo "<span class='eng_sen'>{$p[1]}</span></div>";
            echo "";
            if (!($index % 5) && $index != 15) echo "";
        }
        echo "</div>";
        ?>
    </div>
    <style>
        .sen_box {
            display: block;
        }
        .sen_box .sen_form:nth-child(2n) {
            background: #ffeeb7;
            margin: 20px 0;
            padding: 10px 3px;
        }
    </style>
    <div class="result"><label class="result-title">分數: <?php
            $all = (sizeof($GLOBALS["correct"]) + sizeof($GLOBALS["wrong"]));
            echo intval((sizeof($GLOBALS["correct"]) / $all) * 100);
            ?>%</label><br>
        <button class="submit"
                onclick="window.open('index.php?type=practice&id=<?php echo $_GET["id"] ?>','_self')">
            再測一次
    </div>
</div>
