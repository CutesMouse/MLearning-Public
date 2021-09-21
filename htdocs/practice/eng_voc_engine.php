<?php
class vocab {
    public $voc;
    public $sentence;
    public $chinese;
    function __construct($s) {
        // $s = Pause(暫停)_The climber paused for breath then kept walking to the top.
        $pos_lp = mb_strpos($s,"（",0,"utf8");
        $pos_rp = mb_strpos($s,"）",0,"utf8");
        $this->voc = mb_strtolower(mb_substr($s,0,$pos_lp,"utf8"),"utf8");
        $this->chinese = mb_substr($s,$pos_lp+1,$pos_rp - $pos_lp -1);
        $this->sentence = array();
        $split = mb_split("[_]",$s);
        foreach ($split as $key => $v) {
            if ($key == 0) continue;
            array_push($this->sentence,$v);
        }
    }
}
class sentence {
    public $voc;
    public $ans;
    public $ans_brief;
    public $hint = array();
    public $sentence;
    function __construct($sentence, $voc) {
        $this->sentence = $sentence;
        $this->voc = $voc;
        $this->ans = preg_replace("/.*?{(.*?)}.*/","$1",$sentence);
        $this->hint[0] = mb_substr($this->ans,0,1,"utf8");
        $ans = $this->ans;
        $ans_len = mb_strlen($ans,"utf8");
        if (endsWith($ans,"ed")) {
            $this->hint[1] = mb_substr($this->ans,-3,null,"utf8");
        } else if (endsWith($ans,"ing")) {
            $this->hint[1] = mb_substr($this->ans,-4,null,"utf8");
        } else if (endsWith($ans,"ies")) {
            $this->hint[1] = mb_substr($this->ans,-4,null,"utf8");
        } else if (endsWith($ans,"es")) {
            $this->hint[1] = mb_substr($this->ans,-3,null,"utf8");
        } else if (endsWith($ans,"s")) {
            $this->hint[1] = mb_substr($this->ans,-2,null,"utf8");
        } else {
            $this->hint[1] = mb_substr($this->ans,-1,null,"utf8");
        }
        $this->ans_brief = mb_substr($this->ans,1,$ans_len - mb_strlen($this->hint[1],"utf8") -1,"utf8");
    }
    function p() {
        return preg_split("[{.*?}]", $this->sentence);
    }
}
function endsWith($str, $target) {
    if (mb_strlen($target,"utf8") > mb_strlen($str,"utf8")) return false;
    return mb_substr($str,-mb_strlen($target,"utf8"),null,"utf8") === $target;
}
function getAllVocObject($source) {
    $vocs = array();
    foreach (mb_split("[/]",$source) as $st) {
        array_push($vocs, new vocab($st));
    }
    return $vocs;
}
function genRandomArray($length, $a, $b, $c, $d, $e) {
    $array = array();
    for ($i = 0; $i < $a; $i++) array_push($array,1);
    for ($i = 0; $i < $b; $i++) array_push($array,2);
    for ($i = 0; $i < $c; $i++) array_push($array,3);
    for ($i = 0; $i < $d; $i++) array_push($array,4);
    for ($i = 0; $i < $e; $i++) array_push($array,5);
    while (sizeof($array) < $length) array_push($array,0);
    shuffle($array);
    return $array;
}
function toSentenceObject($sen, $voc) {
    return new sentence($sen, $voc);
}
function getRandomVocs($amount, $vocs, $except = array()) {
    if ($amount == 0) return $except;
    $a = getRandomVocSingle($vocs,$except);
    array_push($except,$a);
    if ($amount > 1) {
        return getRandomVocs($amount-1,$vocs,$except);
    } else {
        return $except;
    }
}
function getRandomVocSingle($vocs, $except = array()) {
    do {
        $r = $vocs[rand(0,sizeof($vocs)-1)];
    } while (in_array($r,$except));
    return $r;
}