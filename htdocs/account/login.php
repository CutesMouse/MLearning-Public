<link rel="stylesheet" href="account/login.css" type="text/css">
<div id="form" class="form notOK">
    <form method="post" action="account/verify.php<?php
    if (isset($_GET["redirect"])) echo "?redirect={$_GET["redirect"]}";
    ?>">
        <label class="lb" for="username">帳號</label>
        <input class='ip' type="text" id="username" name="username" onkeypress="update()"><br>
        <label class="lb" for="password">密碼</label>
        <input class='ip' type="password" id="password" name="password" onkeypress="update()"><br>
        <input class="submit" type="submit" value="送出">
    </form>
</div>
<script>
    function update() {
        let username = document.getElementById("username").value;
        let psw = document.getElementById("password").value;
        if (username !== "" && psw !== "") {
            document.getElementById("form").classList.remove("notOK");
            document.getElementById("form").classList.add("OK");
            console.log("removed");
        } else {
            document.getElementById("form").classList.add("notOK");
            document.getElementById("form").classList.remove("OK");
        }
    }
</script>
<?php

?>