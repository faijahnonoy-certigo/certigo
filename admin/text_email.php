<?php
// Include the send_pickup_email.php file
require 'send_pickup_email.php';

// Call the function with test data
$result = sendPickupEmail('faijahnonoy@gmail.com', 'Juan Dela Cruz', 'TRK12345', 'Indigency', '2025-11-15');

// Output the result
echo $result === true ? "Email sent!" : $result;
?>
