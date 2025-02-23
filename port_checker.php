<?php
// Check if the form is submitted
$result = "";
if(isset($_POST['submit'])){
    // Retrieve and sanitize inputs
    $host = trim($_POST['host']);
    $port = (int) $_POST['port'];
    
    // Basic validation for port number range
    if($port < 1 || $port > 65535){
        $result = "Please enter a valid port number (1-65535).";
    } else {
        // Suppress warnings using @ and try to open a socket connection
        $connection = @fsockopen($host, $port, $errno, $errstr, 5);
        if(is_resource($connection)){
            $result = "Port $port on $host is open.";
            fclose($connection);
        } else {
            $result = "Port $port on $host is closed or unreachable.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Port Checker</title>
    <style>
        body {
            background: #f2f2f2;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            margin-top: 20px;
        }
        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            width: 100%;
            background: #5cb85c;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #4cae4c;
        }
        .result {
            margin-top: 20px;
            text-align: center;
            font-size: 18px;
            color: #333;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Port Checker</h1>
    <form method="post" action="">
        <input type="text" name="host" placeholder="Enter IP or Domain" required>
        <input type="number" name="port" placeholder="Enter Port Number" required>
        <input type="submit" name="submit" value="Check Port">
    </form>
    <?php
    // Display the result if available
    if(!empty($result)){
        echo '<div class="result">' . htmlspecialchars($result) . '</div>';
    }
    ?>
</div>
</body>
</html>
