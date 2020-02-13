<?php
// From URL to get webpage contents. 
// $url = "https://smile.com.ng/"; 
$url = "https://smile.com.ng/wp-admin/admin-ajax.php";
$email = "bolu.akinsefunmi@gmail.com";

// Initialize a CURL session. 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36");
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt($ch,CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_REFERER, $url);
// curl_setopt($ch, CURLOPT_REFERER, "https://smile.com.ng/data-bundles-2/");
curl_setopt(
    $ch,
    CURLOPT_POSTFIELDS,
    http_build_query(
        array(
            "email" => $email
        )
    )
);
curl_setopt($ch, CURLOPT_POST, 1);

$result = curl_exec($ch);

echo $result;
curl_close($ch);
