<?php
include 'config.php';
// Agar portal link direct session expire kare toh bounce authentication sequence
if (!isset($_SESSION['user_email'])) {
    $_SESSION['redirect_back'] = "buy.php?domain=" . urlencode($_GET['domain']) . "&price=" . $_GET['price'];
    header("Location: login.php");
    exit();
}

$domain = htmlspecialchars($_GET['domain']);
$price = htmlspecialchars($_GET['price']);
$email = $_SESSION['user_email'];

if (isset($_POST['utr'])) {
    $utr = trim($_POST['utr']);
    
    // Save request to Firebase via secure server-side cURL block
    $request_payload = json_encode([
        'email' => $email,
        'domain' => $domain,
        'price' => $price,
        'utr' => $utr,
        'status' => 'pending'
    ]);

    $ch = curl_init("$FIREBASE_URL/requests.json?key=$API_KEY");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request_payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_exec($ch);
    curl_close($ch);

    // Dynamic clean redirect sequence straight to pay.php as requested
    header("Location: pay.php?domain=" . urlencode($domain));
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout Secure Gateway</title>
    <link rel="stylesheet" href="style.css?v=3">
</head>
<body style="background:#f4f5f7; padding-top: 50px;">

    <div class="card" style="max-width: 480px; margin: 0 auto; background:#fff; padding:30px; border-radius:12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <h2 style="color:#2b1b54; margin-bottom:15px; font-weight:800;">Complete Registration</h2>
        <p style="color:gray; margin-bottom:20px;">Domain Target: <strong style="color:#673de6;"><?php echo $domain; ?></strong></p>
        
        <div style="background:#f3f4f6; padding:15px; border-radius:8px; margin-bottom:25px; text-align:center;">
            <p style="font-size:14px; color:#4b5563;">Scan QR Code or Pay via UPI ID</p>
            <h3 style="margin:10px 0; color:#111827; font-size:18px; font-weight:bold;"><?php echo $UPI_ID; ?></h3>
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=upi://pay?pa=<?php echo $UPI_ID; ?>&am=<?php echo $price; ?>&cu=INR" alt="UPI QR Network">
            <h1 style="color:#10b981; font-weight:900; margin-top:15px;">&#8377;<?php echo $price; ?></h1>
        </div>

        <form method="POST">
            <div style="margin-bottom:20px;">
                <label style="display:block; margin-bottom:8px; font-weight:bold; color:#374151;">Enter 12-Digit Transaction UTR Number</label>
                <input type="text" name="utr" pattern="\d{12}" placeholder="Enter reference UTR number" required style="width:100%; padding:12px; border-radius:6px; border:1px solid #ccc; outline:none; font-size:16px;">
            </div>
            <button type="submit" class="btn-primary" style="width:100%; padding:12px; font-size:16px;">Pay Now</button>
        </form>
    </div>

</body>
</html>
