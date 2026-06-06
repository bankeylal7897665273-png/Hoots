<?php 
include 'config.php'; 
$domain = $_GET['domain'];
$price = $_GET['price'];
$_SESSION['checkout_domain'] = $domain;
$_SESSION['checkout_price'] = $price;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="navbar">
        <a href="javascript:history.back()" style="color: black;"><i class="fa-solid fa-arrow-left"></i></a>
        <div class="logo"><i class="fa-brands fa-hooli"></i> HOSTINGER</div>
        <div></div>
    </div>
    <div class="container">
        <h2 style="margin-bottom: 20px;">Your cart</h2>
        <div class="card">
            <div style="display: flex; align-items: center; gap: 15px; border-bottom: 1px solid #e5e7eb; padding-bottom: 15px; margin-bottom: 15px;">
                <i class="fa-solid fa-globe" style="font-size: 24px; color: #6b7280;"></i>
                <div>
                    <h3 style="font-size: 18px;"><?php echo $domain; ?></h3>
                    <p style="color: gray; font-size: 14px;">Domain Registration</p>
                </div>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: 600; margin-bottom: 20px;">
                <span>Total</span>
                <span>₹<?php echo $price; ?></span>
            </div>
            <p style="font-size: 14px; color: #059669; margin-bottom: 20px;"><i class="fa-solid fa-circle-check"></i> FREE domain privacy protection included</p>
        </div>
        
        <div style="position: fixed; bottom: 0; left: 0; width: 100%; background: white; padding: 20px; box-shadow: 0 -4px 10px rgba(0,0,0,0.05);">
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-weight: bold; font-size: 20px;">
                <span>Total:</span>
                <span>₹<?php echo $price; ?></span>
            </div>
            <a href="login.php" class="btn-primary">Continue</a>
        </div>
    </div>
</body>
</html>
