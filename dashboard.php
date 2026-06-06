<?php
include 'config.php';
if (!isset($_SESSION['user_email'])) { header("Location: login.php"); exit(); }
$email = $_SESSION['user_email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css?v=3">
    <script>
        // API link hide karne ke liye ye function banaya hai
        function copyAndAlert(domain) {
            navigator.clipboard.writeText(domain);
            alert("Domain " + domain + " copy ho gaya hai!\n\nIsko apne 'Site Run Browser' me paste karke open karein.");
        }
    </script>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="https://assets.hostinger.com/images/logo-dark-fdf870d0.svg" alt="Hostinger" style="height: 24px;">
        </div>
        <button class="menu-btn" onclick="toggleMenu()">&#9776;</button>
    </div>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-close" onclick="toggleMenu()">&times;</div>
        <p style="padding-bottom: 10px; color:gray; font-size:14px;"><?php echo $email; ?></p>
        <a href="dashboard.php" style="color:#673de6;">🔧 Domain Control</a>
        <a href="dashboard.php">📜 Requests</a>
        <a href="logout.php" style="color: red; margin-top: auto;">🚪 Log out</a>
    </div>

    <div class="container">
        <h2>Domain Control / Requests</h2>
        <div style="overflow-x: auto;">
            <table>
                <tr><th>Domain</th><th>Status</th><th>Manage</th></tr>
                <?php
                $reqs = file_get_contents("$FIREBASE_URL/requests.json?key=$API_KEY");
                $reqs_data = json_decode($reqs, true);
                
                if($reqs_data) {
                    foreach($reqs_data as $id => $req) {
                        if($req['email'] == $email) {
                            echo "<tr>";
                            echo "<td><strong>{$req['domain']}</strong></td>";
                            $color = $req['status'] == 'success' ? '#059669' : '#d97706';
                            echo "<td style='color:$color; text-transform:uppercase; font-size:12px; font-weight:bold;'>{$req['status']}</td>";
                            
                            if ($req['status'] == 'success') {
                                // HUGGING FACE API REMOVED COMPLETELY
                                echo "<td>
                                    <a href='upload.php?domain={$req['domain']}' class='btn-primary' style='padding:6px; font-size:12px; margin-bottom:5px;'>Upload Files</a>
                                    <button onclick=\"copyAndAlert('{$req['domain']}')\" class='btn-primary' style='padding:6px; font-size:12px; background:#1f2937; border:none; width:100%; cursor:pointer;'>Live Site</button>
                                </td>";
                            } else {
                                echo "<td>Pending</td>";
                            }
                            echo "</tr>";
                        }
                    }
                } else {
                    echo "<tr><td colspan='3'>No requests found</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
    <script>
        function toggleMenu() { document.getElementById('sidebar').classList.toggle('active'); }
    </script>
</body>
</html>
