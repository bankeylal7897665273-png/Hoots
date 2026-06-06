<?php
// Session ko hamesha ke liye long-lasting banane ke liye configuration
ini_set('session.cookie_lifetime', 604800); // 7 Days session life
ini_set('session.gc_maxlifetime', 604800);
session_start();

// Firebase and Hugging Face Direct Configs (Backend Safe Location)
$FIREBASE_URL = "https://earning-a9b0c-default-rtdb.firebaseio.com";
$API_KEY = "AIzaSyASlD4FM6lyIEzBAzPlflhlCwDc3Toh6Fo";
$HF_API = "https://userapis-domin.hf.space";

// Custom Configuration Settings
$UPI_ID = "paytm@upi"; // Aapki UPI ID yahan lagao
$BANNER_LINK = "https://hostinger.com"; 
?>
ercontent.com";
$GOOGLE_CLIENT_SECRET = "Yaha_Apna_Secret_Dale";
$REDIRECT_URI = "https://tumhari-website.com/login.php"; 
?>
