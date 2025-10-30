<footer class="main-footer">
	<div class="d-flex justify-content-between align-items-center">
		<div>
			<strong>Sistem Informasi Pengadilan Agama Amuntai &copy; <?php echo date('Y'); ?></strong>
		</div>
		<div>
			<span class="text-muted">Dikembangkan oleh</span>
			<a href="#" class="text-primary">Tim IT Pengadilan Agama Amuntai</a>
			<span class="badge badge-info">v2.1</span>
		</div>
	</div>
</footer>

<aside class="control-sidebar control-sidebar-dark">
	<!-- Control sidebar content goes here -->
</aside>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="<?php echo base_url() ?>assets/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url() ?>assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
	$.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url() ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- DataTables & Plugins -->
<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/jszip/jszip.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- overlayScrollbars -->
<script src="<?php echo base_url() ?>assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url() ?>assets/dist/js/adminlte.js"></script>

<script>
	$(function() {
		// Initialize DataTables with export buttons
		$("#example1").DataTable({
			"responsive": true,
			"lengthChange": true,
			"autoWidth": false,
			"dom": '<"top d-flex justify-content-between"Bf>rt<"bottom d-flex justify-content-between"lip>',
			"buttons": [{
					extend: "copy",
					className: "btn-sm btn-secondary",
					text: '<i class="fas fa-copy"></i> Salin'
				},
				{
					extend: "csv",
					className: "btn-sm btn-secondary",
					text: '<i class="fas fa-file-csv"></i> CSV'
				},
				{
					extend: "excel",
					className: "btn-sm btn-secondary",
					text: '<i class="fas fa-file-excel"></i> Excel'
				},
				{
					extend: "pdf",
					className: "btn-sm btn-secondary",
					text: '<i class="fas fa-file-pdf"></i> PDF'
				},
				{
					extend: "print",
					className: "btn-sm btn-secondary",
					text: '<i class="fas fa-print"></i> Cetak'
				},
				{
					extend: "colvis",
					className: "btn-sm btn-secondary",
					text: '<i class="fas fa-columns"></i> Kolom'
				}
			],
			"language": {
				"info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
				"infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
				"infoFiltered": "(disaring dari _MAX_ total data)",
				"search": "Cari:",
				"lengthMenu": "Tampilkan _MENU_ data",
				"zeroRecords": "Tidak ada data yang cocok",
				"paginate": {
					"first": "Pertama",
					"last": "Terakhir",
					"next": "Selanjutnya",
					"previous": "Sebelumnya"
				}
			}
		}).buttons().container().appendTo('#example1_wrapper .top');

		// Enable tooltips
		$('[data-toggle="tooltip"]').tooltip();

		// Highlight active menu
		const currentPath = window.location.pathname.split('/').pop().toLowerCase();
		$('.nav-sidebar a').each(function() {
			const href = $(this).attr('href');
			if (href && href.toLowerCase().indexOf(currentPath) !== -1) {
				$(this).addClass('active');
				$(this).parents('.nav-item').addClass('menu-open');
				$(this).parents('.nav-item').children('.nav-link').addClass('active');
			}
		});
	});
</script>

<!-- Chart Helper for consistent chart initialization -->
<script src="<?= base_url() ?>assets/js/chart-helper.js"></script>

</body>

</html>