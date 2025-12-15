<?php $this->load->view('template/new_header'); ?>
<?php $this->load->view('template/new_sidebar'); ?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo isset($title) ? $title : 'Admin Dashboard'; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Admin Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Info boxes -->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-file-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Berkas</span>
                            <span class="info-box-number">
                                <?php echo isset($dashboard_stats->total_berkas) ? $dashboard_stats->total_berkas : 0; ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Berkas Selesai</span>
                            <span class="info-box-number">
                                <?php echo isset($dashboard_stats->berkas_selesai) ? $dashboard_stats->berkas_selesai : 0; ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-clock"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Berkas Proses</span>
                            <span class="info-box-number">
                                <?php echo isset($dashboard_stats->berkas_proses) ? $dashboard_stats->berkas_proses : 0; ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Users</span>
                            <span class="info-box-number">
                                <?php echo isset($user_stats['total']) ? $user_stats['total'] : 0; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-7 connectedSortable">
                    <!-- Recent Activity card -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-1"></i>
                                Statistik Berkas Hari Ini
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-success">
                                            <i class="fas fa-caret-up"></i>
                                            <?php echo isset($dashboard_stats->berkas_hari_ini) ? $dashboard_stats->berkas_hari_ini : 0; ?>
                                        </span>
                                        <h5 class="description-header">Berkas Hari Ini</h5>
                                        <span class="description-text">BERKAS MASUK</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="description-block">
                                        <span class="description-percentage text-warning">
                                            <i class="fas fa-caret-left"></i>
                                            <?php echo isset($dashboard_stats->berkas_minggu_ini) ? $dashboard_stats->berkas_minggu_ini : 0; ?>
                                        </span>
                                        <h5 class="description-header">Berkas Minggu Ini</h5>
                                        <span class="description-text">BERKAS MASUK</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-tools mr-1"></i>
                                Menu Admin
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <a href="<?php echo base_url('notelen'); ?>" class="btn btn-primary btn-block">
                                        <i class="fas fa-file-alt mr-2"></i>
                                        Kelola Berkas Masuk
                                    </a>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <a href="<?php echo base_url('auth/users'); ?>" class="btn btn-success btn-block">
                                        <i class="fas fa-users mr-2"></i>
                                        Kelola Users
                                    </a>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <a href="<?php echo base_url('notelen/berkas_pbt'); ?>" class="btn btn-warning btn-block">
                                        <i class="fas fa-gavel mr-2"></i>
                                        Berkas PBT
                                    </a>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <a href="<?php echo base_url('notelen/export'); ?>" class="btn btn-info btn-block">
                                        <i class="fas fa-download mr-2"></i>
                                        Export Data
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Right col -->
                <section class="col-lg-5 connectedSortable">
                    <!-- User Info -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Admin</h3>
                        </div>
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle"
                                    src="<?php echo base_url('assets/dist/img/user2-160x160.jpg'); ?>"
                                    alt="User profile picture">
                            </div>

                            <h3 class="profile-username text-center">
                                <?php echo isset($current_user['full_name']) ? $current_user['full_name'] : 'Administrator'; ?>
                            </h3>

                            <p class="text-muted text-center">
                                <span class="badge badge-danger">
                                    <?php echo isset($current_user['user_level']) ? strtoupper($current_user['user_level']) : 'ADMIN'; ?>
                                </span>
                            </p>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Username</b>
                                    <a class="float-right">
                                        <?php echo isset($current_user['username']) ? $current_user['username'] : 'admin'; ?>
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Email</b>
                                    <a class="float-right">
                                        <?php echo isset($current_user['email']) ? $current_user['email'] : 'admin@example.com'; ?>
                                    </a>
                                </li>
                            </ul>

                            <a href="<?php echo base_url('dashboard/profile'); ?>" class="btn btn-primary btn-block">
                                <b>Edit Profile</b>
                            </a>
                        </div>
                    </div>

                    <!-- User Stats -->
                    <?php if (isset($user_stats) && !empty($user_stats)): ?>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Statistik Pengguna</h3>
                            </div>
                            <div class="card-body p-0">
                                <ul class="nav nav-pills flex-column">
                                    <?php if (isset($user_stats['admin'])): ?>
                                        <li class="nav-item active">
                                            <a href="#" class="nav-link">
                                                Admin
                                                <span class="float-right text-danger">
                                                    <i class="fas fa-star"></i>
                                                    <?php echo $user_stats['admin']; ?>
                                                </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if (isset($user_stats['panitera_pengganti'])): ?>
                                        <li class="nav-item">
                                            <a href="#" class="nav-link">
                                                Panitera Pengganti
                                                <span class="float-right text-warning">
                                                    <i class="fas fa-user-tie"></i>
                                                    <?php echo $user_stats['panitera_pengganti']; ?>
                                                </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if (isset($user_stats['staff'])): ?>
                                        <li class="nav-item">
                                            <a href="#" class="nav-link">
                                                Staff
                                                <span class="float-right text-success">
                                                    <i class="fas fa-user"></i>
                                                    <?php echo $user_stats['staff']; ?>
                                                </span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                </section>
            </div>

        </div>
    </section>
</div>

<?php $this->load->view('template/new_footer'); ?>