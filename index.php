<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostinger</title>
    <link rel="stylesheet" href="style.css?v=3">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Stats Section CSS */
        .stats-section { padding: 60px 20px; background: #fff; text-align: center; }
        .stats-grid { display: flex; justify-content: center; gap: 40px; flex-wrap: wrap; max-width: 1000px; margin: 0 auto; }
        .stat-box { background: #f3f4f6; padding: 30px; border-radius: 12px; width: 200px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transform: translateY(30px); opacity: 0; transition: all 0.8s ease-out; }
        .stat-box.visible { transform: translateY(0); opacity: 1; }
        .stat-box h2 { color: #673de6; font-size: 36px; margin-bottom: 10px; }
        .stat-box p { color: #4b5563; font-weight: bold; font-size: 16px; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="index.php" class="logo" style="display: flex; align-items: center; text-decoration: none;">
            <i class="fa-brands fa-hooli" style="color:#673de6; font-size:32px;"></i>
            <span style="font-weight:900; font-size:22px; letter-spacing:-1px; margin-left:5px; color:#2b1b54;">HOSTINGER</span>
        </a>
        
        <div style="display:flex; gap:15px; align-items:center;">
            <a href="login.php" style="color: #111827; text-decoration: none; font-weight: 600;">Log in</a>
            <button class="menu-btn" onclick="toggleMenu()">&#9776;</button>
        </div>
    </div>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-close" onclick="toggleMenu()">&times;</div>
        <?php if(isset($_SESSION['user_email'])): ?>
            <p><?php echo $_SESSION['user_email']; ?></p>
            <a href="dashboard.php">🔧 Domain Control</a>
            <a href="dashboard.php">📜 Requests</a>
            <a href="logout.php" style="color: red; margin-top: 20px;">🚪 Log out</a>
        <?php else: ?>
            <a href="login.php">👤 Log in / Register</a>
        <?php endif; ?>
    </div>

    <div class="hero">
        <h1>Your online success starts here</h1>
        <p>From launching a website to growing your business, Hostinger's got you covered.</p>
        <form action="domin.php" method="GET" class="search-box">
            <input type="text" name="domain" placeholder="Type the domain you want" required>
            <button type="submit">&#128269;</button>
        </form>
    </div>

    <div style="max-width: 800px; margin: 40px auto 20px auto; padding: 0 20px; text-align: center;">
        <a href="<?php echo $BANNER_LINK; ?>" target="_blank">
            <img src="https://i.ibb.co/K1wV0wZ/1780658293614.png" alt="Promo Banner" style="width: 100%; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        </a>
    </div>

    <div class="stats-section">
        <div class="stats-grid" id="statsGrid">
            <div class="stat-box">
                <h2 class="counter" data-target="100000">0</h2>
                <p>Users</p>
            </div>
            <div class="stat-box">
                <h2 class="counter" data-target="258368">0</h2>
                <p>Domains</p>
            </div>
            <div class="stat-box">
                <h2 style="color: #059669;">LOW</h2>
                <p>Price</p>
            </div>
        </div>
    </div>

    <script>
        function toggleMenu() { document.getElementById('sidebar').classList.toggle('active'); }

        // Animation for counting numbers slowly when scrolling
        const counters = document.querySelectorAll('.counter');
        const statBoxes = document.querySelectorAll('.stat-box');

        const animateCounters = () => {
            counters.forEach(counter => {
                const updateCount = () => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText.replace(/\D/g, '');
                    const inc = target / 100; // Slow increment
                    
                    if (count < target) {
                        counter.innerText = Math.ceil(count + inc).toLocaleString() + "+";
                        setTimeout(updateCount, 20);
                    } else {
                        counter.innerText = target.toLocaleString() + "+";
                    }
                };
                updateCount();
            });
        };

        // Scroll Observer
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    animateCounters();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        statBoxes.forEach(box => observer.observe(box));
    </script>
</body>
</html>
