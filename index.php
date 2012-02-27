<?php

session_start();

?>
<html>
    <head>
        <title>Two Factor Authentication Demo</title>
        <style>
            .center {
                margin-left: auto;
                margin-right: auto;
                margin-top: 25px;
            }

            #submit { float: right; }

            form { border-style: solid; padding: 10px; width: 300px; }

            input[type="button"], input[type="text"], input[type="password"]
                { float: right; }

            div { text-align: center; width: 500px; }
        </style>
    </head>
    <body>
        <div class="center">
            <p>This is just a demo that demonstrates how voice/SMS could be
                integrated to build a simple two-factor authentication system.</p>

            <p>No matter what username you put into the initial box, the system
                will generate a one-time use password similar to an RSA token.
                Once this password is used, the user's session is set and the
                password is destroyed. In this particular case, we're not
                storing anything long term.</p>

            <span id="message">
                <?php
                $message = urldecode($_GET['message']);
                echo preg_replace("/[^A-Za-z0-9 ,']/", "", $message);
                $action = (isset($_SESSION['password'])) ? 'login' : 'token';
                ?>
            </span>
        </div>
        <form id="reset-form" action="process.php" method="POST" class="center">
            <input type="hidden" name="action" value="<?php echo $action; ?>" />
            <p>Username: <input type="text" name="username" id="username" value="<?php echo $_SESSION['username']; ?>" /></p>

            <?php if (isset($_SESSION['password'])) { ?>
                <p>Password: <input type="password" name="password" id="password" /></p>
            <?php } else { ?>
                <p>Phone Number: <input type="text" name="phone_number" id="phone_number" /></p>
                Preferred method:<br />
                SMS: <input type="radio" name="method" value="sms" checked="checked" />
                Voice: <input type="radio" name="method" value="voice" />
            <?php } ?>

            <p><input type="submit" name="submit" id="submit" value="login!" /></p>
            <p>&nbsp;</p>
        </form>
    </body>
</html>