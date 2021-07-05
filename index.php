<?php
    /*
        This is the webpage for user login.
    */
    declare(strict_types = 1);
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    session_save_path("sessions");
    session_start();
    if(isset($_SESSION["username"])){
        $_SESSION["username"] = null;
    }
    
    $allowRegistration = true;// Set this to false to prevent new accounts from being created
    $successfulLogin = "";// Set this to the filepath of the page your user is sent to upon successful login
    $curYear = date('Y');
    $username = $password = $errorMessage = "";
    $phpScript = sanitizeValue($_SERVER['PHP_SELF']);

    function sanitizeValue($value){
        return htmlspecialchars(stripslashes(trim($value)));
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        try{
            require "inc.db.php";
            $pdo = new PDO(DSN, USER, PWD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            die($e->getMessage());
        }
        $username = sanitizeValue($_POST["username"]);
        $password = sanitizeValue($_POST["password"]);
        if(empty($username)){
            $errorMessage .= "Please enter a username. ";
        }
        if(empty($password)){
            $errorMessage .= "Please enter a password.";
        }
        if($errorMessage == ""){
            if(preg_match("/^\w{4,20}$/", $username) != 1){
                $errorMessage .= "Username must be between 4 and 20 alphanumeric characters. ";
            }
            if(preg_match("/^\w{8,20}$/", $password) != 1){
                $errorMessage .= "Password must be between 8 and 20 alphanumeric characters.";
            }
        }
        if($errorMessage == ""){
            $sql = $pdo->query("SELECT password FROM user WHERE username = '$username';");
            $row = $sql->fetch();
            if(isset($_POST["login"])){
                if($row != false){
                    if(password_verify($password, $row["password"])){
                        $_SESSION["username"] = $username;
                        header("Location: " . $successfulLogin);
                        die;
                    }else{
                        $errorMessage = "Incorrect username or password.";
                    }
                }else{
                    $errorMessage = "Incorrect username or password.";
                }
            }else if(isset($_POST["register"]) && $allowRegistration){
                if($row != false){
                    $errorMessage = "That username is already taken. Choose a different username or log into an existing account.";
                }else{
                    $hash = password_hash($password, PASSWORD_BCRYPT);
                    $sql = "INSERT INTO user (username, password) VALUES ('$username', '$hash');";
                    $pdo->exec($sql);
                    $_SESSION["username"] = $username;
                    header("Location: " . $successfulLogin);
                    die;
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    </head>
    <body class="w3-container">
        <div class="w3-card w3-light-gray">
            <header class="w3-container w3-margin-top w3-green">
                <h1>Login</h1>
            </header>
            <form action="<?php echo $phpScript; ?>" method="POST" class="w3-container" id="loginForm">
                <p>
                    <label class="w3-text-dark-grey">Username</label>
                    <span class="w3-text-red"> *</span>
                    <input required name="username" id="username" placeholder="Username" value="<?php echo $username; ?>" class="w3-input w3-border">
                </p>
                <p>
                    <label class="w3-text-dark-grey">Password</label>
                    <span class="w3-text-red"> *</span>
                    <input required type="password" id="password" name="password" placeholder="Password" value="<?php echo $password; ?>" class="w3-input w3-border">
                </p>
                <p>
                    <button name="login" class="w3-btn w3-round w3-green">Log In</button>
                    <?php
                        if($allowRegistration){
                            echo "<button name=\"register\" class=\"w3-btn w3-round w3-green\">Register</button>";
                        }
                    ?>
                </p>
            </form>
            <h2 id="errorMessage" class="w3-container w3-text-red"><?php echo $errorMessage; ?></h2>
        </div>
        <footer class="w3-center w3-bottom w3-white">Carter T. McCall - <?php echo $curYear; ?></footer>
        <script src="js/login.js"></script>
    </body>
</html>