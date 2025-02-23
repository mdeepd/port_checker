<?php
// port_scanner.php
// Disclaimer: Only scan networks that you own or have permission to scan.
$result = "";
$open_ports = [];

if (isset($_POST['scan'])) {
    $host = trim($_POST['host']);
    $start_port = (int) $_POST['start_port'];
    $end_port = (int) $_POST['end_port'];

    // Validate input: port numbers must be in the range 1-65535 and start must be <= end.
    if ($start_port < 1 || $start_port > 65535 || $end_port < 1 || $end_port > 65535 || $start_port > $end_port) {
        $result = "Please enter a valid port range (1-65535) with start port less than or equal to end port.";
    } else {
        // Limit scanning range to avoid long execution times (adjust as needed).
        $max_ports = 100;
        if (($end_port - $start_port + 1) > $max_ports) {
            $result = "Please scan a smaller range (max $max_ports ports at a time).";
        } else {
            // Loop through each port in the specified range
            for ($port = $start_port; $port <= $end_port; $port++) {
                // Use a short timeout (0.5 seconds) to check the port
                $connection = @fsockopen($host, $port, $errno, $errstr, 0.5);
                if (is_resource($connection)) {
                    $open_ports[] = $port;
                    fclose($connection);
                }
            }
            $result = "Scan complete.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Port Scanner</title>
    <style>
        body {
            background: #f2f2f2;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
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
            background: #337ab7;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #286090;
        }
        .result, .open-ports {
            margin-top: 20px;
            font-size: 16px;
            color: #333;
        }
        .open-ports ul {
            list-style: none;
            padding: 0;
        }
        .open-ports li {
            background: #e9ecef;
            margin: 5px 0;
            padding: 10px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Port Scanner</h1>
    <form method="post" action="">
        <input type="text" name="host" placeholder="Enter IP or Domain" required>
        <input type="number" name="start_port" placeholder="Start Port" required>
        <input type="number" name="end_port" placeholder="End Port" required>
        <input type="submit" name="scan" value="Scan Ports">
    </form>
    <?php if (!empty($result)) : ?>
        <div class="result"><?php echo htmlspecialchars($result); ?></div>
    <?php endif; ?>

    <?php if (isset($open_ports) && count($open_ports) > 0) : ?>
        <div class="open-ports">
            <h2>Open Ports:</h2>
            <ul>
                <?php foreach ($open_ports as $port): ?>
                    <li>Port <?php echo $port; ?> is open</li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php elseif (isset($_POST['scan']) && count($open_ports) === 0) : ?>
        <div class="open-ports">
            <h2>No open ports found in the specified range.</h2>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
