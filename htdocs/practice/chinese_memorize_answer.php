<div class="content main_questions"><?php
$GLOBALS["correct"] = array();
$GLOBALS["wrong"] = array();
$GLOBALS["answer_n"] = array();
function splitAnalyse($source, $mainID) {
    $answers = array();
    $temp = "";
    for ($i = 0; $i < mb_strlen($source,"utf8"); $i++) {
        $c = mb_substr($source,$i,1,"utf-8");
        if (in_array($c,QUOTELIST)) {
            if ($temp) array_push($answers,$temp);
            $temp = "";
            continue;
        }
        $temp .= $c;
    }
    if ($temp) array_push($answers,$temp);
    $GLOBALS["answer_n"][$mainID] = sizeof($answers);
    foreach ($answers as $id => $answer) {
        $o = $answer === $_POST[$mainID."-".$id];
        $t = array("$mainID."-".$id" => $answer);
        $GLOBALS["$mainID-$id"] = $answer;
        if($o) {
            array_push($GLOBALS["correct"], $mainID."-".$id);
        } else {
            array_push($GLOBALS["wrong"], $mainID."-".$id);
        }
    }
}
function singleAnalyse($answer, $input, $id) {
    $o = $answer === $input;
    if ($o) {
        array_push($GLOBALS["correct"], $id);
    } else {
        array_push($GLOBALS["wrong"], $id);
    }
}
$solved = array();
foreach ($_POST as $id => $answer) {
    if (mb_strpos($id,"-","0","utf8") !== false) {
        $mainID = mb_substr($id,0,mb_strpos($id,"-",0,"utf8"));
        if (in_array($mainID,$solved)) continue;
        splitAnalyse($source[$mainID],$mainID);
        array_push($solved,$mainID);
        continue;
    }
    singleAnalyse($source[$id],$answer,$id);
}
function outputCorrect($i) {
    echo "<span class='correct'>$i</span>";
}
function outputWrong($i,$input) {
    $display = ($input ? "您的輸入: ".$input : "您沒有輸入任何文字!");
    echo "<span class='wrong'>$i<span class='wrong-tooltip'>$display</span></span>";
}
function outputPlain($i) {
    echo "<span class='plain'>$i</span>";
}
if ($quotes != "true") {
    foreach ($source as $id => $origin) {
        if (in_array($id,$GLOBALS["correct"])) {
            outputCorrect($origin);
        } else if (in_array($id,$GLOBALS["wrong"])) {
            outputWrong($origin,$_POST[$id]);
        } else {
            outputPlain($origin);
        }
        echo "<br>";
    }
} else {
    foreach ($source as $id => $origin) {
        if (!in_array($id,$solved)) {
            outputPlain($origin);
        } else {
            $ansCount = $GLOBALS["answer_n"][$id];
            $index = 0;
            for ($i = 0; $i < $ansCount; $i++) {
                $ansOut = $GLOBALS[strval($id)."-".strval($i)];
                if (in_array($id."-".$i,$GLOBALS["correct"])) {
                    outputCorrect($ansOut);
                } else if(in_array($id."-".$i,$GLOBALS["wrong"])) {
                    outputWrong($ansOut,$_POST["$id-$i"]);
                }
                $index += mb_strlen($ansOut,"utf8");
                    while ($index < mb_strlen($origin,"utf8") &&
                        in_array(mb_substr($origin,$index,1,"utf8"),QUOTELIST)) {
                        outputPlain(mb_substr($origin, $index, 1, "utf8"));
                        $index++;
                    }
            }
        }
        echo "<br>";
    }
}
?><div class="result"><label class="result-title">分數: <?php
            $all = (sizeof($GLOBALS["correct"]) + sizeof($GLOBALS["wrong"]));
            echo intval((sizeof($GLOBALS["correct"]) / $all) * 100);
            ?>%</label><br><button class="submit" onclick="window.open('index.php?type=practice&id=<?php echo $_GET["id"]."&dif=$difficulties&quote=$quotes"?>','_self')">再測一次</div></div>
