//Dropzone script
Dropzone.autoDiscover = false;
$(document).ready(function() {
  "use strict"
  // init view datatable
  $(document).on("preInit.dt", function () {
     var $sb = $(".dataTables_filter input[type='search']");
     // remove current handler
     $sb.off();
     // Add key hander
     $sb.on("keypress", function (evtObj) {
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

  var dataListView = $(".data-thumb-view").DataTable({
    processing: true,
    serverSide: true,
    responsive: false,
    ajax: {
      url: "{{ url('/banner/create') }}",
      type: 'GET',
      error:function(xhr, status, error){

      }
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
    order: [[2, "asc"]],
    bInfo: false,
    pageLength: 10,
    buttons: [
      {
        text: "<i class='feather icon-plus'></i> Add New",
        action: function() {
          $(this).removeClass("btn-secondary")
          $(".add-new-data-create").addClass("show")
          $(".overlay-bg-create").addClass("show")
        },
        className: "btn-outline-primary"
      }
    ],
    initComplete: function(settings, json) {
      $(".dt-buttons .btn").removeClass("btn-secondary")
    }
  })

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

  // On Edit
  var dataListUpdate;
  var updateHash;
  $('div').on("click","span.action-edit",function(e){
      e.stopPropagation();
      var hash = $(this).data("hash");
      var title = $(this).data("title");
      updateHash = hash;
      $(".add-new-data-update").addClass("show");
      $(".overlay-bg-update").addClass("show");
      $("#update_title").html("Update Data : "+title);
      $(".data-items-update").scrollTop(0);
      $("#update_body").html($("#loading").html());
      $.ajax({
          url: 'banner/'+hash+'/edit',
          type:"GET",
          success:(function(view){
              $("#update_body").html(view);
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
                  url: "/banner/"+hash,
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
                  formData.append('name', getUpdateValue().name);
                  formData.append('url', getUpdateValue().url);
                  getUpdateValue().sequence.forEach(function (value,index) {
                      formData.append('sequence['+index+']', value);
                  });
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
                      dataListView.ajax.reload();
                      $(".add-new-data-update").removeClass("show")
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
              });
              dragula(
                  [document.getElementById('card-drag-area')],
                  {
                      copySortSource: false,
                      moves: function (el, source, handle, sibling) {
                          if($(el).find('.update_sequence').length){
                              return true;
                          }
                          return false;
                      }
                  }
              );
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

  // On Delete
  $('div').on("click","span.action-delete", function(e){
      e.stopPropagation();
      var that = $(this);
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
                  url: 'banner/'+hashs,
                  headers: {
                      'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                  },
                  type:"DELETE",
                  success:(function(message){

                      var response = JSON.parse(message);

                      if($.trim(response.result_status).toUpperCase()==="SUCCESS"){
                          that.closest('td').parent('tr').fadeOut();
                          Swal.fire(
                              {
                                  type: "success",
                                  title: 'Deleted!',
                                  text: title+' has been deleted.',
                                  confirmButtonClass: 'btn btn-primary',
                              }
                          )
                          dataListView.ajax.reload();
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
  });

  //on Bulk Delete
  $(document).on("click","#bulk_delete", function(){
      var checked_rows = $('input.dt-checkboxes:checkbox:checked').parents("tr");
      var hashs = [];
      if(checked_rows.length) {
          $.each(checked_rows, function (key, val) {
              hashs.push($(this).find(".action-delete").data("hash"))
          });
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
                      url: 'banner/'+hashs,
                      headers: {
                          'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                      },
                      type:"DELETE",
                      success:(function(message){

                          var response = JSON.parse(message);

                          if($.trim(response.result_status).toUpperCase()==="SUCCESS"){
                              $('.dt-checkboxes-select-all').find('input:checkbox:checked').click();
                              Swal.fire(
                                  {
                                      type: "success",
                                      title: 'Deleted!',
                                      text: 'Items has been deleted.',
                                      confirmButtonClass: 'btn btn-primary',
                                  }
                              )
                              dataListView.ajax.reload();
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

    $(function() {
        $("#dataListUpload").sortable({
            items: '.dz-preview',
            cursor: 'move',
            opacity: 0.5,
            containment: '#dataListUpload',
            distance: 20,
            tolerance: 'pointer',
        }).disableSelection();
    });

    var dataListUpload = new Dropzone("div#dataListUpload", {
        paramName: "files", // The name that will be used to transfer the file
        addRemoveLinks: true,
        uploadMultiple: true,
        autoProcessQueue: false,
        thumbnailWidth: 100,
        thumbnailHeight: 100,
        parallelUploads: 50,
        maxFilesize: 2, // MB
        acceptedFiles: ".png, .jpeg, .jpg, .gif",
        dictRemoveFile: " Remove",
        dictCancelUpload: "Cancel",
        url: "/banner",
        headers: {
            'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
        },
    });

    dataListUpload.on("sending", function(file, xhr, formData) {

        formData.append('section', $("#data-section").val());

        $('.dz-preview .dz-filename').each(function(i) {
            formData.append('filenames['+i+']',$(this).find('span').text());
        });

        $("#link_url .add_link_urls").each(function(i){
            formData.append('url_link['+i+']',$(this).find(".add_data_url").val())
        });

    });

    /* Add Files Script*/
    dataListUpload.on("success", function(file, message){
        var response = JSON.parse(message);
        if($.trim(response.result_status).toUpperCase()==="SUCCESS"){
            Swal.fire({
                title: "Yeah.. it works!",
                html: response.result_message,
                type: "success",
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
            });
            dataListView.ajax.reload();
        }else{
            Swal.fire({
                title: "Opps.. Error!",
                html: response.result_message,
                type: "error",
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
            });
        }
    });

    dataListUpload.on("error", function (file, errorMessage) {
        toastr.error(errorMessage, 'Upload Failed!', { "progressBar": true })
    });

    dataListUpload.on("complete", function(file) {
        dataListUpload.removeFile(file);
        $("#data-section").val(0);
    });

    dataListUpload.on("addedfile", function(file) {
        var link_url_temp = $("#link_url_temp").html();
        var link_url_copied = $(link_url_temp).clone();
        $(link_url_copied).find(".add_link_url_index").text(dataListUpload.files.length)
        $("#link_url").append(link_url_copied);

        if (this.files.length) {
            var _i, _len;
            for (_i = 0, _len = this.files.length; _i < _len - 1; _i++) // -1 to exclude current file
            {
                if(this.files[_i].name === file.name)
                {
                    this.removeFile(file);
                    Swal.fire({
                        title: 'An image with the same name already exists',
                        text: 'Please upload images with a different name!',
                        animation: false,
                        customClass: 'animated shake',
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    })
                }
            }
        }
    });

    dataListUpload.on("removedfile", function(file){
        $("#link_url .add_link_urls:last-child").remove();
    });

    // On Add
    $("#add_file").on("click",function (){

        var error_message = null;

        if(dataListUpload.files.length<=0){
            error_message = "Please upload the image first!";
        }

        $("#link_url .add_link_urls").each(function(){
            if(!isUrl($(this).find(".add_data_url").val())){
                error_message = "You must fill in the url link correctly below the upload banner box!";
                $(this).find(".add_data_url").focus();
                return false;
            }
        });

        if($("#data-section").val()<=0){
            error_message = "You must select the banner section";
        }

        if(error_message === null){
            dataListUpload.processQueue();
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

    // On Update
    $(document).on("click","#update_file", function(){
        var error_message = null;

        if(!isUrl($(".update_data_url").val())){
            error_message = "Please enter the url that will be updated correctly!";
            $(".update_data_url").focus();
        }

        if(!$.trim($(".banner_name").val())){
            error_message = "Please enter the banner name first!";
            $(".banner_name").focus();
        }

        if(error_message === null){
            if(dataListUpdate.getQueuedFiles().length>0) {
                dataListUpdate.processQueue();
            }else{
                $.ajax({
                    url: 'banner/'+updateHash,
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    type:"PUT",
                    data: getUpdateValue(),
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
                            dataListView.ajax.reload();
                            $(".add-new-data-update").removeClass("show")
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

    // mac chrome checkbox fix
  if (navigator.userAgent.indexOf("Mac OS X") != -1) {
    $(".dt-checkboxes-cell input, .dt-checkboxes").addClass("mac-checkbox")
  }

  function isUrl(s) {
      var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
      return regexp.test(s);
  }

  function getUpdateValue(){

      var name = $(".banner_name").val();
      var url = $(".update_data_url").val();
      var sequence = [];

      $(".update_sequence_sibling").each(function(i){
          sequence[i] = $(this).data("banner-id");
      });

      return {
          name: name,
          url: url,
          sequence: sequence
      };
  }

})
