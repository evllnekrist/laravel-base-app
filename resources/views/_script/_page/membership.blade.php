<script type="text/javascript">
    $(document).ready(function() {
        /*** COLUMN DEFINE ***/
        var columnDefs = [
            {
                headerName: "First Name",
                field: "first_name",
                editable: true,
                sortable: true,
                width: 300,
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
                width: 300
            },
            {
                headerName: "Status",
                field: "status",
                editable: false,
                sortable: true,
                filter: true,
                width: 200
            },
            {
                headerName: "Gender",
                field: "gender",
                editable: false,
                sortable: true,
                filter: true,
                width: 200
            },
            {
                headerName: "Role",
                field: "role",
                editable: false,
                sortable: true,
                filter: true,
                width: 200
            },
            {
                headerName: "Email",
                field: "email",
                editable: false,
                sortable: true,
                filter: true,
                width: 325
            },
            {
                headerName: "Phone",
                field: "phone",
                editable: false,
                sortable: true,
                filter: true,
                width: 250
            },
            {
                headerName: "Address",
                field: "address",
                editable: false,
                sortable: true,
                filter: true,
                width: 325
            },
            {
                headerName: "POB",
                field: "pob",
                editable: false,
                sortable: true,
                filter: true,
                width: 250,
            },
            {
                headerName: "DOB",
                field: "dob",
                editable: false,
                sortable: true,
                filter: true,
                width: 325,
            },
            {
                headerName: "KTP",
                field: "ktp_number",
                editable: false,
                sortable: true,
                filter: true,
                width: 200
            },
            {
                headerName: "Card ID",
                field: "card_id",
                editable: false,
                sortable: true,
                filter: true,
                width: 250,
                pinned: "left"
            },
            {
                headerName: "Active",
                field: "active",
                editable: false,
                sortable: true,
                filter: true,
                width: 125
            },
            {
                headerName: "Action",
                field: 'action',
                cellRenderer: 'btnCellRenderer',
                cellRendererParams: {
                    clicked: function(data) {
                        showadminDetail(data);
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
            paginationPageSize: 10,
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

        function showadminDetail(data){
            $("#button-edit,#button-close").removeClass("hidden");
            $("#button-update,#button-cancel,#button-edit").addClass("hidden");
            $("#admin-details-modal").modal('show');
            $("#admin-details-modal-title").text(data.name.toString()+' '+data.nik.toString());
            $("#admin-details-modal-body").html($("#loading").html());
            $.ajax({
                url: 'membership/' + data.username,
                type: "GET",
                data: {
                    type: "show_admin_detail"
                },
                success: (function (view) {

                    $("#admin-details-modal-body").html(view);
                    $("#button-edit").removeClass("hidden");
                    $("#site_code_selector,#privilege_selector,#division_selector,#is_enabled_selector").select2({
                        minimumResultsForSearch: -1,
                        tokenSeparators: [',', ' ', '.', '/', '\\','[',']',';','\'','{','}','_','+','=','|','"',":"]
                    });

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
            .simpleHttpRequest({ url: "{{ url('membership/get') }}" })
            .then(function(data) {
                gridOptions.api.setRowData(data.list_of_data);
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
                        'nik',
                        'name',
                        'username',
                        'is_enabled',
                        'created_at'
                    ],
                    onlySelected: true,
                    allColumns: false,
                    fileName: 'admin Export at'+new Date()+'.csv',
                    skipHeader: false,
                    // customHeader: 'admin List' + '\n',
                    // customFooter: '\n \n Total No.Of admins :' + gridOptions.api.getModel().getRowCount() + ' \n'
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
            gridOptions.columnApi.setColumnPinned("card_id", null);
            gridOptions.columnApi.setColumnPinned("action", null);
        } else {
            gridOptions.columnApi.setColumnPinned("card_id", "left");
            gridOptions.columnApi.setColumnPinned("action", "left");
        }
        $(window).on("resize", function() {
            if ($(window).width() < 768) {
                gridOptions.columnApi.setColumnPinned("card_id", null);
                gridOptions.columnApi.setColumnPinned("action", null);
            } else {
                gridOptions.columnApi.setColumnPinned("card_id", "left");
                gridOptions.columnApi.setColumnPinned("action", "left");
            }
        });

        $(document).on("click","#add-data",function(){
            $("#admin-add-modal").modal('show');
            $("#site_code_add_selector,#privilege_add_selector,#division_add_selector,#is_enabled_add_selector").select2({
                minimumResultsForSearch: -1,
                tokenSeparators: [',', ' ', '.', '/', '\\','[',']',';','\'','{','}','_','+','=','|','"',":"]
            });
        }).on("click","#button-edit",function(){
            $("#button-update,#button-cancel,.data-edit").removeClass("hidden");
            $("#button-edit,#button-close,.data-info").addClass("hidden");
        }).on("click","#button-cancel",function(){
            $("#button-edit,#button-close,.data-info").removeClass("hidden");
            $("#button-update,#button-cancel,.data-edit").addClass("hidden");
        }).on("click","#button-update",function () {
            let hash = $(this).data("hash");
            if($("#updateForm")[0].checkValidity()) {
                $("#button-update").val("Updating...");
                $("#updateForm").ajaxSubmit({
                    url: 'membership/'+hash,
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    type: 'put',
                    success:function(data){
                        if($.trim(data)==="SUCCESS"){
                            $("#admin-details-modal").modal("hide");
                            agGrid
                                .simpleHttpRequest({ url: "{{ url('membership/get') }}" })
                                .then(function(data) {
                                    gridOptions.api.setRowData(data.list_of_data);
                                    $(".filter-btn").text("1 - " + gridOptions.api.paginationGetPageSize()  + " of "+gridOptions.api.getModel().getRowCount());
                                });
                            $('#updateForm')[0].reset();
                            $("#button-update").val("Update");
                            Swal.fire({
                                title:"Success!",
                                text: "Yeah, data was updated successfully",
                                type: "success",
                                buttonsStyling: false,
                                confirmButtonClass: "btn btn-success"
                            });
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: data,
                                type: "error",
                                buttonsStyling: false,
                                confirmButtonClass: "btn btn-error"
                            });
                            $("#button-update").val("Update");
                            return false;
                        }
                    },
                    error:function(xhr, status, error){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: xhr.responseText,
                            type: "error",
                            buttonsStyling: false,
                            confirmButtonClass: "btn btn-error"
                        });
                        $("#button-update").val("Update");
                    }
                });
            }
        }).on("click","#button-add-save",function(){
            if($("#addForm")[0].checkValidity()) {
                $("#button-add-save").val("Loading...");
                $("#addForm").ajaxSubmit({
                    url: 'admin',
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    type: 'post',
                    success:function(data){
                        if($.trim(data)==="SUCCESS"){
                            $("#admin-add-modal").modal("hide");
                            agGrid
                                .simpleHttpRequest({ url: "{{ url('membership/get') }}" })
                                .then(function(data) {
                                    gridOptions.api.setRowData(data.list_of_data);
                                    $(".filter-btn").text("1 - " + gridOptions.api.paginationGetPageSize()  + " of "+gridOptions.api.getModel().getRowCount());
                                });
                            $('#addForm')[0].reset();
                            $("#button-add-save").val("Save");
                            Swal.fire({
                                title:"Success!",
                                text: "Yeah, data was updated successfully",
                                type: "success",
                                buttonsStyling: false,
                                confirmButtonClass: "btn btn-success"
                            });
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: data,
                                type: "error",
                                buttonsStyling: false,
                                confirmButtonClass: "btn btn-error"
                            });
                            $("#button-add-save").val("Save");
                            return false;
                        }
                    },
                    error:function(xhr, status, error){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: xhr.responseText,
                            type: "error",
                            buttonsStyling: false,
                            confirmButtonClass: "btn btn-error"
                        });
                        $("#button-add-save").val("Save");
                    }
                });
            }
        });
    });
</script>