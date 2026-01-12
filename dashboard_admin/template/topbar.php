<!-- TOPBAR.PHP -->

<head>
    <style>
        .topbar {
            height: 60px;
            background-color: #0b3c75;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 25px;

            position: fixed;
            /* FIXED */
            top: 0;
            /* tetap di atas */
            left: 0;
            width: 100%;
            /* lebar penuh */
            z-index: 1000;
        }


        /* HAMBURGER */
        .hamburger-btn {
            font-size: 26px;
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            margin-right: 15px;
        }

        .hamburger-btn:focus {
            outline: none;
        }

        /* BUTTON VISIT WEBSITE */
        .btn-website {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: #0b3c75;
            color: white;
            font-weight: 500;
            font-size: 14px;
            border-radius: 10px;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            transform: translateX(-10px);
        }

        .btn-website i {
            font-size: 16px;
            transition: transform 0.3s ease;
        }

        .btn-website:hover {
            background: rgba(255, 255, 255, 1);
            color: #0b3c75;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        /* PROFILE DROPDOWN */
        .profile-dropdown {
            position: relative;
            display: inline-block;
            margin-left: 15px;
        }

        .profile-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 10px;
            border-radius: 12px;
            cursor: pointer;
            transition: background 0.3s ease, opacity 0.3s ease;
            opacity: 0.9;
        }

        .profile-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* PANAH DROPDOWN */
        .profile-btn i {
            transition: transform 0.3s ease;
        }

        .profile-btn.open i {
            transform: rotate(180deg);
        }

        .profile-dropdown .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background-color: white;
            color: #000;
            min-width: 160px;
            border-radius: 8px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.3s ease, transform 0.3s ease;
            z-index: 1000;
        }

        .profile-dropdown .dropdown-menu.show {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        .profile-dropdown .dropdown-menu a {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            text-decoration: none;
            color: #333;
            transition: background 0.2s ease;
        }

        .profile-dropdown .dropdown-menu a:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .profile-dropdown .dropdown-menu a i {
            margin-right: 8px;
        }

        /* Konten halaman */
        .content {
            margin-top: 60px;
            /* supaya tidak tertutup topbar */
            padding: 20px;
            transition: margin-left 0.3s ease;
        }
    </style>
</head>

<body>
    <div class="topbar">

        <!-- HAMBURGER -->
        <button id="hamburgerBtn" class="hamburger-btn">
    <i class="bi bi-list"></i> 
</button>

        <div class="d-flex align-items-center">

            <!-- BUTTON VISIT WEBSITE -->
            <a href="../../web_page/" target="_blank" class="btn-website">
                <i class="bi bi-box-arrow-up-right"></i> Visit Website
            </a>

            <!-- PROFILE -->
            <div class="profile-dropdown">
                <div class="profile-btn" id="profileButton">
                    <span>Admin</span>
                    <img src="../assets/img/admin.jpg" width="35" height="35" class="rounded-circle">
                    <i class="bi bi-chevron-down"></i> <!-- panah -->
                </div>

                <div class="dropdown-menu" id="profileMenu">
                    <a href="../controller/logout_Admin.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // === DROPDOWN PROFILE ===
        const profileButton = document.getElementById('profileButton');
        const profileMenu = document.getElementById('profileMenu');

        profileButton.addEventListener('click', function() {
            profileMenu.classList.toggle('show');
            profileButton.classList.toggle('open'); // panah ikut rotate
        });

        document.addEventListener('click', function(event) {
            if (!profileButton.contains(event.target) && !profileMenu.contains(event.target)) {
                profileMenu.classList.remove('show');
                profileButton.classList.remove('open');
            }
        });

        // === HAMBURGER TOGGLE SIDEBAR ===
        const hamburger = document.getElementById("hamburgerBtn");
        const sidebar = document.getElementById("sidebar");
        const content = document.querySelector(".content");
        const dropdownLinks = document.querySelectorAll(".navlink.dropdown-toggle");

        /* ================= HAMBURGER TOGGLE ================= */
        hamburger.addEventListener("click", function() {
            sidebar.classList.toggle("collapsed");
            content.classList.toggle("collapsed");

            // Jika dicollapse → tutup semua dropdown & reset active
            if (sidebar.classList.contains("collapsed")) {
                document.querySelectorAll(".collapse").forEach(drop => {
                    drop.classList.remove("show");
                });

                dropdownLinks.forEach(btn => {
                    btn.classList.remove("dropdown-active");
                });
            }
        });


        /* ================= DROPDOWN AUTO EXPAND ================= */
        dropdownLinks.forEach(link => {
            link.addEventListener("click", function(e) {
                const targetId = this.getAttribute("href");
                const targetMenu = document.querySelector(targetId);

                // ✅ JIKA SIDEBAR DALAM KEADAAN COLLAPSED
                if (sidebar.classList.contains("collapsed")) {
                    e.preventDefault(); // stop default collapse

                    // 1. EXPAND SIDEBAR
                    sidebar.classList.remove("collapsed");
                    content.classList.remove("collapsed");

                    // 2. TUNGGU ANIMASI SIDEBAR
                    setTimeout(() => {
                        // Tutup semua dropdown dulu
                        document.querySelectorAll(".collapse").forEach(drop => {
                            drop.classList.remove("show");
                        });

                        // Buka dropdown yang ditekan
                        if (targetMenu) {
                            targetMenu.classList.add("show");
                        }

                        // Aktifkan warna orange
                        dropdownLinks.forEach(item => item.classList.remove("dropdown-active"));
                        this.classList.add("dropdown-active");

                    }, 220); // timing pas dengan animasi sidebar
                } else {
                    // ✅ MODE NORMAL (TIDAK COLLAPSED)
                    dropdownLinks.forEach(item => item.classList.remove("dropdown-active"));
                    this.classList.add("dropdown-active");
                }
            });
        });
    </script>
    >
</body>