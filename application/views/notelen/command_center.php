<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Command Center - Notelen System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0c0c0c 0%, #1a1a2e 50%, #16213e 100%);
            font-family: 'Rajdhani', sans-serif;
            color: #00ff88;
            overflow-x: hidden;
            min-height: 100vh;
        }

        .command-header {
            background: linear-gradient(90deg, rgba(0,255,136,0.1) 0%, rgba(0,180,255,0.1) 100%);
            border-bottom: 2px solid #00ff88;
            padding: 20px 0;
            position: relative;
        }

        .command-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #00ff88, #00b4ff, #00ff88);
            animation: pulse 2s infinite;
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .system-title {
            font-family: 'Orbitron', monospace;
            font-size: 2.5rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #00ff88;
            text-shadow: 0 0 20px rgba(0,255,136,0.8);
        }

        .status-indicators {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .status-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 15px;
            background: rgba(0,255,136,0.1);
            border: 1px solid #00ff88;
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }

        .status-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #00ff88;
            animation: blink 1.5s infinite;
        }

        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 20px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            grid-template-rows: auto auto auto;
            gap: 25px;
        }

        .command-panel {
            background: linear-gradient(145deg, rgba(0,255,136,0.05) 0%, rgba(0,180,255,0.05) 100%);
            border: 1px solid rgba(0,255,136,0.3);
            border-radius: 15px;
            padding: 25px;
            position: relative;
            backdrop-filter: blur(20px);
            transition: all 0.3s ease;
        }

        .command-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #00ff88, #00b4ff);
            border-radius: 15px 15px 0 0;
        }

        .command-panel:hover {
            border-color: #00ff88;
            box-shadow: 0 10px 30px rgba(0,255,136,0.2);
            transform: translateY(-5px);
        }

        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .panel-title {
            font-family: 'Orbitron', monospace;
            font-size: 1.3rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #00ff88;
        }

        .panel-icon {
            font-size: 1.5rem;
            color: #00b4ff;
        }

        .metric-grid {
            grid-column: span 3;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
        }

        .metric-card {
            background: linear-gradient(145deg, rgba(0,255,136,0.1) 0%, rgba(0,180,255,0.1) 100%);
            border: 1px solid rgba(0,255,136,0.4);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(from 0deg, transparent, rgba(0,255,136,0.1), transparent);
            animation: rotate 4s linear infinite;
        }

        .metric-value {
            font-family: 'Orbitron', monospace;
            font-size: 2.8rem;
            font-weight: 900;
            color: #00ff88;
            text-shadow: 0 0 15px rgba(0,255,136,0.8);
            position: relative;
            z-index: 2;
        }

        .metric-label {
            font-size: 0.9rem;
            color: #00b4ff;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 8px;
            position: relative;
            z-index: 2;
        }

        .activity-feed {
            grid-column: span 2;
            max-height: 400px;
            overflow-y: auto;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid rgba(0,255,136,0.2);
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(45deg, #00ff88, #00b4ff);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
            font-weight: bold;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            color: #00ff88;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .activity-time {
            color: #00b4ff;
            font-size: 0.85rem;
        }

        .chart-panel {
            grid-row: span 2;
            position: relative;
        }

        .chart-container {
            height: 300px;
            position: relative;
        }

        .control-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 20px;
        }

        .control-btn {
            background: linear-gradient(45deg, #00ff88, #00b4ff);
            border: none;
            color: #000;
            padding: 15px 25px;
            border-radius: 8px;
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .control-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 20px rgba(0,255,136,0.4);
        }

        .control-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: all 0.3s ease;
        }

        .control-btn:active::before {
            width: 300px;
            height: 300px;
        }

        .scanning-line {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #00ff88, transparent);
            animation: scan 3s linear infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes scan {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        @media (max-width: 1200px) {
            .main-container {
                grid-template-columns: 1fr 1fr;
            }
            .metric-grid {
                grid-column: span 2;
                grid-template-columns: repeat(3, 1fr);
            }
            .activity-feed {
                grid-column: span 2;
            }
        }

        @media (max-width: 768px) {
            .main-container {
                grid-template-columns: 1fr;
            }
            .metric-grid {
                grid-column: span 1;
                grid-template-columns: repeat(2, 1fr);
            }
            .activity-feed {
                grid-column: span 1;
            }
            .system-title {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="scanning-line"></div>
    
    <header class="command-header">
        <div class="header-content">
            <h1 class="system-title">NOTELEN Command Center</h1>
            <div class="status-indicators">
                <div class="status-item">
                    <div class="status-dot"></div>
                    <span>SYSTEM ONLINE</span>
                </div>
                <div class="status-item">
                    <div class="status-dot"></div>
                    <span>DATABASE ACTIVE</span>
                </div>
                <div class="status-item">
                    <div class="status-dot"></div>
                    <span id="current-time"></span>
                </div>
            </div>
        </div>
    </header>

    <main class="main-container">
        <!-- Metrics Grid -->
        <div class="metric-grid">
            <div class="metric-card">
                <div class="metric-value" id="total-berkas">0</div>
                <div class="metric-label">Total Berkas</div>
            </div>
            <div class="metric-card">
                <div class="metric-value" id="total-pbt">0</div>
                <div class="metric-label">PBT Cases</div>
            </div>
            <div class="metric-card">
                <div class="metric-value" id="pending-cases">0</div>
                <div class="metric-label">Pending</div>
            </div>
            <div class="metric-card">
                <div class="metric-value" id="completed-today">0</div>
                <div class="metric-label">Completed Today</div>
            </div>
            <div class="metric-card">
                <div class="metric-value" id="system-efficiency">98%</div>
                <div class="metric-label">Efficiency</div>
            </div>
        </div>

        <!-- Activity Monitor -->
        <div class="command-panel activity-feed">
            <div class="panel-header">
                <h3 class="panel-title">Activity Monitor</h3>
                <i class="fas fa-radar-chart panel-icon"></i>
            </div>
            <div id="activity-list">
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Berkas baru diterima</div>
                        <div class="activity-time">2 menit yang lalu</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">PBT selesai diproses</div>
                        <div class="activity-time">5 menit yang lalu</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Chart -->
        <div class="command-panel chart-panel">
            <div class="panel-header">
                <h3 class="panel-title">System Analysis</h3>
                <i class="fas fa-chart-line panel-icon"></i>
            </div>
            <div class="chart-container">
                <canvas id="systemChart"></canvas>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="command-panel">
            <div class="panel-header">
                <h3 class="panel-title">Quick Actions</h3>
                <i class="fas fa-bolt panel-icon"></i>
            </div>
            <div class="control-buttons">
                <button class="control-btn" onclick="location.href='<?= base_url('notelen/berkas_masuk_template') ?>'">
                    <i class="fas fa-plus"></i> Add Berkas
                </button>
                <button class="control-btn" onclick="location.href='<?= base_url('notelen/berkas_pbt_template') ?>'">
                    <i class="fas fa-gavel"></i> PBT Process
                </button>
                <button class="control-btn" onclick="refreshData()">
                    <i class="fas fa-sync"></i> Refresh Data
                </button>
                <button class="control-btn" onclick="exportReport()">
                    <i class="fas fa-download"></i> Export Report
                </button>
            </div>
        </div>

        <!-- System Status -->
        <div class="command-panel">
            <div class="panel-header">
                <h3 class="panel-title">System Status</h3>
                <i class="fas fa-server panel-icon"></i>
            </div>
            <div style="space-y: 15px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                    <span>Database Connection:</span>
                    <span style="color: #00ff88;"><i class="fas fa-check-circle"></i> ACTIVE</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                    <span>Server Load:</span>
                    <span style="color: #00ff88;">12% CPU</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                    <span>Memory Usage:</span>
                    <span style="color: #00ff88;">256 MB</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>Last Backup:</span>
                    <span style="color: #00b4ff;">2 hours ago</span>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Real-time clock
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { hour24: true });
            document.getElementById('current-time').textContent = timeString;
        }
        setInterval(updateTime, 1000);
        updateTime();

        // Initialize Chart
        const ctx = document.getElementById('systemChart').getContext('2d');
        const systemChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Berkas Processed',
                    data: [12, 19, 15, 25, 22, 30],
                    borderColor: '#00ff88',
                    backgroundColor: 'rgba(0,255,136,0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'PBT Completed',
                    data: [8, 12, 10, 18, 15, 20],
                    borderColor: '#00b4ff',
                    backgroundColor: 'rgba(0,180,255,0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#00ff88'
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: { color: '#00ff88' },
                        grid: { color: 'rgba(0,255,136,0.2)' }
                    },
                    x: {
                        ticks: { color: '#00ff88' },
                        grid: { color: 'rgba(0,255,136,0.2)' }
                    }
                }
            }
        });

        // Load real data
        function loadData() {
            $.ajax({
                url: '<?= base_url("notelen/ajax_get_analytics_data") ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const data = response.data;
                        document.getElementById('total-berkas').textContent = data.total_berkas_masuk || 0;
                        document.getElementById('total-pbt').textContent = data.total_pbt || 0;
                        document.getElementById('pending-cases').textContent = data.pending_process || 0;
                        document.getElementById('completed-today').textContent = data.pbt_selesai || 0;
                    }
                },
                error: function() {
                    console.log('Failed to load data');
                }
            });
        }

        function refreshData() {
            loadData();
            // Add visual feedback
            document.querySelectorAll('.metric-value').forEach(el => {
                el.style.animation = 'none';
                setTimeout(() => {
                    el.style.animation = 'pulse 1s ease';
                }, 10);
            });
        }

        function exportReport() {
            window.open('<?= base_url("notelen/export_report") ?>', '_blank');
        }

        // Auto refresh every 30 seconds
        setInterval(loadData, 30000);
        
        // Initial load
        loadData();
    </script>
</body>
</html>
