<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timeline Interactive - Notelen System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            color: #333;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
            z-index: 1;
        }

        .header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 20px 0;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .logo-text {
            font-size: 1.8rem;
            font-weight: 800;
            color: #2d3748;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .btn {
            padding: 12px 20px;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-outline {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-outline:hover {
            background: #667eea;
            color: white;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            position: relative;
            z-index: 2;
        }

        .timeline-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .timeline-title {
            font-size: 3rem;
            font-weight: 800;
            color: white;
            margin-bottom: 15px;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
        }

        .timeline-subtitle {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 30px;
        }

        .filter-tabs {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .filter-tab {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-tab.active,
        .filter-tab:hover {
            background: rgba(255, 255, 255, 0.9);
            color: #2d3748;
            transform: translateY(-2px);
        }

        .timeline {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
        }

        .timeline::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, #667eea, #764ba2);
            border-radius: 2px;
            transform: translateX(-50%);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 50px;
            opacity: 0;
            transform: translateY(50px);
            animation: fadeInUp 0.6s ease forwards;
        }

        .timeline-item:nth-child(even) {
            animation-delay: 0.2s;
        }

        .timeline-item:nth-child(odd) {
            animation-delay: 0.1s;
        }

        .timeline-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 30px;
            border-radius: 20px;
            position: relative;
            width: 45%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .timeline-content:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
        }

        .timeline-item:nth-child(odd) .timeline-content {
            margin-left: auto;
            margin-right: 55%;
        }

        .timeline-item:nth-child(even) .timeline-content {
            margin-left: 55%;
        }

        .timeline-content::before {
            content: '';
            position: absolute;
            top: 30px;
            width: 0;
            height: 0;
            border: 15px solid transparent;
        }

        .timeline-item:nth-child(odd) .timeline-content::before {
            right: -30px;
            border-left-color: rgba(255, 255, 255, 0.95);
        }

        .timeline-item:nth-child(even) .timeline-content::before {
            left: -30px;
            border-right-color: rgba(255, 255, 255, 0.95);
        }

        .timeline-icon {
            position: absolute;
            left: 50%;
            top: 20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            transform: translateX(-50%);
            z-index: 10;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        }

        .timeline-icon.berkas {
            background: linear-gradient(45deg, #ff6b6b, #feca57);
        }

        .timeline-icon.pbt {
            background: linear-gradient(45deg, #48dbfb, #0abde3);
        }

        .timeline-icon.completed {
            background: linear-gradient(45deg, #7bed9f, #2ed573);
        }

        .timeline-icon.urgent {
            background: linear-gradient(45deg, #ff9ff3, #f368e0);
        }

        .timeline-date {
            font-size: 0.9rem;
            color: #667eea;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .timeline-title-item {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 15px;
        }

        .timeline-description {
            color: #718096;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .timeline-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .detail-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            color: white;
        }

        .status-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-top: 15px;
            display: inline-block;
        }

        .status-badge.new {
            background: rgba(255, 107, 107, 0.2);
            color: #ff6b6b;
        }

        .status-badge.process {
            background: rgba(72, 219, 251, 0.2);
            color: #0abde3;
        }

        .status-badge.completed {
            background: rgba(123, 237, 159, 0.2);
            color: #2ed573;
        }

        .stats-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 50px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 25px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: #2d3748;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #718096;
            font-weight: 500;
        }

        .load-more {
            text-align: center;
            margin-top: 50px;
        }

        .load-more-btn {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            color: #667eea;
            border: 2px solid #667eea;
            padding: 15px 30px;
            border-radius: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .load-more-btn:hover {
            background: #667eea;
            color: white;
            transform: translateY(-3px);
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .timeline::before {
                left: 30px;
            }

            .timeline-content {
                width: calc(100% - 80px);
                margin-left: 80px !important;
                margin-right: 0 !important;
            }

            .timeline-content::before {
                left: -30px !important;
                border-right-color: rgba(255, 255, 255, 0.95) !important;
                border-left-color: transparent !important;
            }

            .timeline-icon {
                left: 30px;
            }

            .timeline-details {
                grid-template-columns: 1fr;
            }

            .timeline-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-gavel"></i>
                </div>
                <div class="logo-text">Notelen Timeline</div>
            </div>
            <div class="header-actions">
                <a href="<?= base_url('notelen/berkas_masuk_template') ?>" class="btn btn-outline">
                    <i class="fas fa-plus"></i>
                    Tambah Berkas
                </a>
                <button class="btn btn-primary" onclick="refreshTimeline()">
                    <i class="fas fa-sync-alt"></i>
                    Refresh
                </button>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="timeline-header">
            <h1 class="timeline-title">Timeline Aktivitas</h1>
            <p class="timeline-subtitle">Pantau semua aktivitas sistem notelen dalam tampilan timeline interaktif</p>
            
            <div class="filter-tabs">
                <button class="filter-tab active" data-filter="all">Semua</button>
                <button class="filter-tab" data-filter="berkas">Berkas Masuk</button>
                <button class="filter-tab" data-filter="pbt">PBT</button>
                <button class="filter-tab" data-filter="completed">Selesai</button>
                <button class="filter-tab" data-filter="urgent">Urgent</button>
            </div>
        </div>

        <div class="stats-overview">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(45deg, #ff6b6b, #feca57);">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-number" id="total-berkas">0</div>
                <div class="stat-label">Total Berkas</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(45deg, #48dbfb, #0abde3);">
                    <i class="fas fa-gavel"></i>
                </div>
                <div class="stat-number" id="total-pbt">0</div>
                <div class="stat-label">PBT Cases</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(45deg, #7bed9f, #2ed573);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number" id="completed-today">0</div>
                <div class="stat-label">Selesai Hari Ini</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(45deg, #ff9ff3, #f368e0);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-number" id="urgent-cases">0</div>
                <div class="stat-label">Urgent</div>
            </div>
        </div>

        <div class="timeline" id="timeline-container">
            <!-- Timeline items will be loaded here via AJAX -->
            <div class="timeline-item" data-category="berkas">
                <div class="timeline-icon berkas">
                    <i class="fas fa-file-import"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-date">Hari ini, 14:30</div>
                    <h3 class="timeline-title-item">Berkas Baru Diterima</h3>
                    <p class="timeline-description">Berkas perkara perceraian telah diterima dan masuk ke dalam sistem untuk diproses lebih lanjut.</p>
                    <div class="timeline-details">
                        <div class="detail-item">
                            <div class="detail-icon" style="background: linear-gradient(45deg, #ff6b6b, #feca57);">
                                <i class="fas fa-hashtag"></i>
                            </div>
                            <span>No. 123/Pdt.G/2024</span>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon" style="background: linear-gradient(45deg, #48dbfb, #0abde3);">
                                <i class="fas fa-user"></i>
                            </div>
                            <span>John Doe vs Jane Doe</span>
                        </div>
                    </div>
                    <span class="status-badge new">Baru</span>
                </div>
            </div>

            <div class="timeline-item" data-category="pbt">
                <div class="timeline-icon pbt">
                    <i class="fas fa-gavel"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-date">Hari ini, 13:15</div>
                    <h3 class="timeline-title-item">PBT Sedang Diproses</h3>
                    <p class="timeline-description">Proses Pembacaan Berita Talak (PBT) sedang berlangsung dan akan segera diselesaikan.</p>
                    <div class="timeline-details">
                        <div class="detail-item">
                            <div class="detail-icon" style="background: linear-gradient(45deg, #7bed9f, #2ed573);">
                                <i class="fas fa-clock"></i>
                            </div>
                            <span>Progress: 75%</span>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon" style="background: linear-gradient(45deg, #ff9ff3, #f368e0);">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <span>Est. Selesai: Besok</span>
                        </div>
                    </div>
                    <span class="status-badge process">Proses</span>
                </div>
            </div>

            <div class="timeline-item" data-category="completed">
                <div class="timeline-icon completed">
                    <i class="fas fa-check-double"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-date">Hari ini, 11:45</div>
                    <h3 class="timeline-title-item">Berkas Selesai Diverifikasi</h3>
                    <p class="timeline-description">Berkas perkara telah selesai diverifikasi dan siap untuk tahap selanjutnya.</p>
                    <div class="timeline-details">
                        <div class="detail-item">
                            <div class="detail-icon" style="background: linear-gradient(45deg, #7bed9f, #2ed573);">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <span>Verified by: Admin</span>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon" style="background: linear-gradient(45deg, #48dbfb, #0abde3);">
                                <i class="fas fa-thumbs-up"></i>
                            </div>
                            <span>Quality: Excellent</span>
                        </div>
                    </div>
                    <span class="status-badge completed">Selesai</span>
                </div>
            </div>

            <div class="timeline-item" data-category="urgent">
                <div class="timeline-icon urgent">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-date">Kemarin, 16:20</div>
                    <h3 class="timeline-title-item">Berkas Memerlukan Perhatian</h3>
                    <p class="timeline-description">Berkas ini telah melewati batas waktu standar dan memerlukan perhatian khusus untuk segera diselesaikan.</p>
                    <div class="timeline-details">
                        <div class="detail-item">
                            <div class="detail-icon" style="background: linear-gradient(45deg, #ff6b6b, #feca57);">
                                <i class="fas fa-clock"></i>
                            </div>
                            <span>Overdue: 3 hari</span>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon" style="background: linear-gradient(45deg, #ff9ff3, #f368e0);">
                                <i class="fas fa-flag"></i>
                            </div>
                            <span>Priority: High</span>
                        </div>
                    </div>
                    <span class="status-badge urgent" style="background: rgba(255, 107, 107, 0.2); color: #ff6b6b;">Urgent</span>
                </div>
            </div>
        </div>

        <div class="load-more">
            <button class="load-more-btn" onclick="loadMoreItems()">
                <i class="fas fa-chevron-down"></i>
                Muat Lebih Banyak
            </button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let currentFilter = 'all';

        // Filter functionality
        $('.filter-tab').click(function() {
            $('.filter-tab').removeClass('active');
            $(this).addClass('active');
            currentFilter = $(this).data('filter');
            filterTimelineItems();
        });

        function filterTimelineItems() {
            $('.timeline-item').each(function() {
                const category = $(this).data('category');
                if (currentFilter === 'all' || category === currentFilter) {
                    $(this).show().css('animation', 'fadeInUp 0.6s ease forwards');
                } else {
                    $(this).hide();
                }
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
                        $('#completed-today').text(data.pbt_selesai || 0);
                        $('#urgent-cases').text(data.urgent_count || 0);
                        
                        animateNumbers();
                    }
                },
                error: function() {
                    console.log('Failed to load data');
                }
            });
        }

        function animateNumbers() {
            $('.stat-number').each(function() {
                const $this = $(this);
                const final = parseInt($this.text()) || 0;
                $this.text('0');
                
                $({ counter: 0 }).animate({ counter: final }, {
                    duration: 2000,
                    step: function() {
                        $this.text(Math.ceil(this.counter));
                    }
                });
            });
        }

        function refreshTimeline() {
            // Add refresh animation
            $('.timeline-item').css('animation', 'fadeInUp 0.6s ease forwards');
            loadData();
        }

        function loadMoreItems() {
            // Implement load more functionality
            alert('Fitur load more akan segera tersedia!');
        }

        // Auto refresh every minute
        setInterval(loadData, 60000);
        
        // Initial load
        $(document).ready(function() {
            loadData();
            setTimeout(animateNumbers, 500);
        });

        // Add intersection observer for scroll animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'fadeInUp 0.6s ease forwards';
                }
            });
        });

        document.querySelectorAll('.timeline-item').forEach((item) => {
            observer.observe(item);
        });
    </script>
</body>
</html>
