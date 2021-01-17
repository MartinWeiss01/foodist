<?php
    require_once(dirname(dirname(__DIR__)).'/controllers/AccountController.php');
    $account = new CompanyAccountHandler($_SESSION);
    $account->redirectAuthenticated();
    
    $rep = false;
    if(count($_POST) > 0) {
        require_once(dirname(dirname(__DIR__)).'/controllers/ConnectionController.php');
        $conn = new ConnectionHandler();
    
        $result = $conn->callQuery("SELECT * FROM restaurant_accounts WHERE Email = '".$_POST["email"]."' AND Password = SHA2('".$_POST["password"]."', 256)");
        $fetched = $account->fetchLogin($result->fetch_assoc());

        if(!$fetched) $rep = true;
        else {
            $conn->callQuery("UPDATE restaurant_accounts SET Login_Date = now(), Login_IP = '".$_SERVER["REMOTE_ADDR"]."' WHERE ID = $account->CID");
            $conn->finishConnection(header("Location: ".SSL_COMPANY_PATH));
        }
        $conn->closeConnection();
    }
?>

<!DOCTYPE html>
<html lang="cs">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Foodist | Restaurant Manager / Login</title>
        <meta name="author" content="Martin Weiss (martinWeiss.cz)">
        
        <!-- Styles -->
        <link rel="stylesheet" href="../../assets/css/main.css" media="none" onload="if(media!='all')media='all'"><noscript><link rel="stylesheet" href="../../assets/css/main.css"></noscript>
        <link rel="stylesheet" href="../../assets/css/auth.css" media="none" onload="if(media!='all')media='all'"><noscript><link rel="stylesheet" href="../../assets/css/auth.css"></noscript>
        <link rel="stylesheet" href="../../assets/css/login.css" media="none" onload="if(media!='all')media='all'"><noscript><link rel="stylesheet" href="../../assets/css/login.css"></noscript>

        <!-- Icons & OG -->
        <link rel="apple-touch-icon" sizes="180x180" href="../../images/brand/icons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../../images/brand/icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="194x194" href="../../images/brand/icons/favicon-194x194.png">
        <link rel="icon" type="image/png" sizes="192x192" href="../../images/brand/icons/android-chrome-192x192.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../../images/brand/icons/favicon-16x16.png">
        <link rel="manifest" href="../../images/brand/icons/site.webmanifest">
        <link rel="mask-icon" href="../../images/brand/icons/safari-pinned-tab.svg" color="#d5ac5b">
        <link rel="shortcut icon" href="../../images/brand/icons/favicon.ico">
        <meta name="msapplication-TileColor" content="#ffc40d">
        <meta name="msapplication-TileImage" content="../../images/brand/icons/mstile-144x144.png">
        <meta name="msapplication-config" content="../../images/brand/icons/browserconfig.xml">
        <meta name="theme-color" content="#ffffff">
    </head>

    <body class="preload">
        <div class="flex-container center">
            <div class="form">
                <img style="padding-bottom: 15px;width:55%;" src="../../images/brand/logo.svg">
                <form method="post">
                    <?php if($rep) echo "<span class='error'>Špatný přihlašovací e-mail či heslo.</span>"; ?>
                    <input type="text" id="email" name="email" placeholder="Vaše e-mailová adresa" autocomplete="off" required require/>
                    <input type="password" id="password" name="password" placeholder="Heslo" autocomplete="off" required require/>
                    <button type="submit" id="submitactor" value="Přihlásit se">Přihlásit se</button>
                </form>
            </div>
        </div>
    </body>
    <script>
        window.onload = (function(){document.body.classList.remove("preload");document.body.classList.add("loaded");});
        
        const mailInput = document.getElementById("email");
        const passInput = document.getElementById("password");
        const submitInput = document.getElementById("submitactor");

        function preventRequired(e, event, k) {
            if(e.hasAttribute("require")) {
                event.preventDefault();
                console.log(k);
                e.classList.add("non-valid");
            }
        }

        const nonvalidMessages = [
            "E-mailová adresa chybí nebo má špatný tvar",
            "Chybí heslo"
        ];

        submitInput.addEventListener("click", function(event) {
            let j = document.getElementsByTagName("input");
            for(let i = 0; i < j.length; i++) {
                preventRequired(j[i], event, nonvalidMessages[i]);
            }
        });

        function acceptInput(e, i) {
            if(i == 1) {e.removeAttribute("require");e.classList.remove("non-valid");e.classList.add("valid");}
            else {e.setAttribute("require", "");e.classList.remove("valid");e.classList.add("non-valid");}
        }

        passInput.addEventListener("change", function() {if(this.value != "") acceptInput(this, 1); else acceptInput(this, 0);});
        mailInput.addEventListener("change", function() {
            let emailreg = /^(.+)@(.+)\.(.+)$/g;
            if(emailreg.test(this.value)) acceptInput(this, 1);
            else if(!this.hasAttribute("require")) acceptInput(this, 0);
        });
    </script>
</html>
