<script type="text/javascript">
//Dropzone script
    Dropzone.autoDiscover = false;
    $(document).ready(function() {
        "use strict"

        let page_title = 'Menu';

        // init view datatable
        $(document).on("preInit.dt", function () {
            var $sb = $(".dataTables_filter input[type='search']");
            $sb.off(); // remove current handler
            $sb.on("keypress", function (evtObj) { // Add key hander
                if (evtObj.keyCode == 13) {
                    if($sb.val().length >= 3 || $sb.val().length == 0){
                        $(".data-thumb-view").DataTable().search($sb.val()).draw();
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Do a search using at least 3 characters or leave blank and press enter to cancel the search.',
                            type: "error",
                            buttonsStyling: false,
                            confirmButtonClass: "btn btn-error"
                        });
                    }
                }
            });
        });
        
        var actionButtons = [];
        <?php if($authorize['execute']==1){ ?>
            actionButtons.push(
                
                {
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [ 0, ':visible' ]
                    },
                    className: 'btn-primary btn-datatable-action disabled'
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: ':visible'
                    },
                    className: 'btn-primary btn-datatable-action disabled'
                },
                {
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: ':visible'
                    },
                    className: 'btn-primary btn-datatable-action disabled'
                },
                {
                    text: 'JSON',
                    action: function ( e, dt, button, config ) {
                        var data = dt.buttons.exportData();

                        $.fn.dataTable.fileSave(
                            new Blob( [ JSON.stringify( data ) ] ),
                            'Export.json'
                        );
                    },
                    className: 'btn-primary btn-datatable-action disabled'
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible'
                    },
                    className: 'btn-primary btn-datatable-action disabled'
                },
            );
        <?php } ?>
        <?php if($authorize['create']==1){ ?>
            actionButtons.push(
                {
                    text: "<i class='feather icon-plus'></i> Add New",
                    action: function ( e, dt, button, config ) {
                        $(".add-new-data-create").addClass("show")
                        $(".overlay-bg-create").addClass("show")
                    },
                    className: 'btn-outline-primary btn-datatable-action disabled'
                }
            );
        <?php } ?>

        var dataListView = $('.dataex-html5-selectors').DataTable( {
            processing: true,
            serverSide: true,
            responsive: false,
            ajax: {
                url: "{{ url('master/app/menu/get') }}",
                type: 'GET',
                error:function(xhr, status, error){}
            },
            columnDefs: [
                {
                    orderable: true,
                    targets: 0,
                    checkboxes: { selectRow: true }
                },
                {
                    targets: 'no-sort',
                    orderable: false,
                }
            ],
            dom:
                '<"top"<"actions action-btns"B><"action-filters"lf>><"clear">rt<"bottom"<"actions">p>',
            oLanguage: {
                sLengthMenu: "_MENU_",
                sSearch: "",
                sSearchPlaceholder: "Type and press enter..."
            },
            aLengthMenu: [[10, 50, 100, 200], [10, 50, 100, 200]],
            select: {
                style: "multi"
            },
            order: [[1, "asc"]],
            bInfo: false,
            pageLength: 10,
            buttons: actionButtons,
            initComplete: function(settings, json) {
                $(".btn-datatable-action").removeClass("btn-secondary disabled");
            }
        });

        dataListView.on('draw.dt', function(){
            setTimeout(function(){
            if (navigator.userAgent.indexOf("Mac OS X") != -1) {
                $(".dt-checkboxes-cell input, .dt-checkboxes").addClass("mac-checkbox")
            }
            }, 50);
        });

        // To append actions dropdown before add new button
        var actionDropdown = $(".actions-dropodown")
        actionDropdown.insertBefore($(".top .actions .dt-buttons"))

        // Scrollbar
        if ($(".data-items").length > 0) {
            new PerfectScrollbar(".data-items", { wheelPropagation: false });
        }

        if ($(".data-items-update").length > 0) {
            new PerfectScrollbar(".data-items-update", {
                wheelPropagation: false,
                suppressScrollX: true
            });
        }

        // Close sidebar
        $(".hide-data-sidebar, .cancel-data-btn, .overlay-bg").on("click", function() {
            $(".add-new-data-create").removeClass("show")
            $(".add-new-data-update").removeClass("show")
            $(".overlay-bg").removeClass("show")
            $("#data-name, #data-price").val("")
            $("#data-category, #data-status").prop("selectedIndex", 0)
        })
        
        // ------------------------------------------- BEGIN :: CRUD
        // On Add (UI)
        $(".dt-buttons").on('click', function(event){
            $.ajax({
                url: 'menu/detailAdd',
                type:"GET",
                success:(function(data){
                    console.log(data);
                    let action = 'add';
                    $("#add_body").html(createForm(action));
                    // BEGIN : options
                    let str = '<option value=""></option>';
                    for (let i = 0; i < (data.list_menu_type).length; ++i) {
                        str += '<option value="'+data.list_menu_type[i]+'">'+data.list_menu_type[i]+'</option>';
                    }
                    $("#"+action+"-type").html(str);
                    str = '<option value=""></option>';
                    for (let i = 0; i < (data.list_parent).length; ++i) {
                        str += '<option value="'+data.list_parent[i].id+'">'+data.list_parent[i].name+'</option>';
                    }
                    $("#"+action+"-parent").html(str);
                    // END : options
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
            });

        });

        // On Edit (UI)
        $('div').on("click","span.action-edit",function(e){
            e.stopPropagation();
            var hash = $(this).data("hash");
            var title = $(this).data("title");
            $(".add-new-data-update").addClass("show");
            $(".overlay-bg-update").addClass("show");
            $("#update_title").html("Update Data : "+title);
            $(".data-items-update").scrollTop(0);
            $("#update_body").html($("#loading").html());
            $.ajax({
                url: 'menu/'+hash+'/detailEdit',
                type:"GET",
                success:(function(data){
                    console.log(data);
                    let action = 'edit';
                    $("#update_body").html(createForm(action));

                    $("#"+action+"-id").val(hash); 
                    $("#"+action+"-name").val(data.detail.name); 
                    $("#"+action+"-slug").val(data.detail.slug); 
                    $("#"+action+"-icon").val(data.detail.icon); 
                    // BEGIN : options
                    let prop = '', str = '<option value=""></option>';
                    for (let i = 0; i < (data.list_menu_type).length; ++i) {
                        prop = (data.detail.type == data.list_menu_type[i]) ? 'selected="selected"' : '';
                        str += '<option value="'+data.list_menu_type[i]+'" '+prop+'>'+data.list_menu_type[i]+'</option>';
                    }
                    $("#"+action+"-type").html(str);
                    str = '<option value=""></option>';
                    for (let i = 0; i < (data.list_parent).length; ++i) {
                        prop = (data.detail.parent == data.list_parent[i].id) ? 'selected="selected"' : '';
                        str += '<option value="'+data.list_parent[i].id+'" '+prop+'>'+data.list_parent[i].name+'</option>';
                    }
                    $("#"+action+"-parent").html(str);
                    // END : options
                    $("#"+action+"-need_auth").prop('checked', (data.detail.need_auth == 1) ? true : false);
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
            });
        });

        // On Delete (UI and DO)
        $('div').on("click","span.action-delete", function(e){
            e.stopPropagation();
            var that = $(this);
            var hashs = [$(this).data("hash")];
            var title = $(this).data("title");
            Swal.fire({
                title: 'Are you sure to delete '+title+'?',
                text: "You won't be able to revert this",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-outline-primary ml-1',
                buttonsStyling: false,
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        type:"DELETE",
                        url: 'menu/'+hashs+'/delete',
                        headers: {
                            'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                        },
                        dataType: 'json',
                        success: function(result){    
                            if(result['status']){
                                that.closest('td').parent('tr').fadeOut();
                                Swal.fire(
                                    {
                                        type: "success",
                                        title: "success",
                                        text: result['message']+'.',
                                        confirmButtonClass: 'btn btn-primary',
                                    }
                                )
                                dataListView.ajax.reload();
                            }else{
                                Swal.fire({
                                    title: "Opps.. Error!",
                                    html: result['message']+'<br><br>'+result['detail'],
                                    type: "error",
                                    confirmButtonClass: 'btn btn-primary',
                                    buttonsStyling: false,
                                });
                            }
                        },
                        error: function (err){
                            toastr.error(err, 'Delete Failed!', { "progressBar": true })
                        }
                    });
                }
            })
        });

        //on Bulk Delete (UI and DO)
        $(document).on("click","#bulk_delete", function(){
            var checked_rows = $('input.dt-checkboxes:checkbox:checked').parents("tr");
            var hashs = [];
            if(checked_rows.length) {
                $.each(checked_rows, function (key, val) {
                    hashs.push($(this).find(".action-delete").data("hash"))
                });
                console.log(hashs);
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Are you sure you want to delete all selected items? You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-outline-primary ml-1',
                    buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            type:"DELETE",
                            url: 'menu/'+hashs+'/delete',
                            headers: {
                                'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                            },
                            dataType: 'json',
                            success: function(result){    
                                if(result['status']){
                                    Swal.fire(
                                        {
                                            type: "success",
                                            title: "success",
                                            text: result['message']+'.',
                                            confirmButtonClass: 'btn btn-primary',
                                        }
                                    )
                                    dataListView.ajax.reload();
                                }else{
                                    Swal.fire({
                                        title: "Opps.. Error!",
                                        html: result['message']+'<br><br>'+result['detail'],
                                        type: "error",
                                        confirmButtonClass: 'btn btn-primary',
                                        buttonsStyling: false,
                                    });
                                }
                            },
                            error: function (err){
                                toastr.error(err, 'Delete Failed!', { "progressBar": true })
                            }
                        });
                    }
                });
            }else{
                Swal.fire(
                    {
                        type: "error",
                        title: 'Opps..!',
                        text: 'No items selected, select items to be bulk delete!',
                        confirmButtonClass: 'btn btn-primary',
                    }
                )
            }
        });

        // On Blank
        $('div').on("click","a.action_blank", function(e){
            e.stopPropagation();
        });

        // On Add (DO)
        $("#add_data").on("click",function (){
            let action = 'add';
            let action_for_msg = 'Add';
            let params = getFormData(action);

            $.ajax({
                type: "POST",
                url: "menu/doAdd",
                data: {
                    "_token": "{{ csrf_token() }}",
                    params: params,
                },
                dataType: 'json',
                success: function(result){        
                    if(result['status']){
                        Swal.fire(
                            {
                                type: "success",
                                title: "success",
                                text: page_title+' has been '+action+'ed.',
                                confirmButtonClass: 'btn btn-primary',
                            }
                        )
                        dataListView.ajax.reload();
                        $(".add-new-data-create").removeClass("show")
                        $(".overlay-bg").removeClass("show")
                    }else{
                        toastr.error(result['message']+'<br><br>'+result['detail'], action_for_msg+' Failed!', { "progressBar": true });
                    }
                },
                error: function (err){
                    toastr.error(err, action_for_msg+' Failed!', { "progressBar": true })
                }
            });
        });

        // On Update (DO)
        $(document).on("click","#update_data", function(){
            let action = 'edit';
            let action_for_msg = 'Edit';
            let params = getFormData(action);

            $.ajax({
                type: "POST",
                url: "menu/doEdit",
                data: {
                    "_token": "{{ csrf_token() }}",
                    params: params,
                },
                dataType: 'json',
                success: function(result){        
                    if(result['status']){
                        Swal.fire(
                            {
                                type: "success",
                                title: "success",
                                text: page_title+' has been '+action+'ed.',
                                confirmButtonClass: 'btn btn-primary',
                            }
                        )
                        dataListView.ajax.reload();
                        $(".add-new-data-update").removeClass("show")
                        $(".overlay-bg").removeClass("show")
                    }else{
                        toastr.error(result['message']+'<br><br>'+result['detail'], action_for_msg+' Failed!', { "progressBar": true });
                    }
                },
                error: function (err){
                    toastr.error(err, action_for_msg+' Failed!', { "progressBar": true })
                }
            });
        });
        // ------------------------------------------- END :: CRUD

        // mac chrome checkbox fix
        if (navigator.userAgent.indexOf("Mac OS X") != -1) {
            $(".dt-checkboxes-cell input, .dt-checkboxes").addClass("mac-checkbox")
        }

        function isUrl(s) {
            var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
            return regexp.test(s);
        }

        function getUpdateValue(){

            var name = $(".menu_name").val();
            var url = $(".update_data_url").val();
            var sequence = [];

            $(".update_sequence_sibling").each(function(i){
                sequence[i] = $(this).data("menu-id");
            });

            return {
                name: name,
                url: url,
                sequence: sequence
            };
        }

        function createForm(action){
            return '<!-- '+action+' form : BEGIN -->\
                    <div class="col-sm-12 data-field-col">\
                        <div class="form-group" style="display:none">\
                            <label for="'+action+'-id"><b>Id</b></label>\
                            <input type="text" class="form-control" id="'+action+'-id">\
                        </div>\
                        <div class="form-group">\
                            <label for="'+action+'-name"><b>Name</b></label>\
                            <input type="text" class="form-control" id="'+action+'-name" required>\
                        </div>\
                        <div class="form-group">\
                            <label for="'+action+'-slug"><b>Slug</b></label>\
                            <input type="text" class="form-control" id="'+action+'-slug" value="#">\
                        </div>\
                        <div class="form-group">\
                            <label for="'+action+'-icon"><b>Icon</b></b></label>\
                            <input type="text" class="form-control" id="'+action+'-icon">\
                        </div>\
                        <div class="form-group">\
                            <label for="'+action+'-type"><b>Type</b></label>\
                            <select class="form-control" id="'+action+'-type"></select>\
                        </div>\
                        <div class="form-group row">\
                            <div class="form-group col-xs-12 col-sm-7">\
                                <label for="'+action+'-parent"><b>Parent</b></label>\
                                <select class="form-control" id="'+action+'-parent"></select>\
                            </div>\
                            <div class="col-xs-2 col-sm-1"></div>\
                            <div class="form-check col-xs-10 col-sm-4" title="check >> for requiring user login and role mapping to access this menu">\
                                <label for="'+action+'-need_auth"><b>Need Auth</b></label><br>\
                                <input type="checkbox" class="form-check-input" id="'+action+'-need_auth" value=1 checked>\
                                yes\
                            </div>\
                        </div>\
                    </div>\
                    <!-- '+action+' form : END   -->';
        }

        function getFormData(action){
            let data = {
                name    : $('#'+action+'-name').val(),
                slug    : $('#'+action+'-slug').val(),
                icon    : $('#'+action+'-icon').val(),
                type    : $('#'+action+'-type').val(),
                parent  : $('#'+action+'-parent').val(),
                need_auth   : $('#'+action+'-need_auth').is(":checked") ? 1 : 0,
            };

            if(action == 'edit') data['id'] = $('#'+action+'-id').val();
            
            return data;
        }

    })
</script>