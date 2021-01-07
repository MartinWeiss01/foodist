<?php
    require_once('../controllers/AccountController.php');
    $account = new UserAccountHandler($_SESSION);
    require_once('../controllers/ConnectionController.php');
    $conn = new ConnectionHandler();

    if(isset($_POST["cities"]) && !empty($_POST["cities"])) {
        $result_city = $conn->callQuery("SELECT Name FROM cities WHERE ID = ".$_POST['cities']);
        $result_city = $result_city->fetch_assoc();
        $result_city = $result_city["Name"];

        if(isset($_POST["cuisines"]) && !empty($_POST["cuisines"])) $result = $conn->callQuery("SELECT * FROM restaurants as r INNER JOIN restaurants_cuisines as rc ON r.ID = rc.restaurantID LEFT JOIN (SELECT restaurantID, AVG(stars) as RA, COUNT(stars) as CO FROM reviews GROUP BY restaurantID) as re ON r.ID = re.restaurantID WHERE r.City = ".$_POST['cities']." AND rc.cuisineID = ".$_POST['cuisines']);
        else $result = $conn->callQuery("SELECT * FROM restaurants as r LEFT JOIN (SELECT restaurantID, AVG(stars) as RA, COUNT(stars) as CO FROM reviews GROUP BY restaurantID) as re ON r.ID = re.restaurantID WHERE r.City = ".$_POST['cities']);
    } else $nocity = true;
    $conn->closeConnection();
?>


<!DOCTYPE html>
<html lang="cs">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Foodist | <?php echo $result_city; ?></title>
        <meta name="author" content="Martin Weiss (martinWeiss.cz)">
        
        <!-- Styles -->
        <link rel="stylesheet" href="../assets/css/main.css" media="none" onload="if(media!='all')media='all'"><noscript><link rel="stylesheet" href="../assets/css/main.css"></noscript>
        <link rel="stylesheet" href="../assets/css/view.css" media="none" onload="if(media!='all')media='all'"><noscript><link rel="stylesheet" href="../assets/css/view.css"></noscript>

        <!-- Icons & OG -->
        <script>(function(b,c,d,e,f,g){b.hj=b.hj||function(){(b.hj.q=b.hj.q||[]).push(arguments)},b._hjSettings={hjid:2115839,hjsv:6},f=c.getElementsByTagName("head")[0],g=c.createElement("script"),g.async=1,g.src=d+b._hjSettings.hjid+e+b._hjSettings.hjsv,f.appendChild(g)})(window,document,"https://static.hotjar.com/c/hotjar-",".js?sv=");</script>
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

    <body>
        <div class="container">
            <nav>
                <a href="https://foodist.store/"><img src="https://foodist.store/images/brand/logo.svg" style="width: 8em;"></a>
                
                <div class="menuParent">
                    <div class="flex row hcenter account" onclick="menuHandler(this)" data-role="button">
                        <img class="accountImage" src="/images/users/<?php echo $account->UProfilePicture; ?>">
                        <span class="flex row hcenter accountDetails"><?php echo $account->DisplayName; ?> <icon>arrow_drop_down</icon></span>
                    </div>
                    <div id="menubody" class="flex menu">
                        <?php
                            if($account->UAdmin > 0) echo '<a href="/admin"><div class="flex row hcenter menuItem"><icon>admin_panel_settings</icon><span>Administrace</span></div></a>';
                            if($account->authorized) echo '<a href="#" onclick="showToast(`Not Implemented Yet`)"><div class="flex row hcenter menuItem"><icon>settings</icon><span>Nastavení</span></div></a><hr class="menuDivider">';
                        ?>
                        <div class="flex row hcenter justify-content-between menuItem" data-role="button" onclick="changeTheme()"><div class="flex row hcenter"><icon>nights_stay</icon><span>Tmavý režim</span></div><div><icon theme-listener>toggle_on</icon></div></div><hr class="menuDivider">
                        <?php
                            if($account->authorized) echo '<a href="/logout"><div class="flex row hcenter menuItem"><icon>exit_to_app</icon><span>Odhlásit se</span></div></a>';
                            else echo '<a href="/login"><div class="flex row hcenter menuItem"><icon>exit_to_app</icon><span>Přihlásit se</span></div></a><hr class="menuDivider"><a href="/register"><div class="flex row hcenter menuItem"><icon>exit_to_app</icon><span>Registrovat se</span></div></a>';
                        ?>
                    </div>
                </div>
            </nav>

            <main>
                <div class="restaurant-list">
                    <?php
                        if($nocity) echo "Žádné město<br>";
                        if($result->num_rows < 1) echo "Nenalezena žádná restaurace splňující tyto podmínky!";
                        else {
                            while($row = $result->fetch_assoc()) {
                                echo "<a href='../viewDetailed/?rID=".$row['ID']."'>
                                    <div class='restaurant' id='resta".$row['ID']."'>
                                        <div class='restaurant-header'></div>
                                        <div class='restaurant-details'>
                                            <span class='restaurantName'>".$row['Name']."</span>
                                            <span class='restaurantAddress'>".$row['Address']."</span>
                                            <div class='rating' data-rating='".$row["RA"]."' data-rating-count='".$row["CO"]."'>
                                                <img class='rating-star' src='../images/icons/starfull.png'>
                                                <img class='rating-star' src='../images/icons/starfull.png'>
                                                <img class='rating-star' src='../images/icons/starfull.png'>
                                                <img class='rating-star' src='../images/icons/starfull.png'>
                                                <img class='rating-star' src='../images/icons/starfull.png'>
                                                <span class='raco'>(".($row["CO"] ? $row["CO"] : "0").")</span>
                                    </div></div></div></a>";
                            }
                        }
                    ?>
                </div>
            </main>

            <footer>Vytvořil Martin Weiss (martinWeiss.cz) v rámci maturitní práce © Copyright <?php echo date("Y"); ?></footer>
        </div>

        <div class="toastBox">
            <div id="toast" class="toast"></div>
        </div>
    </body>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            for(let a=0;a<document.getElementsByClassName("rating").length;a++){let b=document.getElementsByClassName("rating")[a],c=b.dataset.rating;switch(Math.round(c)){case 1:b.children[0].classList.add("active");break;case 2:b.children[0].classList.add("active"),b.children[1].classList.add("active");break;case 3:b.children[0].classList.add("active"),b.children[1].classList.add("active"),b.children[2].classList.add("active");break;case 4:b.children[0].classList.add("active"),b.children[1].classList.add("active"),b.children[2].classList.add("active"),b.children[3].classList.add("active");break;case 5:b.children[0].classList.add("active"),b.children[1].classList.add("active"),b.children[2].classList.add("active"),b.children[3].classList.add("active"),b.children[4].classList.add("active");}}
        });

        const themeListenerIcon = document.querySelector("icon[theme-listener]"),
            toastElement = document.getElementById("toast");

        window.matchMedia('(prefers-color-scheme: dark)').matches ? changeTheme(1, false) : changeTheme(0, false);
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => event.matches ? changeTheme(1) : changeTheme(0));

        function changeTheme(j = -1, animations = true) {
            let doc = document.documentElement;
            if(animations) doc.classList.add("theme-transition");
            if((j === 0) || (doc.hasAttribute("dark"))) {
                doc.removeAttribute("dark");
                showToast("[!] Switched to Light Theme.");
                themeListenerIcon.innerText = "toggle_off";
            } else {
                doc.setAttribute("dark", "");
                themeListenerIcon.innerText = "toggle_on";
                showToast("[!] Switched to Dark Theme.");
            }
            if(animations) window.setTimeout(() => doc.classList.remove("theme-transition"), 1000);
        }

        function showToast(message) {
            toastElement.innerText = message;
            toastElement.parentElement.setAttribute("active", "");
            window.setTimeout(() => {
                toastElement.innerText = "";
                toastElement.parentElement.removeAttribute("active");
            }, 2800);
        }

        function menuHandler(caller) {
            caller.parentElement.hasAttribute("active") ? caller.parentElement.removeAttribute("active") : caller.parentElement.setAttribute("active", "");
        }

        function hideMenu(caller) {
            document.getElementById("menubody").style.visibility = "visible";
            caller.parentElement.removeAttribute("active");
            window.setTimeout(() => {
                document.getElementById("menubody").style.visibility = "hidden";
            }, 300);
        }
    </script>
</html>