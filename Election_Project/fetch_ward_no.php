<?php
include 'db_connect.php'; // Ensure your database connection is included

$query = "SELECT ward_no FROM ward_header_all";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<option value="' . $row['ward_no'] . '">' . $row['ward_no'] . '</option>';
    }
} else {
    echo '<option value="">No wards available</option>';
}
?>
