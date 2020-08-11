Dropzone.autoDiscover = false;
$(document).ready(function() {
    "use strict"

    var category_changes = [];
    var containers = document.querySelectorAll('.channel_category');
    var draggable = new Draggable.Sortable(containers, {
        draggable: '.draggable',
        delay: 250
    });

    (function revertOnSpill() {
        var outContainer;
        var current_el_id;
        draggable.on('drag:start', (e) => {
            current_el_id = $(e.data.source).find(".handle").data("id");
        });
        draggable.on('drag:out:container', (e) => {
            outContainer = e.data.overContainer;
        });
        draggable.on('sortable:stop', (e) => {
            var newContainer = e.data.newContainer;
            var spill = outContainer && outContainer === newContainer;
            if (spill) {
                var oldContainer = e.data.oldContainer;
                var oldContainerChildren = draggable.getDraggableElementsForContainer(oldContainer);
                var emptyOldContainer = !oldContainerChildren.length;
                var source = e.data.dragEvent.data.source;
                if (emptyOldContainer) {
                    oldContainer.appendChild(source);
                } else {
                    var oldIndex = e.data.oldIndex;
                    oldContainer.insertBefore(source, oldContainer.children[oldIndex]);
                }
            }
        });
        draggable.on('drag:stop', (e) => {
            set_category_changes(current_el_id);
            $(".save-category-structure-action").removeClass("d-none");
        });
    })();

    // Scrollbar
    if ($(".data-items").length > 0) {
        new PerfectScrollbar(".data-items", {
            wheelPropagation: false,
            suppressScrollX: true
        });
    }

    if ($(".data-items-update").length > 0) {
        new PerfectScrollbar(".data-items-update", {
            wheelPropagation: false,
            suppressScrollX: true
        });
    }

    // Close sidebar
    $(".hide-data-sidebar, .cancel-data-btn, .overlay-bg").on("click", function() {
        $(".add-new-data-create").removeClass("show");
        $(".add-new-data-update").removeClass("show");
        $(".overlay-bg").removeClass("show");
    })

    var dataListUpdate;
    var updateHash;
    $(document).on("click",".handle", function(){
        var that = this;
        $(this).text($(this).text()==="+"?"−":"+");
        var parent_id = $(this).data("id");
        var async = $(this).data("async");
        $("#parent-data-"+parent_id).toggleClass('d-none');

        if($.trim($("#parent-data-"+parent_id).find('ul').html())==="" && async){
            $("#parent-data-"+parent_id).html($("#loading").html());
            $.ajax({
                url: 'category/create',
                type:"GET",
                data: {
                    parent_id: parent_id
                },
                success:(function(view){
                    draggable.destroy();
                    $("#parent-data-"+parent_id).html(view);
                    $(that).data("async",false);
                    containers = document.querySelectorAll('.channel_category');
                    draggable = new Draggable.Sortable(containers, {
                        draggable: '.draggable',
                        delay: 250
                    });

                    (function revertOnSpill() {
                        var outContainer;
                        var current_el_id;
                        draggable.on('drag:start', (e) => {
                            current_el_id = $(e.data.source).find(".handle").data("id");
                        });
                        draggable.on('drag:out:container', (e) => {
                            outContainer = e.data.overContainer;
                        });
                        draggable.on('sortable:stop', (e) => {
                            var newContainer = e.data.newContainer;
                            var spill = outContainer && outContainer === newContainer;
                            if (spill) {
                                var oldContainer = e.data.oldContainer;
                                var oldContainerChildren = draggable.getDraggableElementsForContainer(oldContainer);
                                var emptyOldContainer = !oldContainerChildren.length;
                                var source = e.data.dragEvent.data.source;
                                if (emptyOldContainer) {
                                    oldContainer.appendChild(source);
                                } else {
                                    var oldIndex = e.data.oldIndex;
                                    oldContainer.insertBefore(source, oldContainer.children[oldIndex]);
                                }
                            }
                        });
                        draggable.on('drag:stop', (e) => {
                            set_category_changes(current_el_id);
                            $(".save-category-structure-action").removeClass("d-none");
                        });
                    })();
                })
            });
        }
    }).on("click",".category-mapping", function(){
        var hash = $(this).data("hash");
        var title = $(this).data("title");
        $(".add-new-data-update").addClass("show");
        $(".overlay-bg-update").addClass("show");
        $("#update_title").html(title);
        $(".data-items-update").scrollTop(0);
        $("#update_body").html($("#loading").html());
        $("#update_file").prop("disabled", true);

        $.ajax({
            url: 'category/'+hash+'/edit',
            type:"GET",
            data:{
                type: "edit_category_mapping"
            },
            success:(function(view){
                $("#update_body").html(view);
                var data_update_attribute_set = $("#data-update-attribute-set");
                var data_update_attribute = $("#data-update-attribute");
                data_update_attribute_set.select2({
                    dropdownAutoWidth: true,
                    width: '100%'
                });
                data_update_attribute.select2({
                    dropdownAutoWidth: true,
                    width: '100%'
                });
                data_update_attribute_set.on("select2:select", function (e) {
                    var selected_value = $(e.currentTarget).val();
                    get_attribute(selected_value, hash);
                });
                data_update_attribute_set.on("select2:unselect", function (e) {
                    var selected_value = $(e.currentTarget).val();
                    get_attribute(selected_value, hash);
                });
                $("#update_file").removeAttr('disabled');
                function get_attribute(selected_value, hash){
                    $("#update_file").prop("disabled", true);
                    $.ajax({
                        url: 'category/'+hash,
                        type: "GET",
                        data: {
                            type: "show_selected_attribute_set_detail",
                            selected_value: selected_value
                        },
                        success:(function(message){

                            var response = JSON.parse(message);

                            var category_attribute = response.category_attribute;
                            var category_attribute_selected = response.category_attribute_selected;

                            var data_source_category_attribute = [];

                            category_attribute.forEach(function(value, index){
                                data_source_category_attribute[index] = {
                                    id: value,
                                    text: value
                                }
                            });

                            var selected_category_attribute = Array.from(new Set(category_attribute_selected.concat(data_update_attribute.val())));

                            data_update_attribute.empty();

                            if(data_source_category_attribute.length>0){
                                data_update_attribute.select2({
                                    data: data_source_category_attribute
                                });
                            }

                            data_update_attribute.val(selected_category_attribute).trigger("change");
                            $("#update_file").removeAttr('disabled');

                        }),error:function(xhr,status,error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops... Cannot fetch attributes from server!',
                                text: xhr.responseText,
                                type: "error",
                                buttonsStyling: false,
                                confirmButtonClass: "btn btn-error"
                            });
                        }
                    });
                }
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

    }).on("click",".category-edit", function(){
        var hash = $(this).data("hash");
        updateHash = hash;
        var title = $(this).data("title");
        $(".add-new-data-create").addClass("show")
        $(".overlay-bg-create").addClass("show")
        $("#edit_title").html(title);
        $("#edit_body").html($("#loading").html());
        $(".data-items").scrollTop(0);
        $.ajax({
            url: 'category/' + hash + '/edit',
            type: "GET",
            data: {
                type: "edit_category_image"
            },
            success: (function (view) {
                $("#edit_body").html(view);
                dataListUpdate = new Dropzone("div#dataListUpdate", {
                    paramName: "files", // The name that will be used to transfer the file
                    addRemoveLinks: false,
                    uploadMultiple: false,
                    autoProcessQueue: false,
                    thumbnailWidth: 100,
                    thumbnailHeight: 100,
                    parallelUploads: 1,
                    maxFiles:1,
                    maxFilesize: 2, // MB
                    acceptedFiles: ".png, .jpeg, .jpg, .gif",
                    url: "/category/"+hash,
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    init: function () {
                        this.on("maxfilesexceeded", function (file) {
                            this.removeAllFiles();
                            this.addFile(file);
                        });
                    }
                }).on("sending", function(file, xhr, formData) {
                    formData.append('category_key', $(".update_data_category_key").val());
                    formData.append('type', "update_category_image");
                    formData.append('_method', "PUT");
                }).on("success", function(file, message){
                    var response = JSON.parse(message);
                    if($.trim(response.result_status).toUpperCase()==="SUCCESS"){
                        Swal.fire({
                            title: "Yeah.. it works!",
                            html: response.result_message,
                            type: "success",
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        });
                        $(".add-new-data-create").removeClass("show")
                        $(".overlay-bg").removeClass("show")
                    }else{
                        Swal.fire({
                            title: "Opps.. Error!",
                            html: response.result_message,
                            type: "error",
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        });
                    }
                    $(".update_image_preview").attr("src",file.previewElement.querySelector("img").src);
                    dataListUpdate.removeFile(file);
                }).on("error", function (file, errorMessage) {
                    toastr.error(errorMessage, 'Upload Failed!', { "progressBar": true })
                })
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
    }).on("click",".category-delete", function(){
        var hashs = [$(this).data("hash")];
        var title = $(this).data("title");

        Swal.fire({
            title: 'Are you sure to delete '+title+'?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-outline-primary ml-1',
            buttonsStyling: false,
        }).then(function (result) {
            if (result.value) {

                $.ajax({
                    url: 'category/'+hashs,
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    type:"DELETE",
                    success:(function(message){

                        var response = JSON.parse(message);

                        if($.trim(response.result_status).toUpperCase()==="SUCCESS"){
                            Swal.fire(
                                {
                                    type: "success",
                                    title: 'Deleted!',
                                    text: title+' has been deleted.',
                                    confirmButtonClass: 'btn btn-primary',
                                }
                            ).then(()=>{
                                location.reload();
                            });
                        }else{
                            Swal.fire({
                                title: "Opps.. Error!",
                                html: response.result_message,
                                type: "error",
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            });
                        }
                    })
                });
            }
        })


    }).on("click",".save-category-structure", function(){
        $(".save-category-structure-action").addClass("d-none");
        var category_parent_changes = [];
        category_changes.forEach(function(category_changes_id){
            var current_parent_id = $(".handle[data-id='"+category_changes_id+"']").parent("li").parent("ul").parent("div").attr("id");
            category_parent_changes[category_changes_id] = !current_parent_id ? 0 : parseInt(current_parent_id.replace('parent-data-',''));
        });

        $.ajax({
            url: 'category',
            headers: {
                'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
            },
            type:"POST",
            data: {
                type: "store_structure_changes",
                category_parent_changes: category_parent_changes
            },
            success:(function(message){
                var response = JSON.parse(message);
                if($.trim(response.result_status).toUpperCase()==="SUCCESS"){
                    Swal.fire({
                        title: "Yeah.. it works!",
                        html: response.result_message,
                        type: "success",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                    category_changes= [];
                }else{
                    Swal.fire({
                        title: "Opps.. Error!",
                        html: response.result_message,
                        type: "error",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }
            })
        });

    }).on("click","#add-new-category", function () {
        Swal.mixin({
            confirmButtonText: 'Next &rarr;',
            showCancelButton: true,
            reverseButtons: true,
            progressSteps: ['1', '2'],
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: "btn btn-default"
        }).queue([
            {
                title: 'Add New Category',
                text: 'Enter a new category name',
                input: 'text',
                inputPlaceholder: "Enter a new category name...",
                customClass: {
                    input: 'form-control'
                },
                inputValidator: (value) => {
                    return !value && 'You need to write something!'
                }
            },
            {
                title: 'Main Parent Categories',
                html: 'Is this new category a subcategory? If yes, please select the available main parent categories, if not or unavailable leave it blank!',
                input: 'select',
                customClass: {
                    input: 'form-control'
                },
                inputOptions: categories,
                inputPlaceholder: 'Select main parent categories...',
            }
        ]).then(function (result) {
            if (result.value) {
                var new_category = result.value[0];
                var selected_parent_id = result.value[1];

                $.ajax({
                    url: 'category',
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    type:"POST",
                    data: {
                        type: "store_new_category",
                        new_category: new_category,
                        selected_parent_id: selected_parent_id
                    },
                    success:(function(message){
                        var response = JSON.parse(message);
                        if($.trim(response.result_status).toUpperCase()==="SUCCESS"){
                            Swal.fire({
                                title: "Yeah.. it works!",
                                html: response.result_message,
                                type: "success",
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            }).then(()=>{
                                location.reload();
                            });
                        }else{
                            Swal.fire({
                                title: "Opps.. Error!",
                                html: response.result_message,
                                type: "error",
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            });
                        }
                    })
                });
            }
        })
    }).on("click","#update_file",function(){
        var that = this;
        $(that).text("Updating...");
        $("#update_file").prop("disabled", true);

        var hash = $("#_update_hash").val();
        var data_update_attribute_set = $("#data-update-attribute-set").val();
        var data_update_attribute = $("#data-update-attribute").val();

        $.ajax({
            url: 'category/'+hash,
            headers: {
                'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
            },
            type: "PUT",
            data: {
                type: "update_category_attributes",
                data_update_attribute_set: data_update_attribute_set,
                data_update_attribute: data_update_attribute
            },
            success: (function (message) {

                var response = JSON.parse(message);
                if($.trim(response.result_status).toUpperCase()==="SUCCESS"){
                    Swal.fire({
                        title: "Yeah.. it works!",
                        html: response.result_message,
                        type: "success",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }else{
                    Swal.fire({
                        title: "Opps.. Error!",
                        html: response.result_message,
                        type: "error",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }

                $(that).text("Set Attributes");
                $("#update_file").removeAttr('disabled');

            }), error: function (xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: xhr.responseText,
                    type: "error",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-error"
                });
                $(that).text("Set Attributes");
                $("#update_file").removeAttr('disabled');
            }
        });
    }).on("dblclick",".category-name",function(){
        var that = this;
        var hash = $(this).data("hash");
        var current_text_color = $(this).css("color");
        $(this).css("color","#c00");
        $(this).attr('contenteditable','true');
        $(this).focus();
        $(this).keypress(function (event) {
            if (event.keyCode === 13) {
                $(that).attr('contenteditable','false');
                $(that).html($(that).text().trim());
                $(this).css("color",current_text_color);
            }
        });
    }).on("keypress",".category-name",function(event){
        var that = this;
        var hash = $(this).data("hash");
        if (event.keyCode === 13) {
            $.ajax({
                url: 'category/'+hash,
                headers: {
                    'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                },
                type: "PUT",
                data: {
                    type: "update_category_name",
                    value: $(that).text()
                },
                success: (function (message) {
                }),error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }
    }).on("click","#add_file",function(){
        var error_message = null;

        if(!$.trim($(".update_data_category_key").val())){
            error_message = "Please enter the category key first!";
            $(".update_data_category_key").focus();
        }

        if(error_message === null){
            if(dataListUpdate.getQueuedFiles().length>0) {
                dataListUpdate.processQueue();
            }else{
                $.ajax({
                    url: 'category/'+updateHash,
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    type:"PUT",
                    data: {
                        category_key: $(".update_data_category_key").val(),
                        type: "update_category_image"
                    },
                    success:(function(message){
                        var response = JSON.parse(message);
                        if($.trim(response.result_status).toUpperCase()==="SUCCESS"){
                            Swal.fire({
                                title: "Yeah.. it works!",
                                html: response.result_message,
                                type: "success",
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            });
                            $(".add-new-data-create").removeClass("show")
                            $(".overlay-bg").removeClass("show")
                        }else{
                            Swal.fire({
                                title: "Opps.. Error!",
                                html: response.result_message,
                                type: "error",
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            });
                        }
                    })
                });
            }
        }else{
            Swal.fire({
                title: 'There is something missing',
                text: error_message,
                animation: false,
                customClass: 'animated shake',
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
            });
        }

    });

    function set_category_changes(id){
        if(!category_changes.includes(id)){
            category_changes.push(id);
        }
    }
});
