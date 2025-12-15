<?php $this->load->view('template/new_header'); ?>
<?php $this->load->view('template/new_sidebar'); ?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <i class="fas fa-users"></i> Manajemen User
                    </h1>
                    <small class="text-muted">Kelola pengguna sistem Notelen BHT</small>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Manajemen User</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo $user_stats['total_users']; ?></h3>
                            <p>Total User</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?php echo $user_stats['active_users']; ?></h3>
                            <p>User Aktif</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?php echo $user_stats['users_admin']; ?></h3>
                            <p>Administrator</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3><?php echo $user_stats['recent_logins']; ?></h3>
                            <p>Login 7 Hari</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter & Controls -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter"></i> Filter & Pencarian
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary btn-sm" onclick="openAddUserModal()">
                                    <i class="fas fa-user-plus"></i> Tambah User
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="get" id="filterForm">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Level User:</label>
                                        <select name="user_level" class="form-control">
                                            <option value="">Semua Level</option>
                                            <option value="admin" <?php echo ($filters['user_level'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                                            <option value="panitera_pengganti" <?php echo ($filters['user_level'] === 'panitera_pengganti') ? 'selected' : ''; ?>>Panitera Pengganti</option>
                                            <option value="staff" <?php echo ($filters['user_level'] === 'staff') ? 'selected' : ''; ?>>Staff</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Status:</label>
                                        <select name="is_active" class="form-control">
                                            <option value="">Semua Status</option>
                                            <option value="1" <?php echo ($filters['is_active'] === '1') ? 'selected' : ''; ?>>Aktif</option>
                                            <option value="0" <?php echo ($filters['is_active'] === '0') ? 'selected' : ''; ?>>Nonaktif</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Pencarian:</label>
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Username, nama, atau email..."
                                            value="<?php echo htmlspecialchars($filters['search']); ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <label>&nbsp;</label>
                                        <div>
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="fas fa-search"></i> Cari
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list"></i> Daftar User
                                <span class="badge badge-info"><?php echo $total_users; ?> user</span>
                            </h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Nama Lengkap</th>
                                        <th>Email</th>
                                        <th>Level</th>
                                        <th>Status</th>
                                        <th>Last Login</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($users)): ?>
                                        <?php foreach ($users as $user): ?>
                                            <tr>
                                                <td><?php echo $user->id; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($user->username); ?></strong>
                                                </td>
                                                <td><?php echo htmlspecialchars($user->full_name); ?></td>
                                                <td><?php echo htmlspecialchars($user->email ?: '-'); ?></td>
                                                <td>
                                                    <?php
                                                    $level_badges = [
                                                        'admin' => '<span class="badge badge-danger"><i class="fas fa-user-shield"></i> Admin</span>',
                                                        'panitera_pengganti' => '<span class="badge badge-warning"><i class="fas fa-user-tie"></i> Panitera Pengganti</span>',
                                                        'staff' => '<span class="badge badge-info"><i class="fas fa-user"></i> Staff</span>'
                                                    ];
                                                    echo isset($level_badges[$user->user_level]) ? $level_badges[$user->user_level] : $user->user_level;
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php if ($user->is_active): ?>
                                                        <span class="badge badge-success">Aktif</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-secondary">Nonaktif</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php echo $user->last_login ? date('d/m/Y H:i', strtotime($user->last_login)) : '-'; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-info btn-sm"
                                                            onclick="editUser(<?php echo $user->id; ?>)"
                                                            title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <?php if ($user->user_level !== 'admin'): ?>
                                                            <button class="btn btn-danger btn-sm"
                                                                onclick="deleteUser(<?php echo $user->id; ?>, '<?php echo htmlspecialchars($user->username); ?>')"
                                                                title="Hapus">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada data user</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if ($total_pages > 1): ?>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="dataTables_info">
                                            Halaman <?php echo $current_page; ?> dari <?php echo $total_pages; ?>
                                            (Total: <?php echo $total_users; ?> user)
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="dataTables_paginate paging_simple_numbers float-right">
                                            <?php if ($current_page > 1): ?>
                                                <a href="?page=<?php echo ($current_page - 1); ?>" class="paginate_button previous">Previous</a>
                                            <?php endif; ?>

                                            <?php for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++): ?>
                                                <a href="?page=<?php echo $i; ?>"
                                                    class="paginate_button <?php echo ($i == $current_page) ? 'current' : ''; ?>">
                                                    <?php echo $i; ?>
                                                </a>
                                            <?php endfor; ?>

                                            <?php if ($current_page < $total_pages): ?>
                                                <a href="?page=<?php echo ($current_page + 1); ?>" class="paginate_button next">Next</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- Modal Add User -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">
                    <i class="fas fa-user-plus"></i> Tambah User Baru
                </h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addUserForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Level User <span class="text-danger">*</span></label>
                                <select name="user_level" class="form-control" required>
                                    <option value="staff">Staff</option>
                                    <option value="panitera_pengganti">Panitera Pengganti</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status</label>
                                <div class="form-control">
                                    <div class="icheck-primary">
                                        <input type="checkbox" id="is_active_add" name="is_active" value="1" checked>
                                        <label for="is_active_add">User Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit User -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h4 class="modal-title">
                    <i class="fas fa-user-edit"></i> Edit User
                </h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editUserForm">
                <input type="hidden" name="user_id" id="edit_user_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" id="edit_username" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Password <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                                <input type="password" name="password" id="edit_password" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" id="edit_full_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Level User <span class="text-danger">*</span></label>
                                <select name="user_level" id="edit_user_level" class="form-control" required>
                                    <option value="staff">Staff</option>
                                    <option value="panitera_pengganti">Panitera Pengganti</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" id="edit_email" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status</label>
                                <div class="form-control">
                                    <div class="icheck-primary">
                                        <input type="checkbox" id="is_active_edit" name="is_active" value="1">
                                        <label for="is_active_edit">User Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript untuk SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        console.log('User Management loaded successfully!');
    });

    // Open Add User Modal
    function openAddUserModal() {
        $('#addUserForm')[0].reset();
        $('#addUserModal').modal('show');
    }

    // Add User Form Submit
    $('#addUserForm').submit(function(e) {
        e.preventDefault();

        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({
            url: '<?php echo base_url('auth/ajax_add_user'); ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success'
                    }).then(function() {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: response.message,
                        icon: 'error'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan koneksi',
                    icon: 'error'
                });
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Edit User
    function editUser(userId) {
        // Implement edit functionality
        Swal.fire({
            title: 'Fitur Edit',
            text: 'Fitur edit user akan segera tersedia',
            icon: 'info'
        });
    }

    // Delete User
    function deleteUser(userId, username) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: `Hapus user "${username}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?php echo base_url('auth/ajax_delete_user'); ?>',
                    type: 'POST',
                    data: {
                        user_id: userId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success'
                            }).then(function() {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Gagal!',
                                text: response.message,
                                icon: 'error'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan koneksi',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    }
</script>

<?php $this->load->view('template/new_footer'); ?>