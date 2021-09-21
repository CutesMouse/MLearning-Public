<link rel="stylesheet" href="account/register.css" type="text/css">
<div id="form" class="form notOK">
    <form method="post" action="account/verify.php">
        <label class="lb" for="username">帳號</label>
        <input class='ip' type="text" id="username" name="username" onkeypress="update()"><br>
        <label class="lb" for="password">密碼</label>
        <input class='ip' type="password" id="password" name="password" onkeypress="update()"><br>
        <label class="lb" for="password">確認密碼</label>
        <input class='ip' type="password" id="cpassword" name="cpassword" onkeypress="update()"><br>
        <input class="submit" type="submit" value="送出">
    </form>
</div>
<script>
    function update() {
        setTimeout(delay,100);
    }
    function delay() {
        let username = document.getElementById("username").value;
        let psw = document.getElementById("password").value;
        let cpsw = document.getElementById("cpassword").value;
        if (username !== "" && psw !== "" && cpsw === psw && psw.length >= 8) {
            document.getElementById("form").classList.remove("notOK");
            document.getElementById("form").classList.add("OK");
        } else {
            document.getElementById("form").classList.add("notOK");
            document.getElementById("form").classList.remove("OK");
        }
    }
</script>
<?php

?>