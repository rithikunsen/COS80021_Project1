<!-- 
Name: Rithikun Sen
ID: 103800533 
Email: 103800533@student.swin.edu
Main function: for user to login to CabsOnline
-->

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Login to CabsOnline</title>
</head>

<body>
    <h1>Login to CabsOnline</h1>
    <p>Please enter your username and password to login</p>
    <form method="post" action="login.php">
        <label for="Email">Email:</label>
        <input type="text" name="email" id="email"><br><br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password"><br><br>
        <input type="submit" value="Login" name="login" id="login">
    </form>
    <p>New Member? <a href="register.php">Register</a></p>
</body>
<?php
$host = "feenix-mariadb.swin.edu.au";
$user = "s103800533"; // your user name
$pwd = "251099"; // your password (date of birth ddmmyy unless changed)
$sql_db = "s103800533_db"; // your database

$connect = @mysqli_connect($host, $user, $pwd, $sql_db);

if (!$connect) {
    echo "<p>Database connection failure</p>";
} else {
    //For an existing customer, the email address and password are expected and checked against the customer table.
    if (isset($_POST["login"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];
        if ($email == "" || $password == "") {
            echo "<p>Please fill all the fields</p>";
        } else {
            $query = "SELECT * FROM customers WHERE email_address = '$email' AND password = '$password'";
            $result = mysqli_query($connect, $query);
            if (mysqli_num_rows($result) > 0) {
                header("Location: booking.php?email=$email");
            } else {
                echo "<p>Invalid email address or password</p>";
            }
        }
    }
}

?>

</html>