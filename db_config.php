<?php
$supabaseUrl = getenv('SUPABASE_URL') ?: "https://swtjhlaueokjnhnehbyg.supabase.co";
$supabaseKey = getenv('SUPABASE_KEY') ?: "sb_publishable_zVBQwq50LjNDySMzaf3NKg_M5dsC...";

$headers = [
    "apikey: " . $supabaseKey,
    "Authorization: Bearer " . $supabaseKey,
    "Content-Type: application/json"
];
?>