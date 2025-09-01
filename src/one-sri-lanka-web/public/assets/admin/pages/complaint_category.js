const DatatableAPI = (function () {
    // Configuration constants
    const CONFIG = {
        ACTIONS_COLUMN_INDEX: 6,
        STATUS_COLUMN_INDEX: 5,
        NON_SEARCHABLE_COLUMNS: [3, 4, 6], // Parent Category, Description, Actions
        STATUS_OPTIONS: [
            { value: "", text: "All" },
            { value: "active", text: "Active" },
            { value: "inactive", text: "Inactive" },
        ],
        // URL templates - will be populated from window variables
        URLS: {
            list: null,
            view: null,
            edit: null,
            delete: null,
        }
    };

    const _initializeUrls = function() {
        CONFIG.URLS.list = window.complaintCategoryUrl || '/admin/complaint-categories/get';
        CONFIG.URLS.edit = window.complaintEditUrl || '/admin/complaint-categories/:id/edit';
    };

    const _buildUrl = function(template, id) {
        return template.replace(':id', id);
    };

    const _setDataTableDefaults = function () {
        if (!$().DataTable) {
            console.warn("Warning - datatables.min.js is not loaded.");
            return false;
        }

        $.extend($.fn.dataTable.defaults, {
            autoWidth: false,
            columnDefs: [
                {
                    orderable: false,
                    width: 100,
                    targets: [CONFIG.ACTIONS_COLUMN_INDEX],
                },
            ],
            dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            language: {
                search: '<span class="me-3">Filter:</span> <div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                searchPlaceholder: "Type to filter...",
                lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                paginate: {
                    first: "First",
                    last: "Last",
                    next: document.dir == "rtl" ? "&larr;" : "&rarr;",
                    previous: document.dir == "rtl" ? "&rarr;" : "&larr;",
                },
            },
            processing: true,
            serverSide: true,
            responsive: true,
            order: [[0, 'desc']], // Default sort by ID descending
        });

        return true;
    };

    const _renderStatusBadge = function (data, type, row) {
        // Handle both raw data and pre-rendered HTML
        if (typeof data === 'string' && data.includes('<span class="badge')) {
            return data; // Already rendered
        }

        // Normalize the status value
        const status = String(data || '').toLowerCase().trim();
        const isActive = status === 'active' || status === '1' || status === 'true';
        
        const badgeClass = isActive ? "bg-success" : "bg-danger";
        const textClass = isActive ? "text-success" : "text-danger";
        const text = isActive ? "Active" : "Inactive";

        return `<span class="badge ${badgeClass} bg-opacity-10 ${textClass}">${text}</span>`;
    };

    const _renderActionDropdown = function (row) {
        const editUrl = _buildUrl(CONFIG.URLS.edit, row.id);
        
        return `
            <div class="d-inline-flex">
                <div class="dropdown">
                    <a href="#" class="text-body" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ph-list"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                         <a href="${editUrl}" class="dropdown-item">
                            <i class="ph-pencil me-2"></i>Edit
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
            </div>
        `;
    };

    const _createStatusSelect = function () {
        let html = '<select class="form-select form-select-sm">';
        CONFIG.STATUS_OPTIONS.forEach((option) => {
            html += `<option value="${option.value}">${option.text}</option>`;
        });
        html += "</select>";
        return html;
    };

    const _createSearchInput = function (title) {
        return `<input type="text" class="form-control form-control-sm" placeholder="Search ${title}" />`;
    };

    const _setupColumnSearchInputs = function () {
        $(".datatable-column-search-inputs thead tr:eq(1) th").each(function (index) {
            const title = $(this).text().trim();

            if (index === CONFIG.STATUS_COLUMN_INDEX) {
                $(this).html(_createStatusSelect());
            } else if (CONFIG.NON_SEARCHABLE_COLUMNS.includes(index)) {
                $(this).html("");
            } else {
                $(this).html(_createSearchInput(title));
            }
        });
    };

    const _attachSearchEvents = function (dataTable) {
        dataTable.columns().every(function (index) {
            const column = this;
            const $th = $(".datatable-column-search-inputs thead tr:eq(1) th").eq(index);
            const $input = $("input", $th);
            const $select = $("select", $th);

            // Debounce function for better performance
            let searchTimeout;

            if ($input.length) {
                $input.on("keyup change clear", function () {
                    const value = this.value;
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(function() {
                        if (column.search() !== value) {
                            column.search(value).draw();
                        }
                    }, 300); // 300ms debounce
                });
            }

            if ($select.length) {
                $select.on("change", function () {
                    const value = this.value;
                    if (column.search() !== value) {
                        column.search(value).draw();
                    }
                });
            }
        });
    };

    const _handleAjaxError = function(xhr, error, thrown) {
        console.error('DataTable AJAX Error:', {
            status: xhr.status,
            error: error,
            thrown: thrown,
            response: xhr.responseText
        });

        // Show user-friendly error message
        const errorMessage = xhr.status === 404 
            ? 'Data endpoint not found. Please check your configuration.'
            : xhr.status === 500 
            ? 'Server error occurred. Please try again later.'
            : 'Unable to load data. Please refresh the page.';

        // You can replace this with your preferred notification system
        if (typeof toastr !== 'undefined') {
            toastr.error(errorMessage);
        } else {
            alert(errorMessage);
        }
    };

    const _initializeDataTable = function () {
        _initializeUrls();

        if (!CONFIG.URLS.list) {
            console.error("Complaint category URL is not defined");
            return;
        }

        const dataTable = $(".datatable-column-search-inputs").DataTable({
            ajax: {
                url: CONFIG.URLS.list,
                type: 'GET',
                error: _handleAjaxError
            },
            columns: [
                { 
                    data: "id",
                    title: "ID",
                    width: "60px"
                },
                { 
                    data: "name",
                    title: "Name"
                },
                { 
                    data: "category_type",
                    title: "Type"
                },
                { 
                    data: "parent_category", 
                    title: "Parent Category",
                    defaultContent: "-",
                    orderable: true
                },
                { 
                    data: "description",
                    title: "Description",
                    render: function(data, type, row) {
                        if (type === 'display' && data && data.length > 50) {
                            return `<span title="${data}">${data.substring(0, 50)}...</span>`;
                        }
                        return data || '-';
                    }
                },
                {
                    data: "status",
                    title: "Status",
                    render: function (data, type, row) {
                        return _renderStatusBadge(data, type, row);
                    },
                },
                {
                    data: null,
                    title: "Actions",
                    orderable: false,
                    searchable: false,
                    className: "text-center",
                    width: "100px",
                    render: function (data, type, row) {
                        return _renderActionDropdown(row);
                    },
                },
            ],
            orderCellsTop: true,
            initComplete: function () {
                _attachSearchEvents(this.api());
            },
            drawCallback: function(settings) {
                // Re-initialize Bootstrap dropdowns after each draw
                const dropdowns = document.querySelectorAll('[data-bs-toggle="dropdown"]');
                dropdowns.forEach(dropdown => {
                    if (!bootstrap.Dropdown.getInstance(dropdown)) {
                        new bootstrap.Dropdown(dropdown);
                    }
                });
            }
        });

        _setupColumnSearchInputs();

        return dataTable;
    };

    const _componentDatatableAPI = function () {
        if (!_setDataTableDefaults()) return;
        return _initializeDataTable();
    };

    // Public API
    return {
        init: function () {
            return _componentDatatableAPI();
        },

        // Expose methods for external use
        renderStatusBadge: _renderStatusBadge,
        renderActionDropdown: _renderActionDropdown,
        
        // Utility methods
        refreshTable: function() {
            const table = $('.datatable-column-search-inputs').DataTable();
            if (table) {
                table.ajax.reload(null, false); // false = keep current page
            }
        },
        
        clearSearch: function() {
            const table = $('.datatable-column-search-inputs').DataTable();
            if (table) {
                table.search('').columns().search('').draw();
            }
        },

        // Get table instance
        getTable: function() {
            return $('.datatable-column-search-inputs').DataTable();
        }
    };
})();

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", function () {
    // Store table instance globally if needed
    window.complaintCategoryTable = DatatableAPI.init();
});

// Export for potential module use
if (typeof module !== "undefined" && module.exports) {
    module.exports = DatatableAPI;
}

// Global utility functions (optional)
window.refreshComplaintCategoryTable = function() {
    DatatableAPI.refreshTable();
};

window.clearComplaintCategorySearch = function() {
    DatatableAPI.clearSearch();
};