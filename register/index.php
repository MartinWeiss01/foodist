<?php
    require_once(dirname(__DIR__).'/controllers/AccountController.php');
    $account = new UserAccountHandler($_SESSION);
    $account->redirectAuthenticated();

    $fail = NULL;
    if(count($_POST) > 0) {
        require_once(dirname(__DIR__).'/controllers/ConnectionController.php');
        $conn = new ConnectionHandler();

        $mail = $_POST["email"];
        ($mail && sizeof($mail) > 0 && preg_match('/^(.+)@(.+)\.(.+)$/', $mail)) ? $mail = $conn->escape($mail) : $fail = "Špatný formát e-mailové adresy";
        $phone = $_POST["telephone"];
        ($phone && sizeof($phone) > 0 && preg_match('/^\+\d{3}\s(\d{3}){3}$/', $phone)) ? $phone = $conn->escape($phone) : $fail = "Špatný formát telefonního čísla";
        $password = $_POST["password"];
        ($password && sizeof($password) > 0) ? $password = $conn->escape($password) : $fail = "Špatně zadané heslo";
        $password_confirm = $_POST["password_confirm"];
        ($password_confirm && sizeof($password_confirm) > 0) ? $password_confirm = $conn->escape($password_confirm) : $fail = "Špatně zadané ověřovací heslo";
        if($password != $password_confirm) $fail = "Hesla se neshodují";

        if($fail == NULL) {
            $conn->prepare("INSERT INTO users (Email, Password, Telephone, Register_IP) VALUE (?, SHA2(?, 256), ?, ?)", "ssss", $mail, $password, $phone, $_SERVER["REMOTE_ADDR"]);
            $conn->execute();
            $conn->finishConnection(header("Location: ../login/?register"));
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
        <title>Foodist | Registrace</title>
        <meta name="author" content="Martin Weiss (martinWeiss.cz)">
        
        <!-- Resources -->
        <script defer src="/assets/js/managerly.min.js"></script>
        <script defer src="/assets/js/register.min.js"></script>
        <link rel="preload" href="/assets/css/main.css" as="style" onload="this.rel='stylesheet'"><noscript><link rel="stylesheet" href="/assets/css/main.css"></noscript>
        <link rel="preload" href="/assets/css/register.css" as="style" onload="this.rel='stylesheet'"><noscript><link rel="stylesheet" href="/assets/css/register.css"></noscript>

        <link rel="preload" href="/assets/fonts/OpenSansRegular.woff2" as="font" type="font/woff2" crossorigin onload="this.rel='font'"><noscript><link rel="font" href="/assets/fonts/OpenSansRegular.woff2"></noscript>
        <link rel="preload" href="/assets/fonts/OpenSansSemiBold.woff2" as="font" type="font/woff2" crossorigin onload="this.rel='font'"><noscript><link rel="font" href="/assets/fonts/OpenSansSemiBold.woff2"></noscript>
        <link rel="preload" href="/assets/fonts/OpenSansBold.woff2" as="font" type="font/woff2" crossorigin onload="this.rel='font'"><noscript><link rel="font" href="/assets/fonts/OpenSansBold.woff2"></noscript>
        <link rel="preload" href="/assets/fonts/MaterialIcons.woff2" as="font" type="font/woff2" crossorigin onload="this.rel='font'"><noscript><link rel="font" href="/assets/fonts/MaterialIcons.woff2"></noscript>

        <!-- Icons & OG -->
        <link rel="apple-touch-icon" sizes="180x180" href="/assets/brand/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/assets/brand/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="194x194" href="/assets/brand/favicon-194x194.png">
        <link rel="icon" type="image/png" sizes="192x192" href="/assets/brand/android-chrome-192x192.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/assets/brand/favicon-16x16.png">
        <link rel="manifest" href="/assets/brand/site.webmanifest">
        <link rel="mask-icon" href="/assets/brand/safari-pinned-tab.svg" color="#d5ac5b">
        <link rel="shortcut icon" href="/assets/brand/favicon.ico">
        <meta name="msapplication-TileColor" content="#ffc40d">
        <meta name="msapplication-TileImage" content="/assets/brand/mstile-144x144.png">
        <meta name="msapplication-config" content="/assets/brand/browserconfig.xml">
        <meta name="theme-color" content="#ffffff">
    </head>

    <body class="flex hcenter vcenter preload">
        <div class="flex root">
            <a class="flex vcenter hcenter" href="/"><svg class="header-logo" viewBox="0 0 325.16 96.54"><path d="M0 1.34h39v13.41H14.75V40.9h19v13.41h-19V95.2H0zM84.07 6.17Q78.31 0 67.45 0T50.82 6.17Q45 12.34 45.05 23.6v49.34q0 11.27 5.77 17.44 4.74 5.06 12.93 5.94c.35-4.29 1.34-17.55.75-19.69-.71-2.56-4.09-4.8-6.51-7.69s-2.45-11.18-2.45-13.77 3-34.4 3-34.4h1.92l-.84 36.41s.24 1.58 1.41 1.58 1.24-1.34 1.24-1.34l1.6-36.65h2v36.28c0 1.79 1.3 1.87 1.53 1.87s1.6-.08 1.6-1.87V20.77h2l1.6 36.65s.07 1.34 1.24 1.34 1.42-1.58 1.42-1.58l-.84-36.41h1.91s3 31.81 3 34.4 0 10.87-2.45 13.77-5.79 5.13-6.51 7.69c-.59 2.14.41 15.4.75 19.69q8.17-.9 12.93-5.94 5.78-6.16 5.77-17.44V23.6q.02-11.26-5.75-17.43zM104.45 90.21q5.77 6.17 16.63 6.17t16.63-6.17q5.76-6.17 5.76-17.43V23.43q0-11.26-5.76-17.43c-3.23-3.45-7.66-5.43-13.27-6l-1.12 32.23s-.13 1.87 1.32 2.44c4.15 1.62 10.13 7.94 9.59 19.3s-5.94 21.63-13 21.63-12.8-9.92-13.34-21.81 7-18.2 9-18.75a2.77 2.77 0 002-2.52L117.84 0q-8.5.81-13.39 6-5.76 6.16-5.76 17.43v49.35q0 11.22 5.76 17.43zM153.4 1.34h22.52q11 0 16.5 5.9t5.49 17.3V72q0 11.38-5.49 17.29t-16.5 5.9H153.4zm22.25 80.45a7.13 7.13 0 005.57-2.14c1.29-1.43 1.94-3.76 1.94-7V23.87q0-4.83-1.94-7a7.14 7.14 0 00-5.57-2.15h-7.51v67zM207.83 1.34h14.75V95.2h-14.75zM236.53 90.44q-5.5-6.09-5.5-17.5v-5.36H245V74q0 9.12 7.65 9.11a7.18 7.18 0 005.7-2.21q1.94-2.2 1.94-7.17a19.85 19.85 0 00-2.68-10.39q-2.69-4.5-9.92-10.8-9.12-8-12.74-14.55a29.56 29.56 0 01-3.62-14.68q0-11.13 5.63-17.23T253.29 0q10.59 0 16 6.1t5.43 17.5v3.89H260.8v-4.83c0-3.22-.63-5.56-1.88-7a6.83 6.83 0 00-5.5-2.21q-7.38 0-7.37 9a17.65 17.65 0 002.75 9.52q2.74 4.43 10 10.73 9.24 8 12.73 14.62A32.37 32.37 0 01275 72.68q0 11.53-5.7 17.7t-16.56 6.16q-10.74 0-16.21-6.1zM295 14.75h-15.43V1.34h45.59v13.41h-15.42V95.2H295z"/></svg></a>

            <form class="flex register" method="post">
                <?php if($fail) echo "<small class='error'>$fail</small>"; ?>
                <input type="email" id="email" name="email" placeholder="Vaše e-mailová adresa" autocomplete="off" required require/>
                <input type="tel" id="telephone" name="telephone" placeholder="Telefonní číslo (ve tvaru +420 XXXXXXXXX)" autocomplete="off" required require/>
                <input type="password" id="password" name="password" placeholder="Heslo" autocomplete="off" required require/>
                <input type="password" id="password_confirm" name="password_confirm" placeholder="Zopakujte heslo" autocomplete="off" required require/>
                <button data-role="button" type="submit" id="submitactor" value="Vytvořit účet">Vytvořit účet</button>
                <small class="loglink"><a href="/login/">Již máte účet? Přihlásit se</a></small>
            </form>
        </div>
        
        <div class="mmb-toast-box"><div id="mmb-toast-content"></div></div>
    </body>
</html>