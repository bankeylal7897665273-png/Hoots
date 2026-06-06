<?php
include 'config.php';

$search_domain = isset($_GET['domain']) ? strtolower(trim($_GET['domain'])) : '';
$price = 0;

if ($search_domain != '') {
    $clean_domain = preg_replace('#^https?://#i', '', $search_domain);
    $slash_pos = strpos($clean_domain, '/');
    if ($slash_pos !== false) {
        $clean_domain = substr($clean_domain, 0, $slash_pos);
    }
    
    $ext = pathinfo($clean_domain, PATHINFO_EXTENSION);
    
    // Exact Registered System Extension Checks (.in, .com, .pw etc.)
    $registered_extensions = ['com', 'in', 'net', 'org', 'co', 'site', 'online', 'tech', 'pw', 'bb'];

    if (in_array($ext, $registered_extensions)) {
        $price = rand(100, 200); // Standard/Real extension pricing match rules
    } else if (strpos($clean_domain, '.aiid') !== false || $ext == 'aiid') {
        $price = rand(150, 300); // Custom pricing tier rule 150-300
    } else {
        $price = rand(200, 300); // Unregistered random extensions pricing match rule
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domain Registry Engine</title>
    <link rel="stylesheet" href="style.css?v=3">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="background: #f4f5f7;">

    <div class="navbar">
        <a href="index.php" class="logo" style="display: flex; align-items: center; text-decoration: none;">
            <i class="fa-brands fa-hooli" style="color:#673de6; font-size:32px;"></i>
            <span style="font-weight:900; font-size:22px; letter-spacing:-1px; margin-left:5px; color:#2b1b54;">HOSTINGER</span>
        </a>
        <a href="dashboard.php" style="color: #673de6; text-decoration: none; font-weight: 600;">Dashboard</a>
    </div>

    <div class="container" style="max-width: 700px; margin: 60px auto; padding: 0 20px;">
        <form action="domin.php" method="GET" style="display:flex; gap:10px; margin-bottom: 30px;">
            <input type="text" name="domain" value="<?php echo htmlspecialchars($search_domain); ?>" placeholder="Type the domain you want" required style="flex:1; padding:15px; border-radius:8px; border:1px solid #ccc; font-size:16px; outline:none;">
            <button type="submit" class="btn-primary" style="padding: 15px 25px;"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>

        <?php if($search_domain != ''): ?>
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 style="color:#111827; font-size:20px;"><?php echo htmlspecialchars($clean_domain); ?></h3>
                    <p style="color:#10b981; font-weight:bold; margin-top:5px; font-size:14px;"><i class="fa-solid fa-circle-check"></i> Exclusive Extension Available</p>
                </div>
                <div style="text-align: right;">
                    <h2 style="color:#673de6; font-weight:900; font-size:28px;">&#8377;<?php echo $price; ?></h2>
                    <p style="font-size:11px; color:gray; margin-bottom:10px;">Lifetime Cloud Provisioning</p>
                    
                    <?php 
                    // Session Auto Recovery State Generator
                    if(isset($_SESSION['user_email'])) {
                        $destination_url = "buy.php?domain=".urlencode($clean_domain)."&price=".$price;
                    } else {
                        $_SESSION['redirect_back'] = "buy.php?domain=".urlencode($clean_domain)."&price=".$price;
                        $destination_url = "login.php";
                    }
                    ?>
                    <a href="<?php echo $destination_url; ?>" class="btn-primary" style="text-decoration:none; padding:10px 20px; font-size:14px;">Buy Now</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
