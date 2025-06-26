<?php
$key = 235987456; // keep this safe


function simple_encrypt($data, $key) {
    return base64_encode(openssl_encrypt($data, "AES-128-ECB", $key, 0));
   
}

function simple_decrypt($data, $key) {
    return openssl_decrypt(base64_decode($data), "AES-128-ECB", $key, 0);
}
?>