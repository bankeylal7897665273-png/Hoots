<?php
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css?v=3">
    <style> 
        body { background: #111827; color: white; } 
        td, th { border-color: #374151; } 
        th { background: #374151; } 
    </style>
</head>
<body>
    <div class="navbar" style="background:#111827; border-bottom:1px solid #374151; color:white;">
        <div class="logo" style="color:white; font-weight: 900; font-size: 20px;">ADMIN SECURE PANEL</div>
    </div>
    
    <div class="container">
        <h2>All User Requests</h2>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px; text-align: left;">
                <tr>
                    <th style="padding: 10px; border: 1px solid #374151;">User Email</th>
                    <th style="padding: 10px; border: 1px solid #374151;">Domain</th>
                    <th style="padding: 10px; border: 1px solid #374151;">Price</th>
                    <th style="padding: 10px; border: 1px solid #374151;">UTR</th>
                    <th style="padding: 10px; border: 1px solid #374151;">Status</th>
                    <th style="padding: 10px; border: 1px solid #374151;">Action</th>
                </tr>
                <?php
                // Fetch requests using cURL
                $ch3 = curl_init();
                curl_setopt($ch3, CURLOPT_URL, "$FIREBASE_URL/requests.json?key=$API_KEY");
                curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch3, CURLOPT_SSL_VERIFYPEER, false);
                $reqs = curl_exec($ch3);
                curl_close($ch3);
                
                $reqs_data = json_decode($reqs, true);
                
                if($reqs_data) {
                    // Reverse the array so newest requests show at the top
                    $reqs_data = array_reverse($reqs_data, true);
                    
                    foreach($reqs_data as $id => $req) {
                        $price_display = isset($req['price']) ? "₹".$req['price'] : "N/A";
                        echo "<tr>";
                        echo "<td style='padding: 10px; border: 1px solid #374151;'>{$req['email']}</td>";
                        echo "<td style='padding: 10px; border: 1px solid #374151;'><strong>{$req['domain']}</strong></td>";
                        echo "<td style='padding: 10px; border: 1px solid #374151;'><strong style='color:#10b981;'>$price_display</strong></td>";
                        echo "<td style='padding: 10px; border: 1px solid #374151;'>{$req['utr']}</td>";
                        
                        $status_color = $req['status'] == 'success' ? '#10b981' : '#f59e0b';
                        echo "<td style='padding: 10px; border: 1px solid #374151; color:$status_color; font-weight:bold; text-transform:uppercase;'>{$req['status']}</td>";
                        
                        echo "<td style='padding: 10px; border: 1px solid #374151;'>";
                        if ($req['status'] == 'pending') {
                            // YAHAN LINK FIX KIYA HAI: ab sidha approve.php par jayega
                            echo "<a href='approve.php?id=$id&dom={$req['domain']}' class='btn-primary' style='background:#10b981; padding:8px 12px; font-size:14px; display:inline-block;'>Approve</a>";
                        } else {
                            echo "<span style='color:#9ca3af;'>Approved</span>";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='padding: 10px; border: 1px solid #374151; text-align:center;'>No requests found</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>
