<?php
/**
 * AJAX Configuration for Server Compatibility
 * Include this in all pages that use AJAX
 */
?>
<script>
// Global AJAX Configuration for Server Compatibility
var NOTELEN_CONFIG = {
    base_url: '<?= base_url() ?>',
    site_url: '<?= site_url() ?>',
    current_url: '<?= current_url() ?>',
    environment: '<?= ENVIRONMENT ?>',
    csrf_token_name: '<?= $this->security->get_csrf_token_name() ?>',
    csrf_hash: '<?= $this->security->get_csrf_hash() ?>',
    ajax_timeout: 30000
};

// Set global AJAX defaults
$.ajaxSetup({
    timeout: NOTELEN_CONFIG.ajax_timeout,
    beforeSend: function(xhr, settings) {
        // Add CSRF token for CodeIgniter security
        if (settings.type === 'POST') {
            var csrfName = NOTELEN_CONFIG.csrf_token_name;
            var csrfHash = NOTELEN_CONFIG.csrf_hash;
            
            if (settings.data) {
                settings.data += '&' + csrfName + '=' + csrfHash;
            } else {
                settings.data = csrfName + '=' + csrfHash;
            }
        }
        
        // Add loading indicator
        if (!settings.silent) {
            showAjaxLoading();
        }
    },
    complete: function() {
        hideAjaxLoading();
    },
    error: function(xhr, status, error) {
        console.error('AJAX Error:', {
            status: status,
            error: error,
            responseText: xhr.responseText,
            url: this.url
        });
        
        // User-friendly error handling
        if (status === 'timeout') {
            showErrorMessage('Request timeout. Silakan coba lagi.');
        } else if (status === 'error' && xhr.status === 0) {
            showErrorMessage('Tidak dapat terhubung ke server. Periksa koneksi internet.');
        } else if (xhr.status === 403) {
            showErrorMessage('Access denied. Silakan refresh halaman.');
        } else if (xhr.status === 404) {
            showErrorMessage('Halaman tidak ditemukan.');
        } else if (xhr.status === 500) {
            showErrorMessage('Server error. Silakan hubungi administrator.');
        } else {
            showErrorMessage('Terjadi kesalahan. Silakan coba lagi.');
        }
    }
});

// Helper functions
function getAjaxUrl(controller_method) {
    return NOTELEN_CONFIG.site_url + controller_method;
}

function showAjaxLoading() {
    if ($('#ajax-loading').length === 0) {
        $('body').append('<div id="ajax-loading" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.3);z-index:9999;display:flex;align-items:center;justify-content:center;"><div style="background:white;padding:20px;border-radius:5px;"><i class="fas fa-spinner fa-spin"></i> Loading...</div></div>');
    }
}

function hideAjaxLoading() {
    $('#ajax-loading').remove();
}

function showErrorMessage(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: message
        });
    } else {
        alert(message);
    }
}

function showSuccessMessage(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: message,
            timer: 2000
        });
    } else {
        alert(message);
    }
}

// Server compatibility checks
$(document).ready(function() {
    // Test server connectivity
    if (NOTELEN_CONFIG.environment === 'production') {
        $.ajax({
            url: getAjaxUrl('notelen/test_server_connection'),
            method: 'GET',
            silent: true,
            success: function(response) {
                console.log('Server connection: OK');
            },
            error: function() {
                console.warn('Server connection: Issue detected');
            }
        });
    }
});
</script>
