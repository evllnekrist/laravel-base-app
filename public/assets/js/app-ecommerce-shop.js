$(document).ready(function () {
    "use strict";
    var direction = 'ltr';
    var sidebarShop = $(".sidebar-shop"),
        shopOverlay = $(".shop-content-overlay"),
        sidebarToggler = $(".shop-sidebar-toggler"),
        priceFilter = $(".price-options"),
        gridViewBtn = $(".grid-view-btn"),
        listViewBtn = $(".list-view-btn"),
        ecommerceProducts = $("#ecommerce-products"),
        cart = $(".cart"),
        wishlist = $(".wishlist"),
        ecommerceOrderOptions = $("#ecommerce-order-options");

    var filter = {
        order: "desc",
        search: "",
        brands: [],
        category: "all",
        min_price: parseInt($("#price-slider").data("min")),
        max_price: parseInt($("#price-slider").data("max"))
    }

    function do_filter(filter){
        $('#ecommerce-products').html($("#loading").html());

        var url = "product";
        $.ajax({
            url: url,
            data:{
                filter: filter
            },
            success: (function (message) {
                var response = JSON.parse(message);
                $('#ecommerce-products').html(response.data);
                $('#products-pagination').html(response.pagination);
                $('#product-total').html(response.total);
            }),
            error: function (xhr, status, error) {
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
        window.history.pushState("", "", url);
    }


    // show sidebar
    sidebarToggler.on("click", function () {
        sidebarShop.toggleClass("show");
        shopOverlay.toggleClass("show");
    });

    // remove sidebar
    $(".shop-content-overlay, .sidebar-close-icon").on("click", function () {
        sidebarShop.removeClass("show");
        shopOverlay.removeClass("show");
    })

    //order option
    ecommerceOrderOptions.change(function(){
        filter.order = $(this).val();
        do_filter(filter);
    });

    //price slider
    var slider = document.getElementById("price-slider");
    if (slider) {
        noUiSlider.create(slider, {
            start: [parseInt($("#price-slider").data("min")), parseInt($("#price-slider").data("max"))],
            direction: direction,
            connect: true,
            tooltips: [true, true],
            format: wNumb({
                decimals: 0,
                thousand: '.'
            }),
            range: {
                "min": parseInt($("#price-slider").data("min")),
                "max": parseInt($("#price-slider").data("max"))
            }
        });

        slider.noUiSlider.on('change', function ( values, handle ) {
            filter.min_price = wNumb({
                decimals: 0,
                thousand: '.'
            }).from(values[0]);
            filter.max_price = wNumb({
                decimals: 0,
                thousand: '.'
            }).from(values[1])
            do_filter(filter);
        });
    }
    // for select in ecommerce header
    if (priceFilter.length > 0) {
        priceFilter.select2({
            minimumResultsForSearch: 0,
            dropdownAutoWidth: true,
            width: '100%'
        });
    }

    /***** CHANGE VIEW *****/
    // Grid View
    gridViewBtn.on("click", function () {
        ecommerceProducts.removeClass("list-view").addClass("grid-view");
        listViewBtn.removeClass("active");
        gridViewBtn.addClass("active");
    });

    // List View
    listViewBtn.on("click", function () {
        ecommerceProducts.removeClass("grid-view").addClass("list-view");
        gridViewBtn.removeClass("active");
        listViewBtn.addClass("active");
    });

    // For View in cart
    cart.on("click", function () {
        var $this = $(this),
            addToCart = $this.find(".add-to-cart"),
            viewInCart = $this.find(".view-in-cart");
        if(addToCart.is(':visible')) {
            addToCart.addClass("d-none");
            viewInCart.addClass("d-inline-block");
        }
        else{
            var href= viewInCart.attr('href');
            window.open(href);
        }
    });

    $(".view-in-cart").on('click', function(e){
        e.preventDefault();
    });

    // For Wishlist Icon
    wishlist.on("click", function () {
        var $this = $(this)
        // $this.find("i").toggleClass("fa-heart-o fa-heart")
        // $this.toggleClass("added");
    })

    // Checkout Wizard
    var checkoutWizard = $(".checkout-tab-steps"),
        checkoutValidation = checkoutWizard.show();
    if (checkoutWizard.length > 0) {
        $(checkoutWizard).steps({
            headerTag: "h6",
            bodyTag: "fieldset",
            transitionEffect: "fade",
            titleTemplate: '<span class="step">#index#</span> #title#',
            enablePagination: false,
            onStepChanging: function (event, currentIndex, newIndex) {
                // allows to go back to previous step if form is
                if (currentIndex > newIndex) {
                    return true;
                }
                // Needed in some cases if the user went back (clean up)
                if (currentIndex < newIndex) {
                    // To remove error styles
                    checkoutValidation.find(".body:eq(" + newIndex + ") label.error").remove();
                    checkoutValidation.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                }
                // check for valid details and show notification accordingly
                if (currentIndex === 1 && Number($(".form-control.required").val().length) < 1) {
                    toastr.warning('Error', 'Please Enter Valid Details', { "positionClass": "toast-bottom-right" });
                }
                checkoutValidation.validate().settings.ignore = ":disabled,:hidden";
                return checkoutValidation.valid();
            },
        });
        // to move to next step on place order and save address click
        $(".place-order, .delivery-address").on("click", function () {
            $(".checkout-tab-steps").steps("next", {});
        });
        // check if user has entered valid cvv
        $(".btn-cvv").on("click", function () {
            if ($(".input-cvv").val().length == 3) {
                toastr.success('Success', 'Payment received Successfully', { "positionClass": "toast-bottom-right" });
            }
            else {
                toastr.warning('Error', 'Please Enter Valid Details', { "positionClass": "toast-bottom-right" });
            }
        })
    }

    // checkout quantity counter
    var quantityCounter = $(".quantity-counter"),
        CounterMin = 1,
        CounterMax = 10;
    if (quantityCounter.length > 0) {
        quantityCounter.TouchSpin({
            min: CounterMin,
            max: CounterMax
        }).on('touchspin.on.startdownspin', function () {
            var $this = $(this);
            $('.bootstrap-touchspin-up').removeClass("disabled-max-min");
            if ($this.val() == 1) {
                $(this).siblings().find('.bootstrap-touchspin-down').addClass("disabled-max-min");
            }
        }).on('touchspin.on.startupspin', function () {
            var $this = $(this);
            $('.bootstrap-touchspin-down').removeClass("disabled-max-min");
            if ($this.val() == 10) {
                $(this).siblings().find('.bootstrap-touchspin-up').addClass("disabled-max-min");
            }
        });
    }

    // remove items from wishlist page
    $(".remove-wishlist , .move-cart").on("click", function () {
        $(this).closest(".ecommerce-card").remove();
    })

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();

        $('#ecommerce-products').html($("#loading").html());

        var url = $(this).attr('href');
        $.ajax({
            url: url,
            data:{
                filter: filter
            },
            success: (function (message) {
                var response = JSON.parse(message);
                $('#ecommerce-products').html(response.data);
                $('#products-pagination').html(response.pagination);
                $('#product-total').html(response.total);
            }),
            error: function (xhr, status, error) {
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
        window.history.pushState("", "", url);
    }).on('keypress',".search-product",function(event){
        if(event.keyCode == 13){
            filter.search = $(this).val();
            do_filter(filter);
        }
    }).on('click','.category-filter',function(){
        filter.category = $(this).val();
        do_filter(filter);
    }).on('click','.filter-brands',function(){
        let checked = [];
        $("input.filter-brands[type=checkbox]").each(function() {
            if(this.checked ){
                checked.push($(this).val());
            }
        });
        filter.brands = checked;
        do_filter(filter);
    }).on('click','.price-range-filter',function(){
        if($(this).val()==="all"){
            filter.min_price = parseInt($("#price-slider").data("min"));
            filter.max_price = parseInt($("#price-slider").data("max"));
            slider.noUiSlider.updateOptions({
                start: [filter.min_price,filter.max_price],
                range: {
                    'min': filter.min_price,
                    'max': filter.max_price
                }
            });
            do_filter(filter);
        }else{
            filter.min_price = parseInt($(this).data("min"));
            filter.max_price = parseInt($(this).data("max"));
            slider.noUiSlider.updateOptions({
                start: [filter.min_price,filter.max_price],
                range: {
                    'min': filter.min_price,
                    'max': filter.max_price<filter.min_price?1000000000:filter.max_price
                }
            });
            do_filter(filter);
        }
    }).on('click','#detail',function(){
        var hash = $(this).data("hash");
        var title = $(this).data("title");
        $.ajax({
            url: 'product/' + hash,
            type: "GET",
            data: {
                type: "show_product_detail"
            },
            success: (function (view) {

                Swal.fire({
                    html:view,
                    showCancelButton: false,
                    showConfirmButton: false
                }).then(function(){
                    $(".carousel").carousel({
                        interval: 2000
                    });
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
        })
    }).on('click','#clear_filter',function(){
        $(".category-filter").prop("checked", false);
        $(".category-filter").first().prop("checked", true);
        $(".price-range-filter").prop("checked", false);
        $(".price-range-filter").first().prop("checked", true);
        $(".filter-brands").prop("checked", false);
        slider.noUiSlider.updateOptions({
            start: [parseInt($("#price-slider").data("min")), parseInt($("#price-slider").data("max"))],
            range: {
                'min': parseInt($("#price-slider").data("min")),
                'max': parseInt($("#price-slider").data("max"))
            }
        });

        filter.min_price = parseInt($("#price-slider").data("min"));
        filter.max_price = parseInt($("#price-slider").data("max"));
        filter.brands = [];
        filter.category ="all";

        do_filter(filter);
    }).on('click','#open',function(){
        window.open($(this).data('href'));
    });

    $.ajax({
        url: 'product/create',
        type: "GET",
        success: (function (view) {
            $("#brands").html(view)
        })
    });
})
// on window resize hide sidebar
$(window).on("resize", function () {
    if ($(window).width() <= 991) {
        $(".sidebar-shop").removeClass("show");
        $(".shop-content-overlay").removeClass("show");
    }
    else {
        $(".sidebar-shop").addClass("show");
    }
});
