
const DatatableAPI = function() {

    const _componentDatatableAPI = function() {
        if (!$().DataTable) {
            console.warn('Warning - datatables.min.js is not loaded.');
            return;
        }

        $.extend($.fn.dataTable.defaults, {
            autoWidth: false,
            columnDefs: [{ 
                orderable: false,
                width: 100,
                targets: [5]
            }],
            dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            language: {
                search: '<span class="me-3">Filter:</span> <div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
            }
        });

        $('.datatable-column-search-inputs').DataTable({
            ajax: '/api/complaint-categories', 
            columns: [
                { data: 'id' },
                { data: 'category_name' },
                { data: 'parent_category', defaultContent: '-' },
                { data: 'description' },
                {
                    data: 'status',
                    render: function(data, type, row) {
                        if (data === 'Active') {
                            return '<span class="badge bg-success bg-opacity-10 text-success">Active</span>';
                        } else {
                            return '<span class="badge bg-danger bg-opacity-10 text-danger">Inactive</span>';
                        }
                    }
                },
                {                                                                                                           
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `
                        <div class="d-inline-flex">
                            <div class="dropdown">
                                <a href="#" class="text-body" data-bs-toggle="dropdown">
                                    <i class="ph-list"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="/categories/${row.id}" class="dropdown-item">
                                        <i class="ph-eye me-2"></i>View
                                    </a>
                                    <a href="/categories/${row.id}/edit" class="dropdown-item">
                                        <i class="ph-pencil me-2"></i>Edit
                                    </a>
                                    <a href="#" class="dropdown-item text-danger" data-id="${row.id}" onclick="deleteCategory(${row.id})">
                                        <i class="ph-trash me-2"></i>Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                        `;
                    }
                }
            ],
            orderCellsTop: true,
            initComplete: function () {
                this.api()
                    .columns()
                    .every(function (index) {
                        if(index < 5) { 
                            let column = this;
                            $('input', $('.datatable-column-search-inputs thead tr:eq(1) th').eq(index)).on('keyup change clear', function () {
                                if (column.search() !== this.value) {
                                    column.search(this.value).draw();
                                }
                            });
                        }
                    });
            }
        });

        $('.datatable-column-search-inputs thead tr:eq(1) th').not(':last-child').each(function () {
            const title = $(this).text();
            $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" />');
        });
    };

    return {
        init: function() {
            _componentDatatableAPI();
        }
    }
}();

document.addEventListener('DOMContentLoaded', function() {
    DatatableAPI.init();
});
