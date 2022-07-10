<script type="text/javascript">
    $(document).ready(function() {

        $('#province_add_selector,#province_edit_selector').on('change', function() {
            onChange_Province(this);
        });
        $('#regency_add_selector,#regency_edit_selector').on('change', function() {
            onChange_Regency(this); 
        });
        $('#district_add_selector,#district_edit_selector').on('change', function() {
            onChange_District(this); 
        });
        $('#role_add_selector,#role_edit_selector').on('change', function() {
            onChange_Role(this,0); 
        });
        $('#start_at_add_selector_date,#start_at_edit_selector_date').change(function() {
            onChange_StartAt(this); 
        });
        $('#site_add_selector,#site_edit_selector').on('change', function() {
            onChange_Site(this); 
        });
        $('#package_add_selector,#package_edit_selector').on('change', function() {
            onChange_Package(this); 
        });

        function onChange_Province(el){ 
            let item = el.value;
            let fellow_id = "regency"+$(el).attr("data-fellow");
            $.ajax({
                url: 'selection/regency',
                headers: {
                    'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                },
                type: 'POST',
                data: JSON.stringify({
                    id: item
                }),
                contentType: 'application/json; charset=utf-8',
                success: (function (data) {
                    if(data.status){
                        let fellow_el = $('#'+fellow_id+'');
                        fellow_el.empty();
                        fellow_el.append('<option value="" disabled selected>----- select an option -----</option>');
                        for (var i = 0; i < data.detail.length; i++) {
                            fellow_el.append('<option value='+data.detail[i].id+'>'+data.detail[i].name+'</option>');
                        }
                    }else{
                        showSweetAlert('error','',data.message);
                        return false;
                    }
                }),error:function(xhr,status,error) {
                    showSweetAlert('error','',xhr.responseText);
                }
            });
        }
        function onChange_Regency(el){
            let item = el.value;
            let fellow_id = "district"+$(el).attr("data-fellow");
            $.ajax({
                url: 'selection/district',
                headers: {
                    'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                },
                type: 'POST',
                data: JSON.stringify({
                    id: item
                }),
                contentType: 'application/json; charset=utf-8',
                success: (function (data) {
                    if(data.status){
                        let fellow_el = $('#'+fellow_id+'');
                        fellow_el.empty();
                        fellow_el.append('<option value="" disabled selected>----- select an option -----</option>');
                        for (var i = 0; i < data.detail.length; i++) {
                            fellow_el.append('<option value='+data.detail[i].id+'>'+data.detail[i].name+'</option>');
                        }
                    }else{
                        showSweetAlert('error','',data.message);
                        return false;
                    }
                }),error:function(xhr,status,error) {
                    showSweetAlert('error','',xhr.responseText);
                }
            });
        }
        function onChange_District(el){
            let item = el.value;
            let fellow_id = "village"+$(el).attr("data-fellow");
            $.ajax({
                url: 'selection/village',
                headers: {
                    'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                },
                type: 'POST',
                data: JSON.stringify({
                    id: item
                }),
                contentType: 'application/json; charset=utf-8',
                success: (function (data) {
                    if(data.status){
                        let fellow_el = $('#'+fellow_id+'');
                        fellow_el.empty();
                        fellow_el.append('<option value="" disabled selected>----- select an option -----</option>');
                        for (var i = 0; i < data.detail.length; i++) {
                            fellow_el.append('<option value='+data.detail[i].village_id+'>'+data.detail[i].name+'</option>');
                        }
                    }else{
                        showSweetAlert('error','',data.message);
                        return false;
                    }
                }),error:function(xhr,status,error) {
                    showSweetAlert('error','',xhr.responseText);
                }
            });
        }
        function onChange_Role(el,pass=0){
            let item = el.value;
            let fellow_suffix = $(el).attr("data-fellow");

            if(item == 1){ // member
                $('[name="site_code"]').attr('required','true');
                $('[name="package_id"]').attr('required','true');
                $('[name="start_at"]').attr('required','true');
                $('[name="end_at"]').attr('required','true');
                $('#subscription_title'+fellow_suffix).show();
                $('#subscription_content'+fellow_suffix).show();
            }else{ // other
                $('[name="site_code"]').removeAttr('required');
                $('[name="package_id"]').removeAttr('required');
                $('[name="start_at"]').removeAttr('required');
                $('[name="end_at"]').removeAttr('required');
                $('#subscription_title'+fellow_suffix).hide();
                $('#subscription_content'+fellow_suffix).hide();
            }
            
            if(pass == 0){
                let fellow_id = "status"+fellow_suffix;
                $.ajax({
                    url: 'selection/status-membership',
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    type: 'POST',
                    data: JSON.stringify({
                        id: item
                    }),
                    contentType: 'application/json; charset=utf-8',
                    success: (function (data) {
                        if(data.status){
                            let attr_el = '';
                            let fellow_el = $('#'+fellow_id+'');
                            fellow_el.empty();
                            fellow_el.append('<option value="" disabled selected>----- select an option -----</option>');
                            for (var i = 0; i < data.detail.length; i++) {
                                if(fellow_suffix == '_add_selector'){
                                    if((item==1&&data.detail[i].code=='sub')||(item!=1&&data.detail[i].code=='set')){
                                        attr_el = 'selected';
                                    }else{
                                        attr_el = '';
                                    }
                                }else{
                                    attr_el = '';
                                }
                                fellow_el.append('<option value='+data.detail[i].code+' '+attr_el+'>'+data.detail[i].name+'</option>');
                            }
                        }else{
                            showSweetAlert('error','',data.message);
                            return false;
                        }
                    }),error:function(xhr,status,error) {
                        showSweetAlert('error','',xhr.responseText);
                    }
                });
            }
        }
        function onChange_Site(el){
            let item = el.value;
            let fellow_id = "package"+$(el).attr("data-fellow");
            $.ajax({
                url: 'selection/package',
                headers: {
                    'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                },
                type: 'POST',
                data: JSON.stringify({
                    site_code: item
                }),
                contentType: 'application/json; charset=utf-8',
                success: (function (data) {
                    if(data.status){
                        let fellow_el = $('#'+fellow_id+'');
                        fellow_el.empty();
                        fellow_el.append('<option value="" disabled selected>----- select an option -----</option>');
                        for (var i = 0; i < data.detail.length; i++) {
                            fellow_el.append('<option value="'+data.detail[i].id+'" data-duration='+data.detail[i].duration+'>'+data.detail[i].name+'</option>');
                        }
                    }else{
                        showSweetAlert('error','',data.message);
                        return false;
                    }
                }),error:function(xhr,status,error) {
                    showSweetAlert('error','',xhr.responseText);
                }
            });
        }
        function onChange_Package(el){
            let fellow_suffix = $(el).attr("data-fellow");
            let duration = $('#package'+fellow_suffix).find(':selected').attr('data-duration');

            if(duration && $('#start_at'+fellow_suffix+'_date').val()){
                let date_after = setPackageEndDate($('#start_at'+fellow_suffix+'_date').val(), duration);
                $('#end_at'+fellow_suffix+'_date').val(date_after);
            }else{
                $('#start_at'+fellow_suffix+'_date').val('');
                $('#end_at'+fellow_suffix+'_date').val('');
            }
        }
        function onChange_StartAt(el){
            let fellow_suffix = $(el).attr("data-fellow");
            let duration = $('#package'+fellow_suffix).find(':selected').attr('data-duration');
            if(duration){
                let date_after = setPackageEndDate($(el).val(), duration);
                $('#end_at'+fellow_suffix+'_date').val(date_after);
            }else{
                alert('please select site and package first!');
                $(el).val('');
                $('#end_at'+fellow_suffix+'_date').val('');
            }
        }
        function setPackageEndDate(current_date,duration){
            moment().locale("Asia/Jakarta");
            let after_date = moment(current_date,'YYYY-MM-DD').add(duration, 'M').format();
            let after_date_slice = after_date.split('T');
            console.log(current_date,' ---- '+duration+ 'mo ---- ',after_date_slice);
            return after_date_slice[0];
        }

        // function goTabAction(step){
        //     if(step <= 3 ){
        //         $('#tab-progress').val(step);

        //         if(step == 1){
        //             $('#button-add-prev').hide(); 
        //             $('#button-add-next').show(); 
        //             $('#button-add-save').hide();

        //             $('#tab-1').addClass('active');
        //             $('#tab-2').removeClass('active');
        //             $('#tab-1-tab').addClass('active');
        //             $('#tab-2-tab').removeClass('active');
        //         }else if(step == 2){
        //             $('#button-add-prev').show(); 
        //             $('#button-add-next').hide(); 
        //             $('#button-add-save').show();

        //             $('#tab-1').removeClass('active');
        //             $('#tab-2').addClass('active');
        //             $('#tab-1-tab').removeClass('active');
        //             $('#tab-2-tab').addClass('active');

        //             if($('[name="member_role_id"]').val() && $('[name="member_role_id"]').val() > 1){
        //                 $('#subscription-member').hide();
        //                 $('#subscription-other').show();
        //             }else{
        //                 $('#subscription-member').show();
        //                 $('#subscription-other').hide();
        //             }
        //         }
        //     }
        // }

        // $('.tab-controll').click(function(){
        //     let aim = $(this).attr('data-aim');
        //     let tab_progress = parseInt($('#tab-progress').val());
        //     let tab_progress_now = 1;
        //     if(aim == 'next' && tab_progress < 2){
        //         tab_progress_now = tab_progress+1;
        //     }else if(aim == 'prev' && tab_progress > 1){
        //         tab_progress_now = tab_progress-1;
        //     }
        //     goTabAction(tab_progress_now);
        // });

        // $('#tab-1-tab').click(function(){
        //     goTabAction(1);
        // });
        // $('#tab-2-tab').click(function(){
        //     goTabAction(2);
        // });

        /*** COLUMN DEFINE ***/
        var columnDefs = [
            {
                headerName: "First Name",
                field: "first_name",
                editable: true,
                sortable: true,
                width: 220,
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
                width: 220
            },
            {
                headerName: "Status",
                field: "status.name",
                editable: false,
                sortable: true,
                filter: true,
                width: 140
            },
            {
                headerName: "Gender",
                field: "gender.name",
                editable: false,
                sortable: true,
                filter: true,
                width: 140
            },
            {
                headerName: "Role",
                field: "role.name",
                editable: false,
                sortable: true,
                filter: true,
                width: 180
            },
            {
                headerName: "Email",
                field: "email",
                editable: false,
                sortable: true,
                filter: true,
                width: 220
            },
            {
                headerName: "Phone",
                field: "phone",
                editable: false,
                sortable: true,
                filter: true,
                width: 180
            },
            {
                headerName: "Province",
                field: "province.name",
                editable: false,
                sortable: true,
                filter: true,
                width: 220
            },
            {
                headerName: "Regency/City",
                field: "regency.name",
                editable: false,
                sortable: true,
                filter: true,
                width: 220
            },
            {
                headerName: "District",
                field: "district.name",
                editable: false,
                sortable: true,
                filter: true,
                width: 220
            },
            {
                headerName: "Sub-District/Village",
                field: "village.name",
                editable: false,
                sortable: true,
                filter: true,
                width: 220
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
                width: 180,
            },
            {
                headerName: "DOB",
                field: "dob",
                editable: false,
                sortable: true,
                filter: true,
                width: 180,
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
                headerName: "Active",
                field: "active",
                editable: false,
                sortable: true,
                filter: true,
                width: 120
            },
            {
                headerName: "Subs Package",
                field: "package_name",
                editable: false,
                sortable: true,
                filter: true,
                width: 200
            },
            {
                headerName: "Subs Site Code",
                field: "site_code",
                editable: false,
                sortable: true,
                filter: true,
                width: 180
            },
            {
                headerName: "Subs Start At",
                field: "start_at",
                editable: false,
                sortable: true,
                filter: true,
                width: 180
            },
            {
                headerName: "Subs End At",
                field: "end_at",
                editable: false,
                sortable: true,
                filter: true,
                width: 180
            },
            {
                headerName: "Card ID",
                field: "card_id",
                editable: false,
                sortable: true,
                filter: true,
                width: 180,
                pinned: "left"
            },
            {
                headerName: "Action",
                field: 'action',
                cellRenderer: 'btnCellRenderer',
                cellRendererParams: {
                    clicked: function(data) {
                        // console.log(data);
                        detailEdit(data);
                    }
                },
                width: 100,
                pinned: "left",
                cellClass: "btn-table-detail"
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
                showSweetAlert('error','','You are not permitted to make this change, so changes that you make will not be updated in the database!');
            },
            components: {
                btnCellRenderer: BtnCellRenderer
            }
        };

        function detailEdit(data){
            $("#button-edit,#button-close").removeClass("hidden");
            $("#button-update,#button-delete,#button-cancel").addClass("hidden");
            $("#admin-details-modal").modal('show');
            $("#admin-details-modal-title").text(data.card_id.toString()+' - '+(data.first_name?data.first_name.toString():'')+'  '+(data.last_name?data.last_name.toString():''));
            $("#admin-details-modal-body").html($("#loading").html());
            $.ajax({
                url: 'membership/' + data.id + '/detailEdit',
                type: "GET",
                success: (function (view) {
                    $("#admin-details-modal-body").html(view);
                    $("#button-edit").removeClass("hidden");
                    onChange_Role(document.getElementById('role_edit_selector'),1);
                    <?php if($authorize['execute']==1){ ?>
                        $("#pdf").html('<a href="card/' + data.id + '/pdf" target="_blank" type="button" id="button-edit" class="btn btn-outline-dark"><b>Print Card</b></button>');
                    <?php } ?>
                    $("#role_edit_selector,#status_edit_selector,#gender_edit_selector,#province_edit_selector,#regency_edit_selector,#district_edit_selector,#village_edit_selector").select2({
                        minimumResultsForSearch: 0,
                        tokenSeparators: [',', ' ', '.', '/', '\\','[',']',';','\'','{','}','_','+','=','|','"',":"]
                    });

                }),error:function(xhr,status,error) {
                    showSweetAlert('error','',xhr.responseText);
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
                    date.toLocaleString("en-US", {timeZone: "Asia/Jakarta"});
                let date_str = date.getFullYear()+'_'+date.getMonth()+'_'+date.getDate()+'_'+date.getHours()+'_'+date.getMinutes()+'_'+date.getSeconds();
                gridOptions.api.exportDataAsCsv({
                    columnKeys: [
                        'first_name',
                        'last_name',
                        'email',
                        'phone',
                        'dob',
                        'gender',
                        'address',
                        'city',
                        'province',
                        'post_code'
                    ],
                    onlySelected: true,
                    allColumns: false,
                    fileName: 'export_member__at__'+date_str+'.csv',
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
            $("#role_add_selector,#status_add_selector,#gender_add_selector,#province_add_selector,#regency_add_selector,#district_add_selector,#village_add_selector").select2({
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
                $("#button-add-save").html("Loading...");
                $("#addForm").ajaxSubmit({
                    url: 'membership/doAdd',
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    type: 'POST',
                    success:function(data){
                        if(data.status){
                            $("#admin-add-modal").modal("hide");
                            agGrid
                                .simpleHttpRequest({ url: "{{ url('membership/get') }}" })
                                .then(function(data) {
                                    gridOptions.api.setRowData(data.list_data);
                                    $(".filter-btn").text("1 - " + gridOptions.api.paginationGetPageSize()  + " of "+gridOptions.api.getModel().getRowCount());
                                });
                            $('#addForm')[0].reset();
                            $("#button-add-save").html("Save");
                            showSweetAlert('success','',data.message);
                        }else{
                            $("#button-add-save").html("Save");
                            showSweetAlert('error','',data.message);
                            return false;
                        }
                    },
                    error:function(xhr, status, error){
                        $("#button-add-save").html("Save");
                        showSweetAlert('error','',xhr.responseText);
                    }
                });
            }
        }).on("click","#button-update",function () {
            if($("#updateForm")[0].checkValidity()) {
                $("#button-update").val("Updating...");
                $("#updateForm").ajaxSubmit({
                    url: 'membership/doEdit',
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    type: 'POST',
                    success:function(data){
                        if(data.status){
                            $("#admin-details-modal").modal("hide");
                            agGrid
                                .simpleHttpRequest({ url: "{{ url('membership/get') }}" })
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
                    url: 'membership/'+hash+'/delete',
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    type: 'delete',
                    success:function(data){
                        if(typeof(data) == 'string'){
                            data = JSON.parse(data);
                        }
                        if(data.status){
                            $("#admin-details-modal").modal("hide");
                            agGrid
                                .simpleHttpRequest({ url: "{{ url('membership/get') }}" })
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

        }).on('change','#province_edit_selector',function() {
            onChange_Province(this);
        }).on('change','#regency_edit_selector',function() {
            onChange_Regency(this); 
        }).on('change','#district_edit_selector',function() {
            onChange_District(this); 
        }).on('change','#role_edit_selector',function() {
            onChange_Role(this,0); 
        }).on('change','#start_at_edit_selector_date',function() {
            onChange_StartAt(this); 
        }).on('change','#site_edit_selector',function() {
            onChange_Site(this); 
        }).on('change','#package_edit_selector',function() {
            onChange_Package(this); 
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
    });
</script>