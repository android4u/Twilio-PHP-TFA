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

            ul { margin-bottom: 20px; margin-left: 50px; text-align: left; }

            a { font-size: small; }
        </style>
    </head>
    <body>
        <div class="center">
            This is just a demo that demonstrates how voice/SMS could be
            integrated to build a simple two-factor authentication system.

            We have three scenarios:
            <ul>
                <li>First, we can create a user with the bottom form. If you
                    give it a valid username, password, and phone number, the
                    user will be created and they will receive an SMS greeting.</li>
                <li>Alternatively, we can attempt to log in. With an incorrect
                    username/password, a simple message appears. With a correct
                    username/password, a welcome message appears.</li>
                <li>Finally, you can click "forgot your password" to get a
                    new password generated and sent to the phone number on file
                    via either SMS or Voice.</li>
            </ul>
            <span id="message">
                <?php
                $message = urldecode($_GET['message']);
                echo preg_replace("/[^A-Za-z0-9 ,']/", "", $message);
                ?>
            </span>
        </div>
        <form id="login-form" action="process.php" method="POST" class="center">
            <input type="hidden" name="action" value="login" />
            <p>Username: <input type="text" name="username" id="username" /></p>
            <p>Password: <input type="password" name="password" id="password" /></p>
            <p>
                <input type="submit" name="submit" id="submit" value="login!" />
                <a href="#" onclick="document.getElementById('reset-form').style.display = ''">forgot your password?</a>
            </p>
        </form>
        <form id="reset-form" action="process.php" method="POST" class="center" style="display: none;">
            <input type="hidden" name="action" value="reset" />
            <p>Username: <input type="text" name="username" id="username" /></p>
            <p>
                Preferred method:<br />
                SMS: <input type="radio" name="method" value="sms" checked="checked" />
                Voice: <input type="radio" name="method" value="voice" />
            </p>
            <p><input type="submit" name="submit" id="submit" value="reset!" /></p>
            <p>&nbsp;</p>
        </form>
        <form id="login-form" action="process.php" method="POST" class="center">
            <input type="hidden" name="action" value="create" />
            <p>Username: <input type="text" name="username" id="username" /></p>
            <p>Password: <input type="password" name="password" id="password" /></p>
            <p>Phone Number: <input type="text" name="phone_number" id="phone_number" /></p>
            <p><input type="submit" name="submit" id="submit" value="create!" /></p>
            <p>&nbsp;</p>
        </form>
    </body>
</html>