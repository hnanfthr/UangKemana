<?php
// File: actions/auth_google.php
session_start();
require_once '../config/database.php';
require_once '../vendor/autoload.php';

// --- ISI LAGI KODE RAHASIA LU DI SINI ---
$clientID = 'ISI_DENGAN_CLIENT_ID_KAMU';     
$clientSecret = 'ISI_DENGAN_SECRET_KEY_KAMU'; 
$redirectUri = 'http://localhost/uangkemana/actions/auth_google.php';

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    
    if (isset($token['error'])) {
        die('Error fetching token: ' . $token['error']);
    }

    $client->setAccessToken($token['access_token']);

    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    
    $email = $google_account_info->email;
    $name = $google_account_info->name;
    $google_id = $google_account_info->id;
    $picture = $google_account_info->picture; // <-- INI DIA FOTONYA

    // Cek User
    $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE google_id = '$google_id' OR email = '$email'");

    if (mysqli_num_rows($cek_user) > 0) {
        // A. USER LAMA (LOGIN)
        $data = mysqli_fetch_assoc($cek_user);
        
        // Update data (termasuk foto terbaru) biar selalu fresh
        mysqli_query($koneksi, "UPDATE users SET google_id = '$google_id', email = '$email', picture = '$picture' WHERE id = '".$data['id']."'");

        $_SESSION['status'] = "login";
        $_SESSION['user_id'] = $data['id'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['picture'] = $picture; // Simpan foto ke sesi

        header("Location: ../views/dashboard.php");

    } else {
        // B. USER BARU (REGISTER)
        $query = "INSERT INTO users (nama, username, email, google_id, picture) VALUES ('$name', '$email', '$email', '$google_id', '$picture')";
        
        if (mysqli_query($koneksi, $query)) {
            $new_id = mysqli_insert_id($koneksi);
            
            $_SESSION['status'] = "login";
            $_SESSION['user_id'] = $new_id;
            $_SESSION['nama'] = $name;
            $_SESSION['picture'] = $picture; // Simpan foto ke sesi
            
            header("Location: ../views/dashboard.php");
        } else {
            echo "Gagal Register: " . mysqli_error($koneksi);
        }
    }

} else {
    header("Location: " . $client->createAuthUrl());
    exit;
}
?>