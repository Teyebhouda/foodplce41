"use strict";

$(function () {
    $('.grid').isotope({
        filter: '.transition'
    });
});

var $grid = $('.grid').isotope({
    itemSelector: '.element-item',
    layoutMode: 'fitRows',
});
$(document).ready(function () {
    $('a[href="' + $("#path_site").val() + '"]').addClass('active');
});
var filterFns = {
    numberGreaterThan50: function () {
        var number = $(this).find('.number').text();
        return parseInt(number, 10) > 50;
    },
    ium: function () {
        var name = $(this).find('.name').text();
        return name.match(/ium$/);
    }
};
$('.filters-select').on('change', function () {
    var filterValue = this.value;
    filterValue = filterFns[filterValue] || filterValue;
    $grid.isotope({
        filter: filterValue
    });
});
function disablebtn(){
    alert("This action is disabled in demo.");
}
$(function () {

    var filterList = {

        init: function () {

            $('#portfoliolist').mixItUp({
                selectors: {
                    target: '.portfolio',
                    filter: '.filter'
                },
                load: {
                    filter: '.1'
                }
            });

        }

    };


    filterList.init();

});


$(document).ready(function () {
    $("#content-slider").lightSlider({
        loop: true,
        keyPress: true
    });
    $('#image-gallery').lightSlider({
        gallery: true,
        item: 1,
        thumbItem: 9,
        slideMargin: 0,
        speed: 500,
        auto: true,
        loop: true,
        onSliderLoad: function () {
            $('#image-gallery').removeClass('cS-hidden');
        }
    });
});
$(document).ready(function () {

    $("#owl-demo").owlCarousel({
        navigation: true
    });

});

function changetab() {
    $(this).toggleClass("on");
    $("#menu").slideToggle();
}


$('.responsive').slick({
    dots: true,
    prevArrow: $('.prev'),
    nextArrow: $('.next'),
    infinite: false,
    speed: 300,
    rtl: rtl_slick(),
    slidesToShow: 7,
    slidesToScroll: 4,
    responsive: [{
            breakpoint: 1024,
            settings: {
                slidesToShow: 7,
                slidesToScroll: 3,
                infinite: true,
                dots: true
            }
        }, {
            breakpoint: 767,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
                infinite: true,
                dots: true
            }
        }, {
            breakpoint: 576,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2
            }
        }, {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
        }

    ]
});

function checkboth(val) {
    var pwd = $("input[name='password_reg']").val();
    if (val != pwd) {
        alert($("#pwdmatch").val());
        $("input[name='password_reg']").val("");
        $("input[name='con_password_reg']").val("");
    }
}

function checkbothpwd(val) {
    var npwd = $("input[name='npwd']").val();
    if (npwd != val) {
        alert($("#pwdmatch").val());
        $("input[name='npwd']").val("");
        $("input[name='rpwd']").val("");
    }
}

function checkcurrentpwd(val) {
    $.ajax({
        url: $("#path_site").val() + "/checkuserpassword" + "/" + val,
        success: function (data) {
            if (data == 0) {
                alert($("#error_cut_pwd").val());
                $("input[name='opwd']").val("");
            }
        }
    });
}

function cancelpwd() {
    $("input[name='npwd']").val("");
    $("input[name='rpwd']").val("");
    $("input[name='opwd']").val("");
}

function changepassword() {
    var npwd = $("input[name='npwd']").val();
    var opwd = $("input[name='opwd']").val();
    $.ajax({
        url: $("#path_site").val() + "/changeuserpwd",
        method: "GET",
        data: {
            npwd: npwd,
            opwd: opwd,
        },
        success: function (data) {
            $("input[name='npwd']").val("");
            $("input[name='rpwd']").val("");
            $("input[name='opwd']").val("");
            $('#contact').addClass('active');
            var txt = '<div class="col-sm-12"><div class="alert  alert-success alert-dismissible fade show" role="alert">Password Change Successfully<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div>';
            document.getElementById("msgres").innerHTML = txt;
        }
    });
}

function changecategory(val,id) {
    var total=$("#totalcategory").val();
    document.getElementById("category_div").style.display = "block";
    document.getElementById("main_content").style.display = "none";
    
    $.ajax({
        url: $("#path_site").val() + "/category" + "/" + val,
        method: "GET",
        success: function (result) {
             for(var i=0;i<total;i++){;
                $("#cat1"+i).removeClass('slick-slide active');
                $("#catdiv"+i).removeClass('slick-slide active');
            }
            $("#cat1"+id).addClass('slick-slide active');
            $("#catdiv"+id).addClass('slick-slide active');
            var res = JSON.parse(result);
            var data = res.item;
            var txt = "";
            console.log(data);
            txt=txt+'<div class="row">';
            var path = $("#path_site").val();
              for (var i = 0; i < data.length; i++) {
                 txt=txt+'<div class="portfolio 1 col-md-6 burger w3-container  w3-animate-zoom portfoliocat" data-cat="'+data[i]['id']+'" data-bound><div class="items"><div class="b-img"><a href="' + path + '/detailitem' + "/" + data[i]['id'] + '"><img src="' + path + '/public/upload/images/menu_item_icon' + "/" + data[i]['menu_image'] + '"></a></div><div class="bor"><div class="b-text"><h1><a href="' + path + '/detailitem' + "/" + data[i]['id'] + '">' + data[i]['menu_name'] + '</a></h1><p>' + data[i]['description'] + '</p></div><div class="price"><h1>' + $("#currency").val() + data[i]['price'] + '</h1><div class="cart"><a href="' + path + '/detailitem' + "/" + data[i]['id'] + '">' + $("#addcart").val() + '</a></div></div></div></div></div>';
              }
            txt=txt+'</div>';
            var cat = res.category;
           
            document.getElementById("category_div").innerHTML = txt;
        }
    });

}
$(document).ready(function () {
    $('a[href="/"]').removeClass('active');
    $('a[href="' + window.location.href + '"]').addClass('active');
});

function logout() {
    window.location.href = $("#path_site").val() + "/user_logout";
}

function rtl_slick() {
    if ($("#is_rtl").val()=='1') {
        return true;
    } else {
        return false;
    }
}

function addtocart() {
    var item_id = $("#menu_name").val();
    var item = $("#item_id").val();
    var qty = $("#number").val();
    var price = $('#origin_price').val();
    console.log(price);
    var ingredients = [];
    
    // Collecting values from checkboxes
    $.each($("input[type='checkbox']:checked"), function () {
        ingredients.push($(this).val());
    });

    // Collecting value from selected radio buttons per family
    $.each($("input[type='radio']:checked"), function () {
        ingredients.push($(this).val());
    });
    if (ingredients.length === 0) {
        ingredients.push(""); // or you can push any default value to indicate no selection
    }

    var totalIngredients = ingredients.toString();

    $.ajax({
        url: $("#path_site").val() + "/addcartitem",
        method: "GET",
        data: {
            id: item_id,
            qty: qty,
            totalint: totalIngredients,
            price: price
        },
        success: function (data) {
            document.getElementById("totalcart").innerHTML = data;
            window.location.href = $("#path_site").val() + "/detailitem" + '/' + item;
        }
    });
}

function increaseValue() {
    var value = parseInt(document.getElementById('number').value, 10);
    value = isNaN(value) ? 1 : value;
    value++;
    var getprice = $("#origin_price").val();
    document.getElementById('number').value = value;
    var finalvalues = parseInt(value) * parseFloat(getprice);
    document.getElementById('price').innerHTML = finalvalues.toFixed(2);
}

function decreaseValue() {
    var value = parseInt(document.getElementById('number').value, 10);
    value = isNaN(value) ? 1 : value;
    value < 1 ? value = 1 : '';
    if (value > 1) {
        value--;
    }
    var getprice = $("#origin_price").val();
    document.getElementById('number').value = value;
    var finalvalues = parseInt(value) * parseFloat(getprice);
    document.getElementById('price').innerHTML = finalvalues.toFixed(2);
}

function changemodel() {
    $('a[href="#tab1').removeClass('active');
    $('a[href="#tab2').addClass('active');
    $('#tab1').removeClass('active');
    $('#tab2').addClass('active');
    $("#myModal1").model("show");
}

function addtocartsingle(item_id) {
    var qty = 1;
    var totalint = 0;
    $.ajax({
        url: $("#path_site").val() + "/addcartitem",
        method: "GET",
        data: {
            id: item_id,
            qty: qty,
            totalint: totalint
        },
        success: function (data) {
            document.getElementById("totalcart").innerHTML = data;
            window.location.reload();
        }
    });
}

function shareonsoical(val, id) {
    $.ajax({
        url: $("#path_site").val() + "/sharesoicalmedia" + "/" + val + "/" + id,
        method: "GET",
        success: function (data) {
            if (val == 1) {
                window.open('https://www.facebook.com/sharer/sharer.php?u='+$("#path_site").val()+'/detailitem/' + id + '', '_blank');
                document.getElementById("facebook_share_id").innerHTML = data;
            }
            if (val == 2) {
                window.open('https://twitter.com/intent/tweet?text=my share text&amp;url='+$("#path_site").val()+'/detailitem/' + id + '', '_blank');
                document.getElementById("twitter_share_id").innerHTML = data;
            }

        }
    });
}

function checkcart() {
    var cartttotal = $("#carttotal").val();
    if (carttotal != 0) {
        window.location.href = $("#path_site").val() + "/cartdetails";
    }
}


function registeruser() {
      document.getElementById("reg_error_msg").style.display = "none";
        document.getElementById("reg_success_msg").style.display = "none";
     $(document).ajaxSend(function() {
           $("#overlaychk").fadeIn(300);　
     });
    var name = $("input[name='name']").val();
    var phone = $("input[name='phone_reg']").val();
    var address = $("input[name='address']").val();
    var password = $("input[name='password_reg']").val();
    var email = $("input[name='email']").val();
    var conn = $("input[name='con_password_reg']").val();
    if (name != "" && phone != "" && password != "" && conn != ""&&email!="") {
        $.ajax({
            url: $("#path_site").val() + "/userregister",
            method: "GET",
            data: {
                name: name,
                phone: phone,
                address: address,
                password: password,
                email:email
            },
            success: function (data) {
                if (data == 1) {
                      $.ajax({
                                    url: $("#path_site").val() + "/userlogin",
                                    method: "GET",
                                    data: {
                                        phone: phone,
                                        password: password,
                                        rem_me: 0
                                    },
                                    success: function (data) {
                                        if (data == 1) {
                                            var url1 = window.location.href;
                                            var url2 = $("#path_site").val() + "/home";
                                            var n = url1.localeCompare(url2);
                                            console.log(n);
                                            if (n == 0) {
                                                window.location.href = $("#path_site").val() + "/myaccount";
                                            } else {
                                                window.location.reload();
                                            }
                        
                                        } else {
                                            document.getElementById("login_error_msg").innerHTML = $("#login_error").val();
                                            document.getElementById("login_error_msg").style.display = "block";
                                        }
                                    }
                    });
                } else {
                    document.getElementById("reg_error_msg").innerHTML = $("#reg_error").val();
                    document.getElementById("reg_error_msg").style.display = "block";
                    document.getElementById("reg_success_msg").style.display = "none";
                }
            }
        });
    } else {
        document.getElementById("reg_error_msg").style.display = "block";
        document.getElementById("reg_success_msg").style.display = "none";
        console.log($("#reg_error").val());
        document.getElementById("reg_error_msg").innerHTML = $("#reg_error").val();

    }
    $("#overlaychk").fadeOut(1000);
}

function checkdata(val) {
    var pwd = $("input[name='password_reg']").val();
    if (val != pwd) {
        alert($("#pwdmatch").val());
        $("input[name='password_reg']").val("");
        $("input[name='con_password_reg']").val("");
    }
}

function loginaccount() {
     document.getElementById("login_error_msg").style.display = "none";
     $(document).ajaxSend(function() {
    $("#overlaychk").fadeIn(300);　
  });
    var phone = $("input[name='phone']").val();
    var password = $("input[name='password']").val();
    if ($("input[name='rem_me']").prop("checked") == true) {
        var rem_me = 1;
    } else {
        var rem_me = 0;
    }

    if (phone != "" && password != "") {
        $.ajax({
            url: $("#path_site").val() + "/userlogin",
            method: "GET",
            data: {
                phone: phone,
                password: password,
                rem_me: rem_me
            },
            success: function (data) {
                if (data == 1) {
                    var url1 = window.location.href;
                    var url2 = $("#path_site").val() + "/home";
                    var n = url1.localeCompare(url2);
                    console.log(n);
                    if (n == 0) {
                        window.location.href = $("#path_site").val() + "/myaccount";
                    } else {
                        window.location.reload();
                    }

                } else {
                    document.getElementById("login_error_msg").innerHTML = $("#login_error").val();
                    document.getElementById("login_error_msg").style.display = "block";
                }
            }
        });
    } else {
        document.getElementById("login_error_msg").innerHTML = $("#required_field").val();
        document.getElementById("login_error_msg").style.display = "block";
    }
    $("#overlaychk").fadeOut(1000);
}

function checkloginaccount() {
    var phone = $("input[name='phone_check']").val();
    var password = $("input[name='password_check']").val();
    if ($("input[name='rem_me_check']").prop("checked") == true) {
        var rem_me = 1;
    } else {
        var rem_me = 0;
    }

    if (phone != "" && password != "") {
        $.ajax({
            url: $("#path_site").val() + "/userlogin",
            method: "GET",
            data: {
                phone: phone,
                password: password,
                rem_me: rem_me
            },
            success: function (data) {
                if (data == 1) {
                    var url1 = window.location.href;
                    var url2 = $("#path_site").val() + "/home";
                    var n = url1.localeCompare(url2);
                    console.log(n);
                    if (n == 0) {
                        window.location.href = $("#path_site").val() + "/myaccount";
                    } else {
                        window.location.reload();
                    }

                } else {
                    document.getElementById("check_login_error_msg").innerHTML = $("#login_error").val();
                    document.getElementById("check_login_error_msg").style.display = "block";
                }
            }
        });
    } else {
        document.getElementById("check_login_error_msg").innerHTML = $("#required_field").val();
        document.getElementById("check_login_error_msg").style.display = "block";
    }
}

function forgotmodel() {
    document.getElementById('forgotbody').style.display = "block";
    document.getElementById('loginmodel').style.display = "none";
}

function loginmodel() {
    document.getElementById('forgotbody').style.display = "none";
    document.getElementById('loginmodel').style.display = "block";
}

function forgotaccount() {
    var phone = $("input[name='phone_for']").val();
    if (phone != "") {
        $.ajax({
            url: $("#path_site").val() + "/forgotpassword",
            method: "GET",
            data: {
                phone: phone
            },
            success: function (data) {
                console.log(data);
                if (data == 1) {
                    document.getElementById("for_success_msg").style.display = "block";
                    document.getElementById("for_error_msg").style.display = "none";
                } else {
                    document.getElementById("for_error_msg").innerHTML = $("#forgot_error").val();
                    document.getElementById("for_error_msg").style.display = "block";
                    document.getElementById("for_success_msg").style.display = "none";
                }
            }
        });
    } else {
        document.getElementById("for_error_msg").innerHTML = $("#forgot_error_2").val();
        document.getElementById("for_error_msg").style.display = "block";
        document.getElementById("for_success_msg").style.display = "none";

    }

}


function changeoptioncart(val) {
    if (val == 0) {
        document.getElementById("home1").checked = true;
        document.getElementById("home2").checked = false;
    }
    if (val == 1) {
        document.getElementById("home2").checked = true;
        document.getElementById("home1").checked = false;
    }
}

function addqty(id, iqty) {
    var qty = $("input[name='qty" + iqty + "']").val();
    var nqty = parseInt(qty) + 1;
    var price = document.getElementById("price_pro" + id).innerHTML;
    $("input[name='qty" + iqty + "']").val(nqty);
    $.ajax({
        url: $("#path_site").val() + "/cartqtyupdate" + "/" + id + "/" + nqty + "/1",
        data: {},
        success: function (data) {
            var pricedata = parseFloat(nqty) * parseFloat(price);
            document.getElementById("producttotal" + id).innerHTML = pricedata.toFixed(2);
            document.getElementById("finaltotal").innerHTML = data.finaltotal;
            document.getElementById("subtotal").innerHTML = data.subtotal;
        }
    });

}

function minusqty(id, iqty) {
    var qty = $("input[name='qty" + iqty + "']").val();
    var nqty = parseInt(qty) - 1;
    var price = document.getElementById("price_pro" + id).innerHTML;
    $("input[name='qty" + iqty + "']").val(nqty);
    if (nqty != 0) {
        $.ajax({
            url: $("#path_site").val() + "/cartqtyupdate" + "/" + id + "/" + nqty + "/0",
            data: {},
            success: function (data) {
                var pricedata = parseFloat(nqty) * parseFloat(price);
                document.getElementById("producttotal" + id).innerHTML = pricedata.toFixed(2);
                document.getElementById("finaltotal").innerHTML = data.finaltotal;
                document.getElementById("subtotal").innerHTML = data.subtotal;
            }
        });
    } else {
        $.ajax({
            url: $("#path_site").val() + "/deletecartitem" + "/" + id,
            data: {},
            success: function (data) {
                window.location.reload();
            }
        });
    }

}


function changebutton(val) {
    var phone = $("#order_phone").val();
    var city = $("#order_city").val();
      address = $("#us2-address").val();
    // CodePostal = $("#order_postal").val();
    var delivery_time = $("#delivery_time").val();

    var address = $("#us2-addres").val();
    if (val == "Cash" || val == "by Card") {
        document.getElementById("orderplace1").style.display = "block";
        document.getElementById("orderplacestrip").style.display = "none";
        document.getElementById("orderplacepaypal").style.display = "none";
        $("#pay1").addClass('activepayment');
        $("#pay2").removeClass('activepayment');
        $("#pay3").removeClass('activepayment');
        $("#order_payment_type_1").prop("checked", true);
        $("#order_payment_type_3").prop("checked", false);
        $("#order_payment_type_4").prop("checked", false);
    }
    if (val == "Stripe") {
        var totalprice = document.getElementById("finaltotal_order").innerHTML;
        $("script").each(function () {
            $(this).attr('data-amount', totalprice);
        })
        $("#phone_or").val($("#order_phone").val());
        $("#note_or").val($("#order_notes").val());
        $("#city_or").val($("#order_city").val());
        $("#payment_type_or").val("Stripe");
        $("#shipping_type_or").val($("input[name='shipping_type']").val());
        $('#total_price_or').val(totalprice);
        $('#subtotal_or').val(document.getElementById("subtotal_order").innerHTML);
        
        if ($("#home1").prop("checked") == true) {
            shipping_type = 0;
           var  address = $("#us2-address").val();
            // latlong = $("#us2-lat").val() + "," + $("#us2-lon").val();
             var CodePostal = $("#order_postal").val();
    
    
            if (phone !== "" && city !== ""  && CodePostal !== "" && address !== "" ) {
             if ($("#home1").prop("checked") == true) {
                var shipping_type = 0;
                $("#shipping_type_or").val(0);
                $("#address_or").val($("#us2-address").val());
                // $("#lat_long_or").val($("#us2-lat").val() + "," + $("#us2-lon").val());
                // $('#charage_or').val(document.getElementById("delivery_charges_order").innerHTML);
            } else if ($("#home2").prop("checked") == true) {
                var shipping_type = 1;
                $("#shipping_type_or").val(1);
            }

            if (shipping_type == 0 && $("#address_or").val() == "" && $("#order_postal").val() == "" ) {
                $("#order_payment_type_4").prop("checked", false);
                alert($("#required_field").val()); 
            } 
                document.getElementById("orderplace1").style.display = "none";
                document.getElementById("orderplacestrip").style.display = "block";
                document.getElementById("orderplacepaypal").style.display = "none";
                $("#pay1").removeClass('activepayment');
                $("#pay2").removeClass('activepayment');
                $("#pay3").addClass('activepayment');
                $("#order_payment_type_1").prop("checked", false);
                $("#order_payment_type_3").prop("checked", false);
                $("#order_payment_type_4").prop("checked", true);
           
        } else {
            $("#order_payment_type_4").prop("checked", false);
            alert($("#required_field").val());

        }
      }  else if ($("#home2").prop("checked") == true) {
            shipping_type = 1;
            address = "";
            // latlong = "";
          
            if (phone !== "" && city !== ""  && delivery_time !== ""  ) {
                if ($("#home1").prop("checked") == true) {
                    var shipping_type = 0;
                    $("#shipping_type_or").val(0);
                    $("#address_or").val($("#us2-address").val());
                    $("#lat_long_or").val($("#us2-lat").val() + "," + $("#us2-lon").val());
                    $('#charage_or').val(document.getElementById("delivery_charges_order").innerHTML);
                } else if ($("#home2").prop("checked") == true) {
                    var shipping_type = 1;
                    $("#shipping_type_or").val(1);
                }
    
                if (shipping_type == 0 && $("#address_or").val() == "" && $("#order_postal").val() == "" ) {
                    $("#order_payment_type_4").prop("checked", false);
                    alert($("#required_field").val()); 
                } 
                    document.getElementById("orderplace1").style.display = "none";
                    document.getElementById("orderplacestrip").style.display = "block";
                    document.getElementById("orderplacepaypal").style.display = "none";
                    $("#pay1").removeClass('activepayment');
                    $("#pay2").removeClass('activepayment');
                    $("#pay3").addClass('activepayment');
                    $("#order_payment_type_1").prop("checked", false);
                    $("#order_payment_type_3").prop("checked", false);
                    $("#order_payment_type_4").prop("checked", true);
               
            } else {
                $("#order_payment_type_4").prop("checked", false);
                alert($("#required_field").val());
    
            } 

    }
    else {
        $("#order_payment_type_4").prop("checked", false);
        alert($("#required_field").val());

    } 
}
    if (val == "Paypal") {
        var totalprice = document.getElementById("finaltotal_order").innerHTML;
        $("#phone_pal").val($("#order_phone").val());
        $("#note_pal").val($("#order_notes").val());
        $("#city_pal").val($("#order_city").val());
        $("#payment_type_pal").val("Paypal");
        $('#total_price_pal').val(totalprice);
        $('#subtotal_pal').val(document.getElementById("subtotal_order").innerHTML);

        if ($("#home1").prop("checked") == true) {
            shipping_type = 0;
           var  address = $("#us2-address").val();
            // latlong = $("#us2-lat").val() + "," + $("#us2-lon").val();
             var CodePostal = $("#order_postal").val();
    
    
            if (phone !== "" && city !== ""  && CodePostal !== "" && address !== "" ) {

       
            document.getElementById("orderplace1").style.display = "none";
            document.getElementById("orderplacestrip").style.display = "none";
            document.getElementById("orderplacepaypal").style.display = "block";
            $("#pay1").removeClass('activepayment');
            $("#pay2").addClass('activepayment');
            $("#pay3").removeClass('activepayment');
            $("#order_payment_type_1").prop("checked", false);
            $("#order_payment_type_3").prop("checked", true);
            $("#order_payment_type_4").prop("checked", false);
            
            if ($("#home1").prop("checked") == true) {
                var shipping_type = 0;
                $("#shipping_type_pal").val(0);
                $("#address_pal").val($("#us2-address").val());
                $("#lat_long_pal").val($("#us2-lat").val() + "," + $("#us2-lon").val());
                $('#charage_pal').val(document.getElementById("delivery_charges_order").innerHTML);
            } else if ($("#home2").prop("checked") == true) {
                var shipping_type = 1;
                $("#shipping_type_pal").val(1);
            }

        } else {
            $("#order_payment_type_3").prop("checked", false);
            alert($("#required_field").val());

        }

    }
  else if ($("#home2").prop("checked") == true) {
    shipping_type = 1;
    address = "";
    // latlong = "";
  
    if (phone !== "" && city !== ""  && delivery_time !== ""  ) {
        document.getElementById("orderplace1").style.display = "none";
        document.getElementById("orderplacestrip").style.display = "none";
        document.getElementById("orderplacepaypal").style.display = "block";
        $("#pay1").removeClass('activepayment');
        $("#pay2").addClass('activepayment');
        $("#pay3").removeClass('activepayment');
        $("#order_payment_type_1").prop("checked", false);
        $("#order_payment_type_3").prop("checked", true);
        $("#order_payment_type_4").prop("checked", false);
        
        if ($("#home1").prop("checked") == true) {
            var shipping_type = 0;
            $("#shipping_type_pal").val(0);
            $("#address_pal").val($("#us2-address").val());
            $("#lat_long_pal").val($("#us2-lat").val() + "," + $("#us2-lon").val());
            $('#charage_pal').val(document.getElementById("delivery_charges_order").innerHTML);
        } else if ($("#home2").prop("checked") == true) {
            var shipping_type = 1;
            $("#shipping_type_pal").val(1);
        }

    } else {
        $("#order_payment_type_3").prop("checked", false);
        alert($("#required_field").val());

    }
}
    else {
        $("#order_payment_type_3").prop("checked", false);
        alert($("#required_field").val());

    } 




}
}
function changeoption(val) {
    var subtotal = $("#subtotalorder").val();
    var discharges = $("#delivery_charges").val();
    var deliveryTimeContainer = document.getElementById('delivery-time-container');
    if (val == 0) {
        document.getElementById("home1").checked = true;
        document.getElementById("home2").checked = false;
       // document.getElementById("maporder").style.display = "block";
        document.getElementById("addressorder").style.display = "block";
        document.getElementById("postalorder").style.display = "block";
        document.getElementById("dcorder").style.display = "block";
        document.getElementById("finaltotal_order").innerHTML = parseFloat(subtotal) + parseFloat(discharges);
        
        deliveryTimeContainer.style.display = 'none';
       
    }
    if (val == 1) {
       
        document.getElementById("home2").checked = true;
        document.getElementById("home1").checked = false;
      //  document.getElementById("maporder").style.display = "none";
        document.getElementById("addressorder").style.display = "none";
        
        document.getElementById("postalorder").style.display = "none";

        document.getElementById("dcorder").style.display = "none";
        document.getElementById("finaltotal_order").innerHTML = parseFloat(subtotal);
        deliveryTimeContainer.style.display = 'block';
       

       
           
            
          
            }
    
                const now = new Date();
                    const currentHour = now.getHours();
                    const currentMinute = now.getMinutes();
        
                    for (let hour = currentHour; hour <= 23; hour++) {
                        for (let minute = 0; minute <= 45; minute += 15) {
        
                            const formattedHour = hour.toString().padStart(2, '0');
                            const formattedMinute = minute.toString().padStart(2, '0');
                            
                            const option = document.createElement('option');
                            option.value = `${formattedHour}:${formattedMinute}`;
                            option.text = `${formattedHour}:${formattedMinute}`;
        
                            document.getElementById('delivery_time').appendChild(option);
                        }
                    }

        
    }


function orderplace() {
   
    // var button = document.querySelector('button[onclick="orderplace()"]');
    // button.disabled = true;
    console.log("button clicked!!!");
    var phone = $("#order_phone").val();
    var name = $("#user_name").val();
    var user_address = $("#user_address").val();
    var note = $("#order_notes").val();
    var city = $("#order_city").val();
    

    var address = $("#us2-addres").val();
    var payment_type = 'Cash';
    var totalprice = document.getElementById("finaltotal_order").innerHTML;
    var subtotal = document.getElementById("subtotal_order").innerHTML;
    var charge = document.getElementById("delivery_charges_order").innerHTML;
    var typedata = "";
var delivery_time = "";
var CodePostal = "";
    var shipping_type, address, latlong, CodePostal;

    if ($("#home1").prop("checked") == true) {
        shipping_type = 0;
        address = $("#us2-address").val();
        latlong = $("#us2-lat").val() + "," + $("#us2-lon").val();
        CodePostal = $("#order_postal").val();


        if (phone !== "" && city !== "" && payment_type !== "" && CodePostal !== "" && address !== "" ) {
            var button = document.getElementById("orderplace1Btn");
            button.disabled = true;
            button.style.opacity = 0.5;
            var nameParts = name.split(" ");
            var firstName = nameParts[0]; // First part is the first name
            var lastName = nameParts.slice(1).join(" "); // Rest is the last name (if any)
        
            var newUserData = {
                Civilité: 0,
                Nom: firstName,
                Prénom: lastName,
                Adresse: user_address,
                CodePostal: "",
                Ville: city,
                Téléphone: phone,
                Mobile: phone,
                RIB: "",
                Cin: "",
                solde: 0
            };
    
            var apiUrl = 'https://api.alaindata.com/foodplace41/Client';
    
            fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(newUserData),
            })
           
                .then(response => response.text())
                .then(checkdata => {
                    
                        const idStartIndex = checkdata.indexOf('"IDClient":') + '"IDClient":'.length;
                        const idEndIndex = checkdata.indexOf(',', idStartIndex) !== -1 ? checkdata.indexOf(',', idStartIndex) : checkdata.indexOf('}', idStartIndex);
                        const idValue1 = checkdata.substring(idStartIndex, idEndIndex);
                       
                        //const idValue = data[0].IDClient;
    
                        var newCommandData = {
                            IDClient: idValue1,
                            NuméroInterneCommande: generateUniqueNumber(),
                            DateCommande: getCurrentDate(),
                            TotalTTC: totalprice,
                            // Other command data
                        };
    
                        var commandApiUrl = 'https://api.alaindata.com/foodplace41/Commande';
    
                        fetch(commandApiUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(newCommandData),
                        })
                            .then(response => response.text())
                            .then(commandData => {
                                const idStartIndex = commandData.indexOf('"IDCommande":') + '"IDCommande":'.length;
                                const idEndIndex = commandData.indexOf(',', idStartIndex) !== -1 ? commandData.indexOf(',', idStartIndex) : commandData.indexOf('}', idStartIndex);
                                const Idcomande = commandData.substring(idStartIndex, idEndIndex);
                             
                                if (commandData !== 0) {
                                    $.ajax({
                                        url: $("#path_site").val() + "/placeorder",
                                        method: "GET",
                                        data: {
                                            Idcomande: Idcomande,
                                            phone: phone,
                                            note: note,
                                            city: city,
                                            address: address + ' ' + CodePostal,
                                            payment_type: payment_type,
                                            shipping_type: shipping_type,
                                            totalprice: totalprice,
                                            subtotal: subtotal,
                                            charge: charge,
                                            latlong: latlong,
                                           delivery_time: delivery_time 
                                        },
                                        success: function (data1) {
                                            if (data1 != 0) {
                                                window.location.href = $("#path_site").val() + "/viewdetails" + "/" + data1;
                                            }
                                        }
                                    });                            }
                            });
                    
                }); 
        } else {
            // Code for handling empty fields
            document.getElementById("orderplace1").style.display = "none"
            document.getElementById("orderplacestrip").style.display = "none";
            document.getElementById("orderplacepaypal").style.display = "none";
            $("#pay1").removeClass('activepayment');
            $("#pay2").removeClass('activepayment');
            $("#pay3").removeClass('activepayment');
            $("#order_payment_type_1").prop("checked", false);
            $("#order_payment_type_3").prop("checked", false);
            $("#order_payment_type_4").prop("checked", false);
            alert($("#required_field").val());
        }


    }

   else if ($("#home2").prop("checked") == true) {
        shipping_type = 1;
        address = "";
        latlong = "";
        delivery_time = $("#delivery_time").val();
        if (phone !== "" && city !== "" && payment_type !== "" && delivery_time !== ""  ) {
            var button = document.getElementById("orderplace1Btn");
            button.disabled = true;
            button.style.opacity = 0.5;
            var nameParts = name.split(" ");
            var firstName = nameParts[0]; // First part is the first name
            var lastName = nameParts.slice(1).join(" "); // Rest is the last name (if any)
        
            var newUserData = {
                Civilité: 0,
                Nom: firstName,
                Prénom: lastName,
                Adresse: user_address,
                CodePostal: "",
                Ville: city,
                Téléphone: phone,
                Mobile: phone,
                RIB: "",
                Cin: "",
                solde: 0
            };
    
            var apiUrl = 'https://api.alaindata.com/foodplace41/Client';
    
            fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(newUserData),
            })
           
                .then(response => response.text())
                .then(checkdata => {
                    
                        const idStartIndex = checkdata.indexOf('"IDClient":') + '"IDClient":'.length;
                        const idEndIndex = checkdata.indexOf(',', idStartIndex) !== -1 ? checkdata.indexOf(',', idStartIndex) : checkdata.indexOf('}', idStartIndex);
                        const idValue1 = checkdata.substring(idStartIndex, idEndIndex);
                       
                        //const idValue = data[0].IDClient;
    
                        var newCommandData = {
                            IDClient: idValue1,
                            NuméroInterneCommande: generateUniqueNumber(),
                            DateCommande: getCurrentDate(),
                            TotalTTC: totalprice,
                            // Other command data
                        };
    
                        var commandApiUrl = 'https://api.alaindata.com/foodplace41/Commande';
    
                        fetch(commandApiUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(newCommandData),
                        })
                            .then(response => response.text())
                            .then(commandData => {
                                const idStartIndex = commandData.indexOf('"IDCommande":') + '"IDCommande":'.length;
                                const idEndIndex = commandData.indexOf(',', idStartIndex) !== -1 ? commandData.indexOf(',', idStartIndex) : commandData.indexOf('}', idStartIndex);
                                const Idcomande = commandData.substring(idStartIndex, idEndIndex);
                             
                                if (commandData !== 0) {
                                    $.ajax({
                                        url: $("#path_site").val() + "/placeorder",
                                        method: "GET",
                                        data: {
                                            Idcomande: Idcomande,
                                            phone: phone,
                                            note: note,
                                            city: city,
                                            address: address + ' ' + CodePostal,
                                            payment_type: payment_type,
                                            shipping_type: shipping_type,
                                            totalprice: totalprice,
                                            subtotal: subtotal,
                                            charge: charge,
                                            latlong: latlong,
                                           delivery_time: delivery_time 
                                        },
                                        success: function (data1) {
                                            if (data1 != 0) {
                                                window.location.href = $("#path_site").val() + "/viewdetails" + "/" + data1;
                                            }
                                        }
                                    });                            }
                            });
                    
                }); 
        } else {
            // Code for handling empty fields
            document.getElementById("orderplace1").style.display = "none"
            document.getElementById("orderplacestrip").style.display = "none";
            document.getElementById("orderplacepaypal").style.display = "none";
            $("#pay1").removeClass('activepayment');
            $("#pay2").removeClass('activepayment');
            $("#pay3").removeClass('activepayment');
            $("#order_payment_type_1").prop("checked", false);
            $("#order_payment_type_3").prop("checked", false);
            $("#order_payment_type_4").prop("checked", false);
            alert($("#required_field").val());
        }
    }
else{
    alert($("#required_field").val());

}

// document.getElementById("orderplace1Btn").disabled = false;  
}



function generateUniqueNumber() {
    var min = 10000; // Minimum 5-digit number (inclusive)
    var max = 99999; // Maximum 5-digit number (inclusive)
    var randomNumber = Math.floor(Math.random() * (max - min + 1)) + min;
    return "W" + randomNumber;
}
function getCurrentDate() {
    var today = new Date();
    var year = today.getFullYear();
    var month = String(today.getMonth() + 1).padStart(2, '0'); // Add 1 to month (0-based index) and pad with '0'
    var day = String(today.getDate()).padStart(2, '0'); // Pad day with '0'
    return year + "-" + month + "-" + day;
}




function addprice(price, iqty ,family) {
    console.log(family);
    if (family === "BOISSONS") {
        var isChecked = $('input[name="' + family + '"]:checked').length > 0;
        if (isChecked) {
            $('input[name="' + family + '"]:not(:checked)').prop('disabled', true);
        } else {
            $('input[name="' + family + '"]').prop('disabled', false);
        }
    }
    if ($("#checkbox-" + iqty).prop("checked") == true) {
        console.log("checked");
        var origin_price = $("#origin_price").val();
        var menu_new_price = parseFloat(origin_price) + parseFloat(price);
        $("#origin_price").val(menu_new_price.toFixed(2));
        var pricedata = menu_new_price * parseFloat($('#number').val());
        document.getElementById("price").innerHTML = pricedata.toFixed(2);
        console.log(menu_new_price);
    } else if ($("#checkbox-" + iqty).prop("checked") == false) {
        console.log("unchecked");
        var origin_price = $("#origin_price").val();
        var menu_new_price = parseFloat(origin_price) - parseFloat(price);
        $("#origin_price").val(menu_new_price.toFixed(2));
        var pricedata = menu_new_price * parseFloat($('#number').val());
        document.getElementById("price").innerHTML = pricedata.toFixed(2);
        console.log(menu_new_price);
    }
   $("input[type='radio']:checked").each(function () {
    var price = $(this).data('price');
    console.log(price);
    var origin_price = parseFloat($("#origin_price").val());
    var currentQuantity = parseFloat($('#number').val());
    var isChecked = $(this).prop("checked");

    // if (isChecked) {
    //     console.log("checked");
    //     var parsedPrice = parseFloat(price); // Ensure price is parsed as a float
    //     var menu_new_price = origin_price + parsedPrice;
    //     var roundedPrice = Math.round(menu_new_price * 100) / 100; // Round to two decimal places
    //     $("#origin_price").val(roundedPrice.toFixed(2));

    //     var pricedata = roundedPrice * currentQuantity;
    //     document.getElementById("price").innerHTML = pricedata.toFixed(2);
    //     console.log(roundedPrice.toFixed(2));
    // }
});


}




function createCheckboxChangeHandler(familleOption) {
    if(familleOption =="BOISSONS")
    {
    return function() {
         // Get all checkboxes in this specific familleOption group
         var checkboxes = $('input[name="' + familleOption + '"]');
         
         // Get the number of checkboxes selected in this group
         var selectedCount = checkboxes.filter(':checked').length;
          if (selectedCount >= 1) {
             // Uncheck and disable checkboxes if the limit is exceeded
             checkboxes.filter(':not(:checked)').prop('disabled', true);
         } else {
             // Enable all checkboxes within the group if the limit is not exceeded
             checkboxes.prop('disabled', false);
         }
     };
    }
 }



if($('#us2').length){
  $('#us2').locationpicker({
    location: {
        latitude: $("#lat_env").val(),
        longitude: $("#long_env").val()
    },
    radius: 300,
    inputBinding: {
        latitudeInput: $('#us2-lat'),
        longitudeInput: $('#us2-lon'),
        radiusInput: $('#us2-radius'),
        locationNameInput: $('#us2-address')
    },
    enableAutocomplete: true
});
}
