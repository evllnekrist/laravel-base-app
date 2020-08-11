$(document).ready(function() {
    /*** COLUMN DEFINE ***/
    var columnDefs = [
        {
            headerName: "First Name",
            field: "first_name",
            editable: true,
            sortable: true,
            width: 225,
            filter: true,
            checkboxSelection: true,
            headerCheckboxSelectionFilteredOnly: true,
            headerCheckboxSelection: true
        },
        {
            headerName: "Last Name",
            field: "last_name",
            editable: true,
            sortable: true,
            filter: true,
            width: 225
        },
        {
            headerName: "Phone",
            field: "phone",
            editable: true,
            sortable: true,
            filter: true,
            width: 225
        },
        {
            headerName: "Phone Alt",
            field: "phone_alt",
            editable: true,
            sortable: true,
            filter: true,
            width: 225
        },
        {
            headerName: "Is Active",
            field: "is_active",
            editable: false,
            sortable: true,
            filter: true,
            width: 225
        },
        {
            headerName: "Joined At",
            field: "created_at",
            editable: false,
            sortable: true,
            filter: true,
            width: 300
        },
        {
            headerName: "Email",
            field: "email",
            editable: false,
            sortable: true,
            filter: true,
            width: 350,
            pinned: "left"
        },
        {
            headerName: "Action",
            field: 'action',
            cellRenderer: 'btnCellRenderer',
            cellRendererParams: {
                clicked: function(data) {
                    showCustomerDetail(data);
                }
            },
            width: 100,
            pinned: "left",
        },
    ];

    /*** GRID OPTIONS ***/
    var gridOptions = {
        columnDefs: columnDefs,
        rowSelection: "multiple",
        floatingFilter: true,
        filter: true,
        pagination: true,
        paginationPageSize: 20,
        pivotPanelShow: "always",
        colResizeDefault: "shift",
        animateRows: true,
        resizable: true,
        onCellValueChanged:function(data){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "You are not permitted to make this change, so changes that you make will not be updated in the database!",
                type: "error",
                buttonsStyling: false,
                confirmButtonClass: "btn btn-error"
            });
        },
        components: {
            btnCellRenderer: BtnCellRenderer
        }
    };

    function showCustomerDetail(data){
        $("#customer-details-modal").modal('show');
        $("#customer-details-modal-title").text(data.first_name.toString()+' '+data.last_name.toString());
        $("#customer-details-modal-body").html($("#loading").html());
        $.ajax({
            url: 'customer/' + data.email,
            type: "GET",
            data: {
                type: "show_customer_detail"
            },
            success: (function (view) {

                $("#customer-details-modal-body").html(view);

            }),error:function(xhr,status,error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: xhr.responseText,
                    type: "error",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-error"
                });
            }
        })
    }

    function getSelectedRows() {
        const selectedNodes = gridOptions.api.getSelectedNodes();
        const selectedData = selectedNodes.map( function(node) { return node.data });
        return selectedData;
    }

    function BtnCellRenderer() {}

    BtnCellRenderer.prototype.init = function(params) {
        this.params = params;

        this.eGui = document.createElement('a');
        this.eGui.innerHTML = 'Details';

        this.btnClickedHandler = this.btnClickedHandler.bind(this);
        this.eGui.addEventListener('click', this.btnClickedHandler);
    }

    BtnCellRenderer.prototype.getGui = function() {
        return this.eGui;
    }

    BtnCellRenderer.prototype.destroy = function() {
        this.eGui.removeEventListener('click', this.btnClickedHandler);
    }

    BtnCellRenderer.prototype.btnClickedHandler = function(event) {
        event.stopPropagation();
        this.params.clicked(this.params.data);
    }

    $(function() {
        $.contextMenu({
            selector: '.ag-cell:not([col-id="action"]):not(:empty)',
            callback: function(key, options) {
                if(key==='copy'){
                    copyToClipboard($(this).text());
                }
            },
            items: {
                copy: {name: "Copy"},
            }
        });
    });

    function copyToClipboard(str){
        // Create new element
        var el = document.createElement('textarea');
        // Set value (string to be copied)
        el.value = str;
        // Set non-editable to avoid focus and move outside of view
        el.setAttribute('readonly', '');
        el.style = {position: 'absolute', left: '-9999px'};
        document.body.appendChild(el);
        // Select text inside element
        el.select();
        // Copy text to clipboard
        document.execCommand('copy');
        // Remove temporary element
        document.body.removeChild(el);
    }

    /*** DEFINED TABLE VARIABLE ***/
    var gridTable = document.getElementById("myGrid");

    /*** GET TABLE DATA FROM URL ***/

    agGrid
        .simpleHttpRequest({ url: "customer/create?csrf-token="+$('meta[name="csrf-token"]').attr('content') })
        .then(function(data) {
            gridOptions.api.setRowData(data.customer_model);
            $(".filter-btn").text("1 - " + gridOptions.api.paginationGetPageSize()  + " of "+gridOptions.api.getModel().getRowCount());
        });

    /*** FILTER TABLE ***/
    function updateSearchQuery(val) {
        gridOptions.api.setQuickFilter(val);
    }

    $(".ag-grid-filter").on("keyup", function() {
        updateSearchQuery($(this).val());
    });

    /*** CHANGE DATA PER PAGE ***/
    function changePageSize(value) {
        gridOptions.api.paginationSetPageSize(Number(value));
    }

    $(".sort-dropdown .dropdown-item").on("click", function() {
        var $this = $(this);
        changePageSize($this.text());
        $(".filter-btn").text("1 - " + $this.text() + " of "+gridOptions.api.getModel().getRowCount());
    });

    /*** EXPORT AS CSV BTN ***/
    $(".ag-grid-export-btn").on("click", function(params) {
        if(getSelectedRows().length){
            gridOptions.api.exportDataAsCsv({
                columnKeys: [
                    'first_name',
                    'last_name',
                    'email',
                    'phone',
                    'phone_alt',
                    'card_id',
                    'is_active',
                    'created_at'
                ],
                onlySelected: true,
                allColumns: false,
                fileName: 'Customer Export at'+new Date()+'.csv',
                skipHeader: false,
                // customHeader: 'Customer List' + '\n',
                // customFooter: '\n \n Total No.Of Customers :' + gridOptions.api.getModel().getRowCount() + ' \n'
            });
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "It seems that you have not selected the data to export!",
                type: "error",
                buttonsStyling: false,
                confirmButtonClass: "btn btn-error"
            });
        }
    });

    /*** INIT TABLE ***/
    new agGrid.Grid(gridTable, gridOptions);

    /*** SET OR REMOVE EMAIL AS PINNED DEPENDING ON DEVICE SIZE ***/

    if ($(window).width() < 768) {
        gridOptions.columnApi.setColumnPinned("email", null);
        gridOptions.columnApi.setColumnPinned("action", null);
    } else {
        gridOptions.columnApi.setColumnPinned("email", "left");
        gridOptions.columnApi.setColumnPinned("action", "left");
    }
    $(window).on("resize", function() {
        if ($(window).width() < 768) {
            gridOptions.columnApi.setColumnPinned("email", null);
            gridOptions.columnApi.setColumnPinned("action", null);
        } else {
            gridOptions.columnApi.setColumnPinned("email", "left");
            gridOptions.columnApi.setColumnPinned("action", "left");
        }
    });
});
