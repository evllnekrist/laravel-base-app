<script type="text/javascript">
//Dropzone script
    Dropzone.autoDiscover = false;
    $(document).ready(function() {
        "use strict"
        
        let page_title = 'Mapping Role Menu';

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
                        columns: ':visible',
                        pageSize: 'A5',
                        pageOrientation: 'landscape',
                        pageMargins: [ 40, 60, 40, 60 ],
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
        <?php // if($authorize['create']==1){ ?>
            // actionButtons.push(
            //     {
            //         text: "<i class='feather icon-plus'></i> Add New",
            //         action: function ( e, dt, button, config ) {
            //             $(".add-new-data-create").addClass("show")
            //             $(".overlay-bg-create").addClass("show")
            //         },
            //         className: 'btn-outline-primary btn-datatable-action disabled'
            //     }
            // );
        <?php // } ?>

        var dataListView = $('.dataex-html5-selectors').DataTable( {
            processing: true,
            serverSide: true,
            responsive: false,
            ajax: {
                url: "{{ url('master/app/role-menu/get') }}",
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
            dom: '<"top"<"actions action-btns"B><"action-filters"lf>><"clear">rt<"bottom"<"actions">p>',
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
                url: 'role-menu/'+hash+'/detailEdit',
                type:"GET",
                success:(function(data){
                    console.log(data);
                    let action = 'edit';
                    $("#update_body").html(createForm(action,data));
                    // $("#"+action+"-id").val(hash); 
                    $("#"+action+"-id").val(data.detail.id); 
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

        // On Blank
        $('div').on("click","a.action_blank", function(e){
            e.stopPropagation();
        });

        // On Update (DO)
        $(document).on("click","#update_data", function(){
            let action = 'edit';
            let action_for_msg = 'Edit';
            let params = getFormData(action);
            // console.log('doEdit', params);
            $.ajax({
                type: "POST",
                url: "role-menu/doEdit",
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

        function createForm(action,data){
            console.log(data);
            let str =   '<!-- '+action+' form : BEGIN -->\
                        <div class="col-sm-12 data-field-col">\
                            <div class="form-group">\
                                <input type="text" class="form-control" id="'+action+'-id" style="display:none">\
                                Mapping Menu for Role <strong id="'+action+'-name">"'+data.detail.name+'"</strong>\
                            </div>\
                        </div>\
                        <table class="table table-striped table-dark table-bordered ft-sm">\
                            <thead>\
                                <tr>\
                                    <td scope="col">#</td>\
                                    <td scope="col"><b>Menu Name</b></td>\
                                    <td scope="col" class="col-fit-right-sm"><b>View</b></td>\
                                    <td scope="col" class="col-fit-right-sm"><b>Create</b></td>\
                                    <td scope="col" class="col-fit-right-sm"><b>Edit</b></td>\
                                    <td scope="col" class="col-fit-right-sm"><b>Delete</b></td>\
                                    <td scope="col" class="col-fit-right-sm"><b>Execute</b></td>\
                                </tr>\
                            </thead>\
                            <tbody>';
                for (let i = 0; i < (data.list_menu).length; ++i) {
                    str +=      '<tr>\
                                    <th scope="row">'+(i+1)+'</th>\
                                    <td><input name="id[]" type="text" value="'+data.list_menu[i].id+'" style="display:none">\
                                        '+data.list_menu[i].name+'\
                                    </td>\
                                    <td class="col-fit-right-sm"><input name="view[]" type="checkbox" value="1" '+(data.list_menu[i].view ? 'checked' : '')+'></td>\
                                    <td class="col-fit-right-sm"><input name="create[]" type="checkbox" value="1" '+(data.list_menu[i].create ? 'checked' : '')+'></td>\
                                    <td class="col-fit-right-sm"><input name="edit[]" type="checkbox" value="1" '+(data.list_menu[i].edit ? 'checked' : '')+'></td>\
                                    <td class="col-fit-right-sm"><input name="delete[]" type="checkbox" value="1" '+(data.list_menu[i].delete ? 'checked' : '')+'></td>\
                                    <td class="col-fit-right-sm"><input name="execute[]" type="checkbox" value="1" '+(data.list_menu[i].execute ? 'checked' : '')+'></td>\
                                </tr>';
                }
            str +=          '</tbody>\
                        </table>\
                        <!-- '+action+' form : END   -->';
            return str;
        }

        function getFormData(action){
            let data = {detail:{}};
            let i = 0;
            $("input[name=id\\[\\]").each(function() {
                data.detail[i] = {};
                data.detail[i].id = this.value; 
                i++;
            });

            i = 0;
            $("input[name=view\\[\\]").each(function() {
                data.detail[i].view = $(this).is(':checked')?this.value:0; 
                i++;
            });

            i = 0;
            $("input[name=create\\[\\]").each(function() {
                data.detail[i].create = $(this).is(':checked')?this.value:0; 
                i++;
            });
            
            i = 0;
            $("input[name=edit\\[\\]").each(function() {
                data.detail[i].edit = $(this).is(':checked')?this.value:0; 
                i++;
            });
            
            i = 0;
            $("input[name=delete\\[\\]").each(function() {
                data.detail[i].delete = $(this).is(':checked')?this.value:0; 
                i++;
            });
            
            i = 0;
            $("input[name=execute\\[\\]").each(function() {
                data.detail[i].execute = $(this).is(':checked')?this.value:0; 
                i++;
            });

            if(action == 'edit'){
                 data.id = $('#'+action+'-id').val();
                 data.name = $('#'+action+'-name').html();
            }
            
            return data;
        }

    })
</script>