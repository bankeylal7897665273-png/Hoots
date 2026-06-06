<?php
include 'config.php';
if (!isset($_SESSION['user_email'])) { header("Location: login.php"); exit(); }
$domain = htmlspecialchars($_GET['domain']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaction Validation Core</title>
    <link rel="stylesheet" href="style.css?v=3">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="background:#f4f5f7; display:flex; justify-content:center; align-items:center; height:100vh;">

    <div class="card" style="max-width:400px; padding:40px 20px; text-align:center; background:#fff; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.05);">
        <i class="fa-solid fa-clock-rotate-left" style="font-size:55px; color:#f59e0b; animation: pulse 2s infinite;"></i>
        <h2 style="color:#2b1b54; margin-top:20px; font-weight:800;">Verifying Payment</h2>
        <p style="color:gray; margin-top:10px; line-height:1.5;">Hum aapka UTR data verify kar rahe hain. <strong>5-10 minutes</strong> me approval milte hi domain active ho jayega.</p>
        
        <div style="border-top:1px solid #e5e7eb; margin-top:25px; padding-top:20px;">
            <p style="font-size:13px; color:gray;">Target Allocation Address:</p>
            <p style="font-weight:bold; color:#673de6; margin-top:3px;"><?php echo $domain; ?></p>
        </div>
        
        <a href="dashboard.php" class="btn-primary" style="display:block; margin-top:25px; text-decoration:none; padding:12px;">Go to Dashboard</a>
    </div>

    <style>
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.7; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</body>
</html>
