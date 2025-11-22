<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Dashboard - Notelen System</title>
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
            color: #333;
        }

        .mobile-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            display: flex;
            justify-content: between;
            align-items: center;
            max-width: 480px;
            margin: 0 auto;
        }

        .app-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #2d3748;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .app-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .notification-badge {
            position: relative;
            cursor: pointer;
        }

        .notification-badge::after {
            content: '3';
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ff4757;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .container {
            max-width: 480px;
            margin: 0 auto;
            padding: 20px;
        }

        .quick-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            padding: 20px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 15px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: white;
        }

        .stat-icon.berkas {
            background: linear-gradient(45deg, #ff6b6b, #feca57);
        }

        .stat-icon.pbt {
            background: linear-gradient(45deg, #48dbfb, #0abde3);
        }

        .stat-icon.pending {
            background: linear-gradient(45deg, #ff9ff3, #f368e0);
        }

        .stat-icon.completed {
            background: linear-gradient(45deg, #7bed9f, #70a1ff);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: #2d3748;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #718096;
            font-weight: 500;
        }

        .card-section {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #2d3748;
        }

        .see-all {
            color: #667eea;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
        }

        .progress-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .progress-item:last-child {
            border-bottom: none;
        }

        .progress-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .progress-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
        }

        .progress-details h4 {
            font-size: 0.95rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 5px;
        }

        .progress-details p {
            font-size: 0.8rem;
            color: #718096;
        }

        .progress-status {
            text-align: right;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-badge.new {
            background: rgba(255, 107, 107, 0.1);
            color: #ff6b6b;
        }

        .status-badge.process {
            background: rgba(72, 219, 251, 0.1);
            color: #0abde3;
        }

        .status-badge.completed {
            background: rgba(123, 237, 159, 0.1);
            color: #2ed573;
        }

        .action-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .action-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 20px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        .action-btn i {
            font-size: 1.5rem;
        }

        .recent-activity {
            max-height: 300px;
            overflow-y: auto;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(45deg, #ff6b6b, #feca57);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 3px;
        }

        .activity-time {
            font-size: 0.75rem;
            color: #718096;
        }

        .float-menu {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }

        .float-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }

        .float-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.6);
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(102, 126, 234, 0.3);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(102, 126, 234, 0.5);
        }

        @media (max-width: 480px) {
            .container {
                padding: 15px;
            }
            
            .mobile-header {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <header class="mobile-header">
        <div class="header-content">
            <div class="app-title">
                <div class="app-icon">
                    <i class="fas fa-gavel"></i>
                </div>
                Notelen
            </div>
            <div class="notification-badge">
                <i class="fas fa-bell" style="font-size: 1.2rem; color: #667eea;"></i>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- Quick Stats -->
        <div class="quick-stats">
            <div class="stat-card">
                <div class="stat-icon berkas">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-number" id="total-berkas">0</div>
                <div class="stat-label">Total Berkas</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon pbt">
                    <i class="fas fa-gavel"></i>
                </div>
                <div class="stat-number" id="total-pbt">0</div>
                <div class="stat-label">PBT Cases</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon pending">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number" id="pending-cases">0</div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon completed">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number" id="completed-today">0</div>
                <div class="stat-label">Completed</div>
            </div>
        </div>

        <!-- Progress Tracking -->
        <div class="card-section">
            <div class="section-header">
                <h3 class="section-title">Progress Tracking</h3>
                <a href="#" class="see-all">Lihat Semua</a>
            </div>
            <div class="progress-list">
                <div class="progress-item">
                    <div class="progress-info">
                        <div class="progress-icon" style="background: linear-gradient(45deg, #ff6b6b, #feca57);">
                            <i class="fas fa-file-import"></i>
                        </div>
                        <div class="progress-details">
                            <h4>Berkas Masuk Baru</h4>
                            <p>Menunggu proses selanjutnya</p>
                        </div>
                    </div>
                    <div class="progress-status">
                        <span class="status-badge new">New</span>
                        <div style="font-size: 0.8rem; color: #718096; margin-top: 5px;">3 items</div>
                    </div>
                </div>
                <div class="progress-item">
                    <div class="progress-info">
                        <div class="progress-icon" style="background: linear-gradient(45deg, #48dbfb, #0abde3);">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <div class="progress-details">
                            <h4>Dalam Proses</h4>
                            <p>Sedang diverifikasi</p>
                        </div>
                    </div>
                    <div class="progress-status">
                        <span class="status-badge process">Process</span>
                        <div style="font-size: 0.8rem; color: #718096; margin-top: 5px;">7 items</div>
                    </div>
                </div>
                <div class="progress-item">
                    <div class="progress-info">
                        <div class="progress-icon" style="background: linear-gradient(45deg, #7bed9f, #2ed573);">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <div class="progress-details">
                            <h4>Selesai Hari Ini</h4>
                            <p>Berkas yang sudah diselesaikan</p>
                        </div>
                    </div>
                    <div class="progress-status">
                        <span class="status-badge completed">Done</span>
                        <div style="font-size: 0.8rem; color: #718096; margin-top: 5px;">12 items</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card-section">
            <div class="section-header">
                <h3 class="section-title">Quick Actions</h3>
            </div>
            <div class="action-grid">
                <a href="<?= base_url('notelen/berkas_masuk_template') ?>" class="action-btn">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Berkas</span>
                </a>
                <a href="<?= base_url('notelen/berkas_pbt_template') ?>" class="action-btn">
                    <i class="fas fa-gavel"></i>
                    <span>Proses PBT</span>
                </a>
                <button class="action-btn" onclick="refreshData()">
                    <i class="fas fa-sync-alt"></i>
                    <span>Refresh Data</span>
                </button>
                <button class="action-btn" onclick="generateReport()">
                    <i class="fas fa-chart-bar"></i>
                    <span>Lihat Laporan</span>
                </button>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card-section">
            <div class="section-header">
                <h3 class="section-title">Aktivitas Terbaru</h3>
                <a href="#" class="see-all">Lihat Semua</a>
            </div>
            <div class="recent-activity" id="activity-feed">
                <div class="activity-item">
                    <div class="activity-avatar">JD</div>
                    <div class="activity-content">
                        <div class="activity-title">Berkas baru dari John Doe</div>
                        <div class="activity-time">2 menit yang lalu</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-avatar" style="background: linear-gradient(45deg, #48dbfb, #0abde3);">AS</div>
                    <div class="activity-content">
                        <div class="activity-title">PBT selesai diproses</div>
                        <div class="activity-time">5 menit yang lalu</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-avatar" style="background: linear-gradient(45deg, #7bed9f, #2ed573);">MR</div>
                    <div class="activity-content">
                        <div class="activity-title">Verifikasi berkas selesai</div>
                        <div class="activity-time">10 menit yang lalu</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <div class="float-menu">
        <button class="float-btn" onclick="scrollToTop()">
            <i class="fas fa-chevron-up"></i>
        </button>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Smooth animations
        function animateNumbers() {
            $('.stat-number').each(function() {
                const $this = $(this);
                const final = parseInt($this.text()) || 0;
                $this.text('0');
                
                $({ counter: 0 }).animate({ counter: final }, {
                    duration: 1500,
                    step: function() {
                        $this.text(Math.ceil(this.counter));
                    }
                });
            });
        }

        // Load data from server
        function loadData() {
            $.ajax({
                url: '<?= base_url("notelen/ajax_get_analytics_data") ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const data = response.data;
                        $('#total-berkas').text(data.total_berkas_masuk || 0);
                        $('#total-pbt').text(data.total_pbt || 0);
                        $('#pending-cases').text(data.pending_process || 0);
                        $('#completed-today').text(data.pbt_selesai || 0);
                        
                        // Animate numbers after updating
                        setTimeout(animateNumbers, 100);
                    }
                },
                error: function() {
                    console.log('Failed to load data');
                }
            });
        }

        function refreshData() {
            // Show loading state
            $('.stat-number').addClass('loading');
            loadData();
            
            // Remove loading state after animation
            setTimeout(() => {
                $('.stat-number').removeClass('loading');
            }, 1600);
        }

        function generateReport() {
            // Implement report generation
            alert('Fitur laporan akan segera tersedia!');
        }

        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Auto refresh every 30 seconds
        setInterval(loadData, 30000);
        
        // Initial load
        $(document).ready(function() {
            loadData();
            setTimeout(animateNumbers, 500);
        });

        // Add loading animation CSS
        $('<style>').prop('type', 'text/css').html(`
            .stat-number.loading {
                animation: pulse 1s infinite;
            }
            
            @keyframes pulse {
                0% { opacity: 1; transform: scale(1); }
                50% { opacity: 0.7; transform: scale(1.05); }
                100% { opacity: 1; transform: scale(1); }
            }
        `).appendTo('head');
    </script>
</body>
</html>
