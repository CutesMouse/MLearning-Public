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
            if ($index <= 15 || $index >= 21) {
                if ($GLOBALS["qt_inp"][$index] === $sentence->ans) {
                    array_push($GLOBALS["correct"], $index);
                    outputCorrect($sentence->ans, $GLOBALS["qt_inp"][$index], $voc);
                } else {
                    array_push($GLOBALS["wrong"], $index);
                    outputWrong($sentence->ans, $GLOBALS["qt_inp"][$index], $voc);
                }
            } else {
                if ($GLOBALS["qt_inp"][$index] === $sentence->ans || $GLOBALS["qt_inp"][$index] === $sentence->ans_brief) {
                    array_push($GLOBALS["correct"], $index);
                    outputCorrect($sentence->ans, $GLOBALS["qt_inp"][$index], $voc);
                } else {
                    array_push($GLOBALS["wrong"], $index);
                    outputWrong($sentence->ans, $GLOBALS["qt_inp"][$index], $voc);
                }
            }
        }

        function outputCorrect($i, $input, $voc)
        {
            $display = "εε: {$voc->voc}<br>";
            $display .= "δΈ­ζ: {$voc->chinese}<br>";
            echo "<span class='correct'>$i<span class='correct-tooltip'>$display</span></span>";
        }

        function outputWrong($i, $input, $voc)
        {
            $display = "εε: {$voc->voc}<br>";
            $display .= "δΈ­ζ: {$voc->chinese}<br>";
            $display .= ($input ? "ζ¨ηθΌΈε₯: " . $input : "ζ¨ζ²ζθΌΈε₯δ»»δ½ζε­!");
            echo "<span class='wrong'>$i<span class='wrong-tooltip'>$display</span></span>";
        }

        for ($index = 1; $index <= 25; $index++) {
            if ($index == 1) {
                echo "<div class=\"title\">δΈγιει‘</div><br>";
            }
            if ($index == 6 || $index == 11) {
                echo "<br>";
            }
            if ($index == 16) {
                echo "<div class=\"title\">δΊγζζε­ε½</div><br>";
            }
            if ($index == 21) {
                echo "<div class=\"title\">δΈγε­ε½ιΈζ</div><br>";
            }
            $v = $GLOBALS["qt_ans"][$index];
            $sentence = toSentenceObject($vocs[$v]->sentence[0], $vocs[$v]->voc);
            $p = $sentence->p();
            echo "<span class='eng_sen'>{$p[0]}</span>";
            analyse($index, $sentence, $vocs[$v]);
            echo "<span class='eng_sen'>{$p[1]}</span>";
            echo "<br>";
        }
        ?>
    </div>
    <div class="result"><label class="result-title">εζΈ: <?php
            $all = (sizeof($GLOBALS["correct"]) + sizeof($GLOBALS["wrong"]));
            echo intval((sizeof($GLOBALS["correct"]) / $all) * 100);
            ?>%</label><br>
        <button class="submit"
                onclick="window.open('index.php?type=practice&id=<?php echo $_GET["id"] ?>','_self')">
            εζΈ¬δΈζ¬‘
    </div>
</div>
