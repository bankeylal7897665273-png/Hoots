<?php
include 'config.php';

// Check if parameters are passed
if (isset($_GET['id']) && isset($_GET['dom'])) {
    $req_id = $_GET['id'];
    $dom = $_GET['dom'];
    
    // 1. Call Hugging Face API to create domain using cURL
    $api_call = "$HF_API/create-domin/?dominnqme=$dom&key=dominb2ka";
    $ch1 = curl_init();
    curl_setopt($ch1, CURLOPT_URL, $api_call);
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
    curl_exec($ch1);
    curl_close($ch1);
    
    // 2. Update Firebase request to Success using cURL (PATCH method)
    $update = ['status' => 'success'];
    $update_json = json_encode($update);
    $firebase_url = "$FIREBASE_URL/requests/$req_id.json?key=$API_KEY";
    
    $ch2 = curl_init();
    curl_setopt($ch2, CURLOPT_URL, $firebase_url);
    curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, 'PATCH');
    curl_setopt($ch2, CURLOPT_POSTFIELDS, $update_json);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch2, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
    curl_exec($ch2);
    curl_close($ch2);
    
    // 3. Wapas Admin Panel par bhej do
    header("Location: admin.php");
    exit();
} else {
    // Agar koi direct kholne ki koshish kare
    header("Location: admin.php");
    exit();
}
?>
