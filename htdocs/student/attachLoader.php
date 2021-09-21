<?php
function load($id, $attach)
{
    //"type" => "opt","name" => "基礎數學","questions" => "請問 1+1=?","options" => array("10","5","1"),"answer" => "1"
    switch ($attach->type) {
        case "opt":
            optSolver($id, $attach);
            break;
    }
}

function optSolver($id, $attach)
{
    $index = 1;
    $code = "$(\"body\").append(\"<div class='window' id='{$id}_outer_dialog'><span class='head'>{$attach->name}</span><button identify='{$id}' class='leave' onclick='openWindow(this)' title='關閉視窗'>X</button><hr class='title-sep'><div class='content'><form id='{$id}_form'><div id='{$id}_dialog'></div></form></div></div>\");";
    $div = "{$id}_dialog";
    $max = sizeof($attach->questions);
    foreach ($attach->questions as $question) {
        $request = $question->question;
        $options = $question->options;
        $code .= "$(\"#$div\").append(\"<div id='{$id}_dialog_{$index}'>";
        $code .= "<div class='question'>$index/{$max}. $request</div><br>";
        $qindex = 'A';
        foreach ($options as $option) {
            $code .= "<input name='$index' value='{$option}' type='radio' id='{$id}_{$index}_{$option}_button'><label for='%name_%name_%name' class='{$id}_{$index}_answer' present='{$option}'> {$qindex}. $option</label>";
            $code .= "<br>";
            $qindex++;
        }
        if ($index != $max) $code .= "<button type='button' class='{$id}_next attachButton'>下一題</button>";
        else $code .= "<button identify='{$id}' type='button' class='{$id}_finish attachButton'>提交</button>";
        $code .= "</div>\");";
        $code .= "$(\"#{$id}_dialog_{$index}\").hide();";
        $index++;
    }
    $code .= "$(\"#{$div}\").append(\"<div id='{$id}_result'></div>\");";
    $code .= "$(\"#{$id}_dialog_1\").show();";
    $code .= "$(\".last\").hide();";
    $code .= "var {$id}_index = 1;";
    $code .= "$('.{$id}_next').click(function() {
    $('#{$id}_dialog_'+{$id}_index).hide();
    {$id}_index++;
    $('#{$id}_dialog_'+{$id}_index).show();
    });";
    $code .= "$('.{$id}_finish').click(function() {
    $('#{$id}_dialog_{$max}').hide();
    var formData = $(\"#{$id}_form\").serialize();
    $.ajax(\"student/answer_check.php?id={$id}&viewer={$_GET["viewer"]}\",{
        type: 'POST',
        dataType: 'json',
        data: formData,
        success: function(result) {
            $(\"#{$id}_result\").html(\"<div class='score'>得分: \"+result[\"point\"]+\"</div><div class='back'><button type='button' id='{$id}-back-first' class='attachButton'>回首頁</button></div>\");
            for (let i = 1; i <= {$max}; i++) {
                let answer = result[i];
                $('.{$id}_'+(i)+'_answer[present=\''+answer+'\']').css('color','red');
            };
            $(\"#{$id}-back-first\").click(function() {
                $('#{$id}_result').hide();
                $('#{$id}_dialog_'+{$id}_index).hide();
                $(\"#{$id}_dialog_1\").show();
                $('.{$id}_finish').hide();
                {$id}_index = 1;
            });
        }
    });
    });";
    appendOrCreate($code);
}

function appendOrCreate($s)
{
    if (isset($GLOBALS["script"])) {
        $GLOBALS["script"] .= $s;
    } else $GLOBALS["script"] = $s;
}

function printOut()
{
    if ($GLOBALS["script"]) echo $GLOBALS["script"];
}
