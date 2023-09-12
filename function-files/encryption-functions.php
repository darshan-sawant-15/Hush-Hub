<?php

function getRandomKey()
{
    // Key and IV (Initialization Vector) for encryption
    $keyLength = 32; // Key length in bytes (256 bits for AES-256)
    $key = bin2hex(random_bytes($keyLength));
    return $key;
}

function getRandomIV()
{
    $ivSize = openssl_cipher_iv_length('AES-256-CBC'); // IV size for AES-256-CBC
    $iv = random_bytes($ivSize);
    return $iv;
}

// Encrypt the data


// Decrypt the data
//

// echo "Original: $data\n";
// echo "Encrypted: $encrypted\n";
// echo "Decrypted: $decrypted\n";
?>