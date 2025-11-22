<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Gallery - Notelen System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 50px;
        }

        .main-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 15px;
            text-shadow: 2px 2px 20px rgba(0, 0, 0, 0.3);
        }

        .subtitle {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 30px;
        }

        .description {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.7);
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 60px;
        }

        .dashboard-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            position: relative;
            cursor: pointer;
        }

        .dashboard-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.2);
        }

        .card-preview {
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
            overflow: hidden;
        }

        .card-preview::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(102, 126, 234, 0.8), rgba(118, 75, 162, 0.8));
            z-index: 1;
        }

        .preview-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
            text-align: center;
            color: white;
        }

        .preview-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        .preview-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .preview-subtitle {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .card-content {
            padding: 25px;
        }

        .card-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 15px;
        }

        .card-description {
            color: #718096;
            line-height: 1.6;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }

        .card-features {
            list-style: none;
            margin-bottom: 25px;
        }

        .card-features li {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
            color: #4a5568;
            font-size: 0.9rem;
        }

        .card-features li i {
            color: #667eea;
            width: 16px;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .try-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .try-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .card-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-new {
            background: rgba(255, 107, 107, 0.1);
            color: #ff6b6b;
        }

        .badge-popular {
            background: rgba(72, 219, 251, 0.1);
            color: #0abde3;
        }

        .badge-featured {
            background: rgba(123, 237, 159, 0.1);
            color: #2ed573;
        }

        .badge-premium {
            background: rgba(255, 159, 243, 0.1);
            color: #f368e0;
        }

        /* Specific styling for each card */
        .command-center-bg {
            background: linear-gradient(135deg, #0c0c0c 0%, #1a1a2e 50%, #16213e 100%);
        }

        .mobile-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .timeline-bg {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }

        .back-to-main {
            position: fixed;
            top: 30px;
            left: 30px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            color: #667eea;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            z-index: 1000;
        }

        .back-to-main:hover {
            background: #667eea;
            color: white;
            transform: scale(1.1);
        }

        @media (max-width: 768px) {
            .main-title {
                font-size: 2.5rem;
            }
            
            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .container {
                padding: 20px 15px;
            }
        }
    </style>
</head>
<body>
    <button class="back-to-main" onclick="goBack()" title="Kembali ke Menu Utama">
        <i class="fas fa-arrow-left"></i>
    </button>

    <div class="container">
        <div class="header">
            <h1 class="main-title">Dashboard Gallery</h1>
            <p class="subtitle">Pilih Tampilan Dashboard Yang Anda Sukai</p>
            <p class="description">
                Kami menyediakan berbagai pilihan tampilan dashboard yang modern dan interaktif. 
                Setiap dashboard dirancang dengan gaya yang berbeda untuk memberikan pengalaman terbaik 
                dalam mengelola sistem notelen Anda.
            </p>
        </div>

        <div class="dashboard-grid">
            <!-- Command Center Dashboard -->
            <div class="dashboard-card" onclick="openDashboard('command_center')">
                <div class="card-preview command-center-bg">
                    <div class="preview-content">
                        <div class="preview-icon">
                            <i class="fas fa-radar-chart"></i>
                        </div>
                        <div class="preview-title">Command Center</div>
                        <div class="preview-subtitle">Futuristic Control Interface</div>
                    </div>
                </div>
                <div class="card-content">
                    <h3 class="card-title">Command Center Dashboard</h3>
                    <p class="card-description">
                        Tampilan futuristik dengan tema dark mode yang memberikan pengalaman seperti pusat komando. 
                        Dilengkapi dengan real-time monitoring dan visual effects yang menawan.
                    </p>
                    <ul class="card-features">
                        <li><i class="fas fa-check"></i> Dark Mode Theme</li>
                        <li><i class="fas fa-check"></i> Real-time Monitoring</li>
                        <li><i class="fas fa-check"></i> Glowing Effects</li>
                        <li><i class="fas fa-check"></i> System Status Indicators</li>
                    </ul>
                    <div class="card-footer">
                        <a href="<?= base_url('notelen/command_center') ?>" class="try-btn">
                            <i class="fas fa-rocket"></i>
                            Coba Sekarang
                        </a>
                        <span class="card-badge badge-featured">Featured</span>
                    </div>
                </div>
            </div>

            <!-- Mobile Dashboard -->
            <div class="dashboard-card" onclick="openDashboard('mobile_dashboard')">
                <div class="card-preview mobile-bg">
                    <div class="preview-content">
                        <div class="preview-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div class="preview-title">Mobile First</div>
                        <div class="preview-subtitle">Responsive Card Interface</div>
                    </div>
                </div>
                <div class="card-content">
                    <h3 class="card-title">Mobile Dashboard</h3>
                    <p class="card-description">
                        Design modern dengan pendekatan mobile-first yang responsive. Menggunakan card-based layout 
                        dengan gradient yang indah dan animasi yang smooth.
                    </p>
                    <ul class="card-features">
                        <li><i class="fas fa-check"></i> Mobile Responsive</li>
                        <li><i class="fas fa-check"></i> Card-based Layout</li>
                        <li><i class="fas fa-check"></i> Smooth Animations</li>
                        <li><i class="fas fa-check"></i> Touch Friendly</li>
                    </ul>
                    <div class="card-footer">
                        <a href="<?= base_url('notelen/mobile_dashboard') ?>" class="try-btn">
                            <i class="fas fa-mobile-alt"></i>
                            Coba Sekarang
                        </a>
                        <span class="card-badge badge-popular">Popular</span>
                    </div>
                </div>
            </div>

            <!-- Timeline Interactive -->
            <div class="dashboard-card" onclick="openDashboard('timeline')">
                <div class="card-preview timeline-bg">
                    <div class="preview-content">
                        <div class="preview-icon">
                            <i class="fas fa-timeline"></i>
                        </div>
                        <div class="preview-title">Timeline View</div>
                        <div class="preview-subtitle">Interactive Activity Timeline</div>
                    </div>
                </div>
                <div class="card-content">
                    <h3 class="card-title">Timeline Interactive</h3>
                    <p class="card-description">
                        Tampilan timeline yang interaktif untuk memantau semua aktivitas sistem secara kronologis. 
                        Sangat cocok untuk tracking progress dan riwayat aktivitas.
                    </p>
                    <ul class="card-features">
                        <li><i class="fas fa-check"></i> Chronological View</li>
                        <li><i class="fas fa-check"></i> Activity Tracking</li>
                        <li><i class="fas fa-check"></i> Filter by Category</li>
                        <li><i class="fas fa-check"></i> Interactive Timeline</li>
                    </ul>
                    <div class="card-footer">
                        <a href="<?= base_url('notelen/timeline') ?>" class="try-btn">
                            <i class="fas fa-history"></i>
                            Coba Sekarang
                        </a>
                        <span class="card-badge badge-new">New</span>
                    </div>
                </div>
            </div>

            <!-- Original Dashboard (if exists) -->
            <div class="dashboard-card" onclick="openDashboard('berkas_masuk_template')">
                <div class="card-preview" style="background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="preview-content">
                        <div class="preview-icon">
                            <i class="fas fa-table"></i>
                        </div>
                        <div class="preview-title">Classic View</div>
                        <div class="preview-subtitle">Traditional Data Table</div>
                    </div>
                </div>
                <div class="card-content">
                    <h3 class="card-title">Berkas Masuk Template</h3>
                    <p class="card-description">
                        Tampilan klasik dengan table-based interface yang familiar dan mudah digunakan. 
                        Cocok untuk pengguna yang menyukai antarmuka tradisional.
                    </p>
                    <ul class="card-features">
                        <li><i class="fas fa-check"></i> Traditional Layout</li>
                        <li><i class="fas fa-check"></i> Data Tables</li>
                        <li><i class="fas fa-check"></i> Familiar Interface</li>
                        <li><i class="fas fa-check"></i> Quick Access</li>
                    </ul>
                    <div class="card-footer">
                        <a href="<?= base_url('notelen/berkas_masuk_template') ?>" class="try-btn">
                            <i class="fas fa-table"></i>
                            Coba Sekarang
                        </a>
                        <span class="card-badge badge-premium">Classic</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openDashboard(type) {
            const urls = {
                'command_center': '<?= base_url("notelen/command_center") ?>',
                'mobile_dashboard': '<?= base_url("notelen/mobile_dashboard") ?>',
                'timeline': '<?= base_url("notelen/timeline") ?>',
                'berkas_masuk_template': '<?= base_url("notelen/berkas_masuk_template") ?>'
            };
            
            if (urls[type]) {
                window.location.href = urls[type];
            }
        }

        function goBack() {
            window.history.back();
        }

        // Add hover effects and interactions
        document.querySelectorAll('.dashboard-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Add loading effect when clicking cards
        document.querySelectorAll('.try-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                this.style.pointerEvents = 'none';
            });
        });
    </script>
</body>
</html>
