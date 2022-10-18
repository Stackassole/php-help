<?php
if (empty($_POST["firstName"])) {
    die("Name is required");
}
if (empty($_POST["lastName"])) {
    die("Last name is required");
}
if ( ! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Valid email is required");
}

if (strlen($_POST["password"]) <6) {
    die("Password must be at least 6 characters");
}
/* check for letter*/ 
if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
    die("Password must contain at least one letter");
}
/* check for number*/ 
if ( ! preg_match("/[0-9]/i", $_POST["password"])) {
    die("Password must contain at least one number");
}

/**if ($_POST["password_confirmation"]) {
 * die("Passwords must match");
 * }**/

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

 $mysqli = require __DIR__  . "/database.php";

 $sql= "INSERT INTO user (name, lastname, email, password_hash)
        VALUES (?,?,?,?)";

$stmt = $mysqli->stmt_init();


/**any syntax errors in sql will be caught at this poin, if prepare method returns false, then theres a problem */

if ( ! $stmt->prepare($sql)) {
    die("SQL error: ". $mysqli->error);
}

/**binding values to the placeholder characters */

$stmt->bind_param("ssss",
                  $_POST["firstName"],
                  $_POST["lastName"],
                  $_POST["email"],
                  $password_hash);
//**duplicate email */
if ($stmt->execute()) {
    header("Location: signup-sucess.html");
    exit;

} else { 

    if($mysqli->errno ===1062) {
        die("email already taken");
    } else {
    die($mysqli->error . " " . $mysqli->errno);
    }
}
