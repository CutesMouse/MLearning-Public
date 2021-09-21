<?php
function canModifyComment($post, $mysql) {
    require_once "acmanager.php";
    $acc = getCurrentAcc($mysql);
    if (!$acc) return false;
    if (((array) $post)["author"] === $acc["username"] || $acc["perm"] === "admin") return true;
    return false;
}
function canDeleteComment($post, $topic, $mysql) {
    require_once "acmanager.php";
    $acc = getCurrentAcc($mysql);
    if (!$acc) return false;
    if ($acc["perm"] === "admin") return true;
    if ($acc["username"] === $topic["author"]) return true;
    if (((array) ($post))["author"] === $acc["username"]) return true;
    return false;
}
function submitComment($postID, $content, $mysql) {
    $content = preg_replace("[\r\n]","&spl;",$content);
    require_once "acmanager.php";
    $acc = getCurrentAcc($mysql);
    if (!$acc) return false;
    $create = array();
    $create["author"] = $acc["username"];
    $create["content"] = $content;
    $create["time"] = date_format(new DateTime("now",new DateTimeZone("asia/Taipei")),"Y/m/d H:i:s");
    $create["id"] = genRID(20);
    $cmt = array();
    $d = $mysql->query("select comments from edutopic where id='{$postID}'")->fetch_assoc();
    if (!$d) return false;
    if ($d["comments"]) {
        $cmt = json_decode($d["comments"]);
    }
    array_push($cmt,$create);
    $cmt = json_encode($cmt,JSON_UNESCAPED_UNICODE);
    return $mysql->query("update edutopic set comments='{$cmt}' where id='{$postID}'");
}
function modifyComment($commentID,$postID, $content, $mysql) {
    $content = preg_replace("[\r\n]","&spl;",$content);
    require_once "acmanager.php";
    $acc = getCurrentAcc($mysql);
    if (!$acc) return false;
    $create = array();
    $GLOBALS["author"] = $acc["username"];
    $create["content"] = $content;
    $create["time"] = date_format(new DateTime("now",new DateTimeZone("asia/Taipei")),"Y/m/d H:i:s");
    $create["id"] = $commentID;
    $cmt = array();
    $d = $mysql->query("select comments,author from edutopic where id='{$postID}'")->fetch_assoc();
    if (!$d) return false;
    if ($d["comments"]) {
        $cmt = json_decode($d["comments"]);
    }
    $GLOBALS["cmtID"] = $commentID;
    $GLOBALS["perm"] = false;
    $GLOBALS["acc"] = $acc;
    $cmt = array_filter($cmt,function($ele) {
        if ($ele->id === $GLOBALS["cmtID"]) {
            if ($GLOBALS["acc"]["perm"] !== "admin" && $ele->author !== $GLOBALS["acc"]["username"]) {
                return true;
            }
            $GLOBALS["author"] = $ele->author;
            $GLOBALS["perm"] = true;
            return false;
        }
        return true;
    });
    if (!$GLOBALS["perm"]) {
        return false;
    }
    $create["author"] = $GLOBALS["author"];
    array_push($cmt,$create);
    $cmt = array_values($cmt);
    $cmt = json_encode($cmt,JSON_UNESCAPED_UNICODE);
    return $mysql->query("update edutopic set comments='{$cmt}' where id='{$postID}'");
}
function deleteComment($commentID,$postID, $mysql) {
    require_once "acmanager.php";
    $acc = getCurrentAcc($mysql);
    if (!$acc) return false;
    $cmt = array();
    $d = $mysql->query("select comments,author from edutopic where id='{$postID}'")->fetch_assoc();
    if (!$d) return false;
    if ($d["comments"]) {
        $cmt = json_decode($d["comments"]);
    }
    $GLOBALS["acc"] = $acc;
    $GLOBALS["cmtID"] = $commentID;
    $GLOBALS["post"] = $d;
    $cmt = array_filter($cmt,function($ele) {
        if ($ele->id === $GLOBALS["cmtID"]) {
            if ($GLOBALS["acc"]["perm"] !== "admin" && $ele->author !== $GLOBALS["acc"]["username"] && $GLOBALS["post"]["author"] !== $GLOBALS["acc"]["username"]) {
                return true;
            }
            $GLOBALS["perm"] = true;
            return false;
        }
        return true;
    });
    if (!$GLOBALS["perm"]) {
        return false;
    }
    $cmt = array_values($cmt);
    $cmt = json_encode($cmt,JSON_UNESCAPED_UNICODE);
    return $mysql->query("update edutopic set comments='{$cmt}' where id='{$postID}'");
}
function genRID($length) {
    if ($length == 1) return chr(rand(97,122));
    return genRID($length-1).genRID(1);
}
