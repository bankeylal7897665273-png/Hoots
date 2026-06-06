<?php
include 'config.php';

$message = "";
if (isset($_POST['action'])) {
    $email = trim(strtolower($_POST['email']));
    $password = $_POST['password'];
    $safe_email = str_replace(['.', '#', '$', '[', ']'], '_', $email);

    if ($_POST['action'] == 'register') {
        // Firebase Check if User Exists
        $ch = curl_init("$FIREBASE_URL/users/$safe_email.json?key=$API_KEY");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($ch);
        curl_close($ch);
        
        if (json_decode($res, true) !== null) {
            $message = "exist";
        } else {
            // Register New User
            $user_data = json_encode(['email' => $email, 'password' => $password]);
            $ch = curl_init("$FIREBASE_URL/users/$safe_email.json?key=$API_KEY");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $user_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_exec($ch);
            curl_close($ch);
            
            $_SESSION['user_email'] = $email;
            $message = "registered";
        }
    } elseif ($_POST['action'] == 'login') {
        $ch = curl_init("$FIREBASE_URL/users/$safe_email.json?key=$API_KEY");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($ch);
        curl_close($ch);
        $user = json_decode($res, true);

        if ($user && $user['password'] === $password) {
            $_SESSION['user_email'] = $email;
            $message = "logged_in";
        } else {
            $message = "wrong";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostinger Portal - Login/Register</title>
    <link rel="stylesheet" href="style.css?v=3">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="background: #f4f5f7; display: flex; justify-content: center; align-items: center; height: 100vh;">

    <div class="card" style="width: 100%; max-width: 400px; padding: 30px; border-radius: 12px; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <div style="text-align: center; margin-bottom: 25px;">
            <h2 style="color: #2b1b54; font-weight: 900;">HOSTINGER</h2>
            <p style="color: gray;" id="formSubtitle">Access your control dashboard</p>
        </div>

        <form method="POST" id="authForm">
            <input type="hidden" name="action" id="formAction" value="login">
            
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-weight:600;">Email Address</label>
                <input type="email" name="email" required style="width:100%; padding:12px; border-radius:6px; border:1px solid #ccc;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom:5px; font-weight:600;">Password</label>
                <input type="password" name="password" required style="width:100%; padding:12px; border-radius:6px; border:1px solid #ccc;">
            </div>

            <button type="submit" class="btn-primary" id="submitBtn" style="width: 100%; padding: 12px; font-size: 16px;">Log In</button>
        </form>

        <p style="text-align: center; margin-top: 20px; color: #673de6; cursor: pointer; font-weight: bold;" onclick="toggleAuthMode()" id="toggleText">Don't have an account? Create one</p>
    </div>

    <script>
        function toggleAuthMode() {
            const action = document.getElementById('formAction');
            const submitBtn = document.getElementById('submitBtn');
            const toggleText = document.getElementById('toggleText');
            const subtitle = document.getElementById('formSubtitle');

            if(action.value === 'login') {
                action.value = 'register';
                submitBtn.innerText = 'Create VIP Account';
                toggleText.innerText = 'Already have an account? Log In';
                subtitle.innerText = 'Get your custom domain cloud dashboard setup';
            } else {
                action.value = 'login';
                submitBtn.innerText = 'Log In';
                toggleText.innerText = "Don't have an account? Create one";
                subtitle.innerText = 'Access your control dashboard';
            }
        }

        // PHP Server Responses handling through Premium SweetAlert Popups
        const responseStatus = "<?php echo $message; ?>";
        const redirectUrl = "<?php echo isset($_SESSION['redirect_back']) ? $_SESSION['redirect_back'] : 'dashboard.php'; ?>";

        if(responseStatus === "registered") {
            Swal.fire({
                icon: 'success',
                title: 'Account Created Successfully!',
                text: 'Welcome to VIP Hostinger Network.',
                confirmButtonColor: '#673de6'
            }).then(() => { window.location.href = redirectUrl; });
        } else if(responseStatus === "logged_in") {
            window.location.href = redirectUrl;
        } else if(responseStatus === "exist") {
            Swal.fire({ icon: 'error', title: 'Registration Failed', text: 'Bhai ye email pehle se registered hai!', confirmButtonColor: '#ef4444' });
        } else if(responseStatus === "wrong") {
            Swal.fire({ icon: 'error', title: 'Login Error', text: 'Galat Password ya Email dala hai aapne.', confirmButtonColor: '#ef4444' });
        }
    </script>
</body>
</html>
