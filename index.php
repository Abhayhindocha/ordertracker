<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "internship";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

foreach ($_POST as $key => $value) {
    if (strpos($key, 'tracking_id_') !== false) {
        $order_id = substr($key, 12); // Extract Order ID from input name
        $tracking_id = $_POST["tracking_id_" . $order_id];
        $tracking_link = $_POST["tracking_link_" . $order_id];
        $order_status = $_POST["order_status_" . $order_id];
            
        // Update the database with new values
        $sql_update = "UPDATE `order` SET Tracking_Id='$tracking_id', Tracking_link='$tracking_link', Order_Status='$order_status' WHERE Order_Id=$order_id";
        $conn->query($sql_update);
    } elseif (strpos($key, 'delete_') !== false) {
         $order_id = substr($key, 7); // Extract Order ID from input name
            
        // Delete record from the database
         $sql_delete = "DELETE FROM `order` WHERE Order_Id=$order_id";
        $conn->query($sql_delete);
    }
}


// Fetch data from the 'order' table
$sql = "SELECT * FROM `order`";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<form method='post'>";
        echo "<div style='border: 1px solid #000; padding: 10px; margin: 10px; display: inline-block;'>";
        echo "<p><strong>Order Name:</strong> " . $row["Order_Name"] . "</p>";
        echo "<p><strong>Order ID:</strong> " . $row["Order_Id"] . "</p>";
        echo "<p><strong>Order Status:</strong> " . $row["Order_Status"] . "</p>";
        echo "<p><strong>Tracking ID:</strong> <input type='text' value='" . $row["Tracking_Id"] . "' name='tracking_id_" . $row["Order_Id"] . "'></p>";
        echo "<p><strong>Tracking Link:</strong> <input type='text' value='" . $row["Tracking_link"] . "' name='tracking_link_" . $row["Order_Id"] . "'></p>";
        echo "<p><strong>Update Order Status:</strong> 
            <select name='order_status_" . $row["Order_Id"] . "'>
                <option value='pending' " . ($row["Order_Status"] = 'pending') . ">Pending</option>
                <option value='picked' " . ($row["Order_Status"] = 'picked') . ">Picked</option>
                <option value='in_transit' " . ($row["Order_Status"] = 'in_transit') . ">In Transit</option>
                <option value='delivered' " . ($row["Order_Status"] = 'delivered' ) . ">Delivered</option>
            </select>
        </p>";
        echo "<input type='submit' value='Update'>";
        echo "<input type='submit' name='delete_" . $row["Order_Id"] . "' value='Delete'>";
        echo "</div>";
        echo "</form>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>
