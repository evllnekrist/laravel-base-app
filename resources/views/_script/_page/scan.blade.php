<script type="text/javascript">
    $(document).ready(function() {
        /*** COLUMN DEFINE ***/
        var columnDefs = [
            {
                headerName: "Time",
                field: "created_at",
                editable: false,
                sortable: true,
                width: 180,
                filter: true,
            },
            {
                headerName: "First Name",
                field: "member.first_name",
                editable: false,
                sortable: true,
                width: 180,
                filter: true,
            },
            {
                headerName: "Last Name",
                field: "member.last_name",
                editable: false,
                sortable: true,
                width: 180,
                filter: true,
            },
            {
                headerName: "Card ID",
                field: "card_id",
                editable: false,
                sortable: true,
                filter: true,
                width: 140,
                pinned: "left"
            },
            {
                headerName: "Transaction",
                field: "transaction.name",
                editable: false,
                sortable: true,
                filter: true,
                width: 140,
                pinned: "left"
            },
            {
                headerName: "Status",
                field: "member.status_code",
                editable: false,
                sortable: true,
                filter: true,
                width: 140,
                pinned: "left"
            },
            // {
            //     headerName: "Action",
            //     field: 'action',
            //     cellRenderer: 'btnCellRenderer',
            //     cellRendererParams: {
            //         clicked: function(data) {
            //             // console.log(data);
            //             detailEdit(data);
            //         }
            //     },
            //     width: 100,
            //     pinned: "left",
            // },
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
                showSweetAlert('error','','You are not permitted to make this change, so changes that you make will not be updated in the database!');
            },
            components: {
                btnCellRenderer: BtnCellRenderer
            }
        };

        // function detailEdit(data){
        //     $("#button-edit,#button-close").removeClass("hidden");
        //     $("#button-update,#button-delete,#button-cancel,#button-edit").addClass("hidden");
        //     $("#admin-details-modal").modal('show');
        //     $("#admin-details-modal-title").text(data.card_id.toString()+' - '+data.first_name.toString()+'  '+data.last_name.toString());
        //     $("#admin-details-modal-body").html($("#loading").html());
        //     $.ajax({
        //         url: 'scan/' + data.id + '/detailEdit',
        //         type: "GET",
        //         success: (function (view) {
        //             $("#admin-details-modal-body").html(view);
        //             $("#button-edit").removeClass("hidden");
        //             $("#role_add_selector,#status_add_selector,#gender_add_selector").select2({
        //                 minimumResultsForSearch: 0,
        //                 tokenSeparators: [',', ' ', '.', '/', '\\','[',']',';','\'','{','}','_','+','=','|','"',":"]
        //             });

        //         }),error:function(xhr,status,error) {
        //             showSweetAlert('error','',xhr.responseText);
        //         }
        //     })
        // }

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
            .simpleHttpRequest({ url: "{{ url('scan/get') }}" })
            .then(function(data) {
                gridOptions.api.setRowData(data.list_data);
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
                let date = new Date();
                let date_str = date.getFullYear()+'_'+date.getMonth()+'_'+date.getDate()+'_'+date.getHours()+'_'+date.getMinutes()+'_'+date.getSeconds();
                gridOptions.api.exportDataAsCsv({
                    columnKeys: [
                        'created_at',
                        'card_id',
                        'transaction.name',
                    ],
                    onlySelected: true,
                    allColumns: false,
                    fileName: 'export_activity__at__'+date_str+'.csv',
                    skipHeader: false,
                    // customHeader: 'admin List' + '\n',
                    // customFooter: '\n \n Total No.Of admins :' + gridOptions.api.getModel().getRowCount() + ' \n'
                });
            }else{
                showSweetAlert('error','','It seems that you have not selected the data to export!');
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
            $("#role_add_selector,#status_add_selector,#gender_add_selector").select2({
                minimumResultsForSearch: 0,
                tokenSeparators: [',', ' ', '.', '/', '\\','[',']',';','\'','{','}','_','+','=','|','"',":"]
            });
        }).on("click","#button-edit",function(){
            $("#button-update,#button-delete,#button-cancel,.data-edit").removeClass("hidden");
            $("#button-edit,#button-close,.data-info").addClass("hidden");
        }).on("click","#button-cancel",function(){
            $("#button-edit,#button-close,.data-info").removeClass("hidden");
            $("#button-update,#button-delete,#button-cancel,.data-edit").addClass("hidden");
        }).on("click","#button-add-save",function(){
            if($("#addForm")[0].checkValidity()) {
                $("#button-add-save").val("Loading...");
                $("#addForm").ajaxSubmit({
                    url: 'scan/doAdd',
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    type: 'post',
                    success:function(data){
                        if(data.status){
                            $("#admin-add-modal").modal("hide");
                            agGrid
                                .simpleHttpRequest({ url: "{{ url('scan/get') }}" })
                                .then(function(data) {
                                    gridOptions.api.setRowData(data.list_data);
                                    $(".filter-btn").text("1 - " + gridOptions.api.paginationGetPageSize()  + " of "+gridOptions.api.getModel().getRowCount());
                                });
                            $('#addForm')[0].reset();
                            $("#button-add-save").val("Save");
                            showSweetAlert('success','',data.message);
                        }else{
                            $("#button-add-save").val("Save");
                            showSweetAlert('error','',data.message);
                            return false;
                        }
                    },
                    error:function(xhr, status, error){
                        $("#button-add-save").val("Save");
                        showSweetAlert('error','',xhr.responseText);
                    }
                });
            }
        }).on("click","#button-update",function () {
            if($("#updateForm")[0].checkValidity()) {
                $("#button-update").val("Updating...");
                $("#updateForm").ajaxSubmit({
                    url: 'scan/doEdit',
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    type: 'post',
                    success:function(data){
                        if(data.status){
                            $("#admin-details-modal").modal("hide");
                            agGrid
                                .simpleHttpRequest({ url: "{{ url('scan/get') }}" })
                                .then(function(data) {
                                    gridOptions.api.setRowData(data.list_data);
                                    $(".filter-btn").text("1 - " + gridOptions.api.paginationGetPageSize()  + " of "+gridOptions.api.getModel().getRowCount());
                                });
                            $('#updateForm')[0].reset();
                            $("#button-update").val("Update");
                            showSweetAlert('success','',data.message);
                        }else{
                            $("#button-update").val("Update");
                            showSweetAlert('error','',data.message);
                            return false;
                        }
                    },
                    error:function(xhr, status, error){
                        $("#button-update").val("Update");
                        showSweetAlert('error','',xhr.responseText);
                    }
                });
            }
        }).on("click","#button-delete",function () {

            if(confirm("This action will permanently remove the member, are you sure?")){
                let hash = $(this).data("hash");
                $("#button-delete").val("Deleting...");
                $("#updateForm").ajaxSubmit({
                    url: 'scan/'+hash+'/delete',
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    type: 'delete',
                    success:function(data){
                        if(data.status){
                            $("#admin-details-modal").modal("hide");
                            agGrid
                                .simpleHttpRequest({ url: "{{ url('scan/get') }}" })
                                .then(function(data) {
                                    gridOptions.api.setRowData(data.list_data);
                                    $(".filter-btn").text("1 - " + gridOptions.api.paginationGetPageSize()  + " of "+gridOptions.api.getModel().getRowCount());
                                });
                            $('#updateForm')[0].reset();
                            $("#button-delete").val("Delete");
                            showSweetAlert('success','',data.message);
                        }else{
                            $("#button-delete").val("Delete");
                            showSweetAlert('error','',data.message);
                            return false;
                        }
                    },
                    error:function(xhr, status, error){
                        $("#button-delete").val("Delete");
                        showSweetAlert('error','',xhr.responseText);
                    }
                });
            }else{}

        });

        function showSweetAlert(type='success',title='',message){
            if(type=='success'){
                Swal.fire({
                    title: (title?title:"Success!"),
                    text: message,
                    type: "success",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-success"
                });
            }else if(type=='error'){
                Swal.fire({
                    icon: 'error',
                    title: (title?title:"Oops..."),
                    html: message,
                    type: "error",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-error"
                });
            }
        }

        // ----------------------------------------- custom ----------------------
        $('[name="card_id"]').on('input', function() {
            let card_id = this.value;        

            $('#addForm_info').removeClass("ft-warn");
            $('#addForm_info').html("");
            if(card_id.length >= 12){
                if(card_id.length > 12){ 
                    $('#addForm_info').addClass("ft-warn");
                    $('#addForm_info').html("* Card Id should not be more than 12 digits long<br>");
                }else{
                    console.log("detecting card_id >> ",card_id);
                    
                    $("#addForm").ajaxSubmit({
                        url: 'scan/doAdd',
                        headers: {
                            'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                        },
                        type: 'post',
                        success:function(data){
                            $('#addDetail').show();
                            if(data.status){
                                console.log(data.detail);
                                if(data.detail){
                                    $('#attende_card_id').html(data.detail.card_id);
                                    $('#attende_name').html(data.detail.first_name+" "+data.detail.last_name);
                                    $('#attende_role').html(data.detail.role.name);
                                    $('#attende_role_color').css("background-color", data.detail.role.color_sign);
                                    $('#attende_status').html(data.detail.status.name);
                                    $('#attende_status_color').css("background-color", data.detail.status.color_sign);
                                    if(data.detail.member_role_id == 1){ // member
                                        $('#attende_package_area').show();
                                        $('#attende_package').html(data.detail.package_name);
                                        if(data.detail.end_at){
                                            $('#attende_package_end_date').html(data.detail.end_at.split(" ")[0]);
                                        }
                                    }else{ // other
                                        $('#attende_package_area').hide();
                                    }
                                }
                                agGrid
                                    .simpleHttpRequest({ url: "{{ url('scan/get') }}" })
                                    .then(function(data) {
                                        gridOptions.api.setRowData(data.list_data);
                                        $(".filter-btn").text("1 - " + gridOptions.api.paginationGetPageSize()  + " of "+gridOptions.api.getModel().getRowCount());
                                    });
                                $('[name="card_id"]').val('');
                            }else{
                                $('[name="card_id"]').val('');
                                showSweetAlert('error','',data.message);
                                return false;
                            }
                        },
                        error:function(xhr, status, error){
                            $('[name="card_id"]').val('');
                            showSweetAlert('error','',xhr.responseText);
                        }
                    });
                }
            }
        });
        // ----------------------------------------- custom --- end --------------

    });
</script>