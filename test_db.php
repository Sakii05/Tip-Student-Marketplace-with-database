<?php
include 'db_config.php';

// Change 'products' to whatever table name you have in Supabase
$result = supabase_query("products?select=*");

if ($result['status'] === 200) {
    echo "<h1>Connection Successful!</h1>";
    echo "<pre>";
    print_r($result['data']);
    echo "</pre>";
} else {
    echo "<h1>Error: " . $result['status'] . "</h1>";
    print_r($result['data']);
}
?>