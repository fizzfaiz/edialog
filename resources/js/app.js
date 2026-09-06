import './bootstrap';
import $ from 'jquery';
import 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';

// Make jQuery globally available for inline scripts
window.$ = $;
window.jQuery = $;

// =============================================
// Dark / Light Mode Toggle
// =============================================

(function () {
    // Apply theme immediately (before DOMContentLoaded) to prevent flash.
    // localStorage (navbar quick toggle) overrides the server-rendered user setting.
    const savedTheme = localStorage.getItem('theme');

    if (savedTheme === 'dark') {
        document.documentElement.classList.add('dark-mode');
    } else if (savedTheme === 'light') {
        document.documentElement.classList.remove('dark-mode');
    }
})();

document.addEventListener('DOMContentLoaded', function () {
    // Theme toggle
    const themeToggle = document.getElementById('themeToggle');
    const themeIconDark = document.getElementById('themeIconDark');
    const themeIconLight = document.getElementById('themeIconLight');
    const appHtml = document.getElementById('appHtml');

    function persistTheme(theme) {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrf) return;
        fetch('/settings/theme', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ theme: theme })
        }).catch(function () {});
    }

    function setTheme(isDark) {
        if (isDark) {
            appHtml.classList.add('dark-mode');
            themeIconDark.classList.add('d-none');
            themeIconLight.classList.remove('d-none');
            localStorage.setItem('theme', 'dark');
        } else {
            appHtml.classList.remove('dark-mode');
            themeIconLight.classList.add('d-none');
            themeIconDark.classList.remove('d-none');
            localStorage.setItem('theme', 'light');
        }
        persistTheme(isDark ? 'dark' : 'light');
    }

    // Initialize icon states based on current theme
    if (appHtml.classList.contains('dark-mode')) {
        themeIconDark.classList.add('d-none');
        themeIconLight.classList.remove('d-none');
    }

    // Toggle theme on button click
    if (themeToggle) {
        themeToggle.addEventListener('click', function () {
            const isDark = appHtml.classList.contains('dark-mode');
            setTheme(!isDark);
        });
    }

    // =============================================
    // Sidebar Toggle Functionality
    // =============================================

    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');

    if (!sidebar || !sidebarToggle) return;

    // Load saved sidebar state from localStorage
    const sidebarState = localStorage.getItem('sidebarCollapsed');
    if (sidebarState === 'true') {
        sidebar.classList.add('collapsed');
    }

    // Toggle sidebar on button click
    sidebarToggle.addEventListener('click', function () {
        // On mobile: toggle 'show' class
        if (window.innerWidth < 768) {
            sidebar.classList.toggle('show');
            sidebar.classList.remove('collapsed');
            return;
        }

        // On desktop: toggle 'collapsed' class
        sidebar.classList.toggle('collapsed');

        // Save state to localStorage
        const isCollapsed = sidebar.classList.contains('collapsed');
        localStorage.setItem('sidebarCollapsed', isCollapsed);
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function (e) {
        if (window.innerWidth >= 768) return;

        const isClickInsideSidebar = sidebar.contains(e.target);
        const isClickOnToggle = sidebarToggle.contains(e.target);

        if (!isClickInsideSidebar && !isClickOnToggle && sidebar.classList.contains('show')) {
            sidebar.classList.remove('show');
        }
    });

    // Handle window resize
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 768) {
            sidebar.classList.remove('show');
        }
    });

    // Save collapsed state before page unload
    window.addEventListener('beforeunload', function () {
        const isCollapsed = sidebar.classList.contains('collapsed');
        localStorage.setItem('sidebarCollapsed', isCollapsed);
    });

    // =============================================
    // DataTable Initialization (Dialog Prestasi Page)
    // =============================================

    const dialogPrestasiTable = document.getElementById('dialogPrestasiTable');
    if (dialogPrestasiTable) {
        const dataUrl = dialogPrestasiTable.getAttribute('data-url') || '';
        console.log('Dialog Prestasi DataTable initializing with URL:', dataUrl);
        console.log('jQuery available:', typeof $ !== 'undefined');
        console.log('DataTable plugin available:', typeof $.fn.DataTable !== 'undefined');
        initDialogPrestasiTable(dataUrl);
    }
});

/**
 * Initialize DataTable for Dialog Prestasi reports page.
 * @param {string} dataUrl - The AJAX endpoint URL.
 */
function initDialogPrestasiTable(dataUrl) {
    console.log('Initializing DataTable with URL:', dataUrl);
    const table = $('#dialogPrestasiTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'Semua']],
        language: {
            processing: '<div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>Memproses...',
            search: '<i class="bi bi-search"></i>',
            searchPlaceholder: 'Carian...',
            lengthMenu: '_MENU_ rekod / halaman',
            info: 'Memaparkan _START_ hingga _END_ dari _TOTAL_ rekod',
            infoEmpty: 'Tiada rekod ditemui',
            infoFiltered: '(ditapis dari _MAX_ rekod)',
            zeroRecords: 'Tiada laporan Dialog Prestasi ditemui.',
            paginate: {
                first: '<i class="bi bi-chevron-double-left"></i>',
                last: '<i class="bi bi-chevron-double-right"></i>',
                previous: '<i class="bi bi-chevron-left"></i>',
                next: '<i class="bi bi-chevron-right"></i>',
            },
        },
        ajax: {
            url: dataUrl,
            type: 'GET',
            data: function (d) {
                d.tarikh = document.getElementById('filterTarikh')?.value || '';
            },
            error: function (xhr, error, thrown) {
                console.error('DataTables AJAX error:', error, thrown);
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'id', orderable: false, searchable: false },
            {
                data: 'pengerusi',
                name: 'pengerusi',
                render: function (data) {
                    return '<span class="fw-medium">' + (data || '-') + '</span>';
                }
            },
            {
                data: 'tarikh',
                name: 'tarikh',
                render: function (data) {
                    return '<span class="badge bg-light text-dark">' + data + '</span>';
                }
            },
            { data: 'hari', name: 'hari' },
            {
                data: 'masa',
                name: 'masa',
                render: function (data) {
                    return '<i class="bi bi-clock text-muted me-1"></i>' + data;
                }
            },
            {
                data: 'tempat',
                name: 'tempat',
                render: function (data) {
                    return '<i class="bi bi-geo-alt text-muted me-1"></i>' + data;
                }
            },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[2, 'desc']],
        drawCallback: function () {
            $('[title]').tooltip({ container: 'body' });
        }
    });

    // Filter form submit
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            table.ajax.reload();
        });
    }

    // Reset filter
    const resetBtn = document.getElementById('resetFilter');
    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            const filterTarikh = document.getElementById('filterTarikh');
            if (filterTarikh) filterTarikh.value = '';
            table.ajax.reload();
        });
    }

    // Delete handler (delegated)
    $(document).on('click', '.delete-btn', function () {
        const url = $(this).data('url');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        if (confirm('Anda pasti mahu memadam laporan ini? Tindakan ini tidak boleh dibatalkan.')) {
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: csrfToken,
                    _method: 'DELETE'
                },
                success: function () {
                    table.ajax.reload(null, false);
                    const alertHtml = '<div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert" style="z-index:9999">' +
                        '<i class="bi bi-check-circle me-2"></i>Laporan berjaya dipadam.' +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
                    $('body').append(alertHtml);
                    setTimeout(function () {
                        $('.alert').alert('close');
                    }, 3000);
                },
                error: function (xhr) {
                    alert('Ralat: Gagal memadam laporan. Sila cuba lagi.');
                    console.error('Delete error:', xhr.responseText);
                }
            });
        }
    });
}
