<?php
    require_once(dirname(__DIR__).'/controllers/AccountController.php');
    $account = new UserAccountHandler($_SESSION);
    $account->redirectAuthenticated();
    
    $fail = NULL;
    if(count($_POST) > 0) {
        if(isset($_POST["firstName"]) && !empty($_POST["firstName"])) $local_firstName = htmlspecialchars($_POST["firstName"]); else $fail = "Chyba s křestním jménem";
        if(isset($_POST["lastName"]) && !empty($_POST["lastName"])) $local_lastName = htmlspecialchars($_POST["lastName"]); else $fail = "Chyba s příjmením";
        if(isset($_POST["email"]) && !empty($_POST["email"])) $local_email = htmlspecialchars($_POST["email"]); else $fail = "Chyba s emailem";
        if(isset($_POST["telephone"]) && !empty($_POST["telephone"])) $local_telephone = htmlspecialchars($_POST["telephone"]); else $fail = "Chyba s telefonem";
        if(isset($_POST["date"]) && !empty($_POST["date"])) $local_date = htmlspecialchars($_POST["date"]); else $fail = "Chyba s datem";
        if(isset($_POST["password"]) && !empty($_POST["password"])) $local_password = htmlspecialchars($_POST["password"]); else $fail = "Chyba s heslem";
        if(isset($_POST["password_confirm"]) && !empty($_POST["password_confirm"])) $local_password_confirm = htmlspecialchars($_POST["password_confirm"]); else $fail = "Chyba s potvrzovacím heslem";
        if(isset($_POST["sexValue"]) && !empty($_POST["sexValue"])) $local_sexValue = htmlspecialchars($_POST["sexValue"]); else $fail = "Chyba s pohlavím";
        if(!preg_match('/^(.+)@(.+)\.(.+)$/', $local_email)) $fail = "Chyba s emailem";
        if(!preg_match('/^\+\d{3}\s(\d{3}){3}$/', $local_telephone)) $fail = "Chyba s telefonem";
        if($local_password != $local_password_confirm) $fail = "Hesla se neshodují";

        $local_sexValue -= 1;
        
        if($fail == NULL) {
            require_once(dirname(__DIR__).'/controllers/ConnectionController.php');
            $conn = new ConnectionHandler();
            $conn->callQuery("INSERT INTO users(Email, Password, First_name, Last_Name, Sex, Telephone, Birth, Register_IP) VALUE ('$local_email', SHA2('$local_password', 256), '$local_firstName', '$local_lastName', $local_sexValue, '$local_telephone', '$local_date', '".$_SERVER["REMOTE_ADDR"]."')");
            $conn->finishConnection(header("Location: ../login/"));
        }
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
        
        <!-- Styles -->
        <link rel="stylesheet" href="../assets/css/main.css" media="none" onload="if(media!='all')media='all'"><noscript><link rel="stylesheet" href="../assets/css/main.css"></noscript>
        <link rel="stylesheet" href="../assets/css/auth.css" media="none" onload="if(media!='all')media='all'"><noscript><link rel="stylesheet" href="../assets/css/auth.css"></noscript>
        <link rel="stylesheet" href="../assets/css/register.css" media="none" onload="if(media!='all')media='all'"><noscript><link rel="stylesheet" href="../assets/css/register.css"></noscript>

        <!-- Icons & OG -->
        <link rel="apple-touch-icon" sizes="180x180" href="../images/brand/icons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../images/brand/icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="194x194" href="../images/brand/icons/favicon-194x194.png">
        <link rel="icon" type="image/png" sizes="192x192" href="../images/brand/icons/android-chrome-192x192.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../images/brand/icons/favicon-16x16.png">
        <link rel="manifest" href="../images/brand/icons/site.webmanifest">
        <link rel="mask-icon" href="../images/brand/icons/safari-pinned-tab.svg" color="#d5ac5b">
        <link rel="shortcut icon" href="../images/brand/icons/favicon.ico">
        <meta name="msapplication-TileColor" content="#ffc40d">
        <meta name="msapplication-TileImage" content="../images/brand/icons/mstile-144x144.png">
        <meta name="msapplication-config" content="../images/brand/icons/browserconfig.xml">
        <meta name="theme-color" content="#ffffff">
    </head>

    <body class="preload">
        <!--<div class="privacy-policies">🍪</div>-->

        <div class="flex-container center">
            <div class="form">
                
            <img style="padding-bottom: 50px;width:55%;" src="../images/brand/logo.svg">
                <form method="post">
                    <?php
                        if($fail != NULL) echo $fail;
                    ?>
                    <input type="text" id="firstName" name="firstName" placeholder="Křestní jméno" autocomplete="off" required require/>
                    <input type="text" id="lastName" name="lastName" placeholder="Příjmení" autocomplete="off" required require/>
                    <input type="text" id="email" name="email" placeholder="E-mailová adresa" autocomplete="off" required require/>
                    <input type="text" id="telephone" name="telephone" placeholder="Telefonní číslo (ve tvaru +420 XXXXXXXXX)" autocomplete="off" required require/>
                    <input type="date" id="date" name="date" min="1900-01-01" max="2018-12-31" placeholder="Datum narození" autocomplete="off" required require/>
                    <input type="password" id="password" name="password" placeholder="Heslo" autocomplete="off" required require/>
                    <input type="password" id="password_confirm" name="password_confirm" placeholder="Zopakujte heslo" autocomplete="off" required require/>
                    <input type="hidden" id="sexValue" name="sexValue" value="0">
                    <div id="sexSwitchGroup" data-value="1" class="sexSwitchGroup">
                        <span id="genderMale">Muž</span>
                        <icon id="sexSwitch" class="sexSwitch">toggle_off</icon>
                        <span id="genderFemale">Žena</span>
                    </div>
                    <button type="submit" id="submitactor" value="Vytvořit účet">Vytvořit účet</button>
                    <button onclick="window.location.replace('../login/')" value="Již mám účet">Již mám účet</button>
                </form>
            </div>
        </div>
    </body>
    <script>
        window.onload = (function(){document.body.classList.remove("preload");document.body.classList.add("loaded");});

        const firstNameInput = document.getElementById("firstName");
        const lastNameInput = document.getElementById("lastName");
        const mailInput = document.getElementById("email");
        const telephoneInput = document.getElementById("telephone");
        const birthInput = document.getElementById("date");
        const passInput = document.getElementById("password");
        const passConfirmInput = document.getElementById("password_confirm");
        const submitInput = document.getElementById("submitactor");

        function preventRequired(e, event, k) {
            if(e.hasAttribute("require")) {
                event.preventDefault();
                console.log(k);
                e.classList.add("non-valid");
            }
        }

        const nonvalidMessages = [
            "Vyplňte své křestní jméno",
            "Vyplňte své příjmení",
            "E-mailová adresa chybí nebo má špatný tvar",
            "Vyplňte své tel. číslo ve tvaru +420 XXXXXXXXX",
            "Chybí datum narození",
            "Chybí heslo",
            "Obě hesla musí být stejná"
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

        firstNameInput.addEventListener("change", function() {if(this.value != "") acceptInput(this, 1); else acceptInput(this, 0);});
        lastNameInput.addEventListener("change", function() {if(this.value != "") acceptInput(this, 1); else acceptInput(this, 0);});
        passInput.addEventListener("change", function() {if(this.value != "") acceptInput(this, 1); else acceptInput(this, 0);});
        birthInput.addEventListener("change", function() {if(this.value != "") acceptInput(this, 1); else acceptInput(this, 0);});

        passConfirmInput.addEventListener("change", function() {
            if(this.value == passInput.value) acceptInput(this, 1);
            else acceptInput(this, 0);
        });

        mailInput.addEventListener("change", function() {
            let emailreg = /^(.+)@(.+)\.(.+)$/g;
            if(emailreg.test(this.value)) acceptInput(this, 1);
            else if(!this.hasAttribute("require")) acceptInput(this, 0);
        });

        document.getElementById("telephone").addEventListener("change", function() {
            let telereg = /^\+\d{3}\s(\d{3}){3}$/g;
            if(telereg.test(this.value)) acceptInput(this, 1);
            else if(!this.hasAttribute("require")) acceptInput(this, 0);
        });

        document.getElementById("genderFemale").addEventListener("click", function() {
            document.getElementById("sexSwitch").parentElement.dataset.value = 2;
            document.getElementById("sexSwitch").innerHTML = "toggle_on";
            document.getElementById("sexValue").value = document.getElementById("sexSwitch").parentElement.dataset.value;
        });

        document.getElementById("genderMale").addEventListener("click", function() {
            document.getElementById("sexSwitch").parentElement.dataset.value = 1;
            document.getElementById("sexSwitch").innerHTML = "toggle_off";
            document.getElementById("sexValue").value = document.getElementById("sexSwitch").parentElement.dataset.value;
        });

        document.getElementById("sexSwitch").addEventListener("click", function() {
            if(this.parentElement.dataset.value == 1) {
                this.parentElement.dataset.value = 2;
                this.innerHTML = "toggle_on";
            } else {
                this.parentElement.dataset.value = 1;
                this.innerHTML = "toggle_off";
            }
            document.getElementById("sexValue").value = document.getElementById("sexSwitch").parentElement.dataset.value;
        });
    </script>
</html>
