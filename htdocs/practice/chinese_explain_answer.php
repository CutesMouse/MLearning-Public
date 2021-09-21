<div class="content main_questions">
    <div class="answer"><?php
        $GLOBALS["correct"] = array();
        $GLOBALS["wrong"] = array();
        for ($i = 0; $i < sizeof($source); $i++) {
            echo "<div class='element_line'>";
            $quest = preg_split("[:]", $source[$i])[0];
            $ans = preg_split("[:]", $source[$i])[1];
            genPlain($quest . ": ");
            analyse($i, $_POST[$i], $ans);
            genPlain("。");
            echo "</div>";
        }
        function analyse($index, $input, $source)
        {
            if ($source === $input) {
                outputCorrect($input);
                array_push($GLOBALS["correct"], $index);
            } else {
                outputWrong($source, $input);
                array_push($GLOBALS["wrong"], $index);
            }
        }

        function outputCorrect($i)
        {
            echo "<span class='correct'>$i</span>";
        }

        function outputWrong($i, $input)
        {
            $display = ($input ? "您的輸入: " . $input : "您沒有輸入任何文字!");
            echo "<span class='wrong'>$i<span class='wrong-tooltip'>$display</span></span>";
        }

        ?>
    </div>
    <div class="result"><label class="result-title">分數: <?php
            $all = (sizeof($GLOBALS["correct"]) + sizeof($GLOBALS["wrong"]));
            echo intval((sizeof($GLOBALS["correct"]) / $all) * 100);
            ?>%</label><br>
        <button class="submit" onclick="window.open('index.php?type=practice&id=<?php echo $_GET["id"] ?>','_self')">
            再測一次
    </div>
</div>
