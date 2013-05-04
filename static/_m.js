
$('#moreinfo,.myinfo').mouseover(function() {
    $('.myinfo').css({
        left: $('#moreinfo').offset().left - 20
    }).show();
    $('#moreinfo').addClass('seemore');
    $('.barrow').removeClass().addClass('uarrow');
}).mouseout(function() {
    $('.myinfo').hide();
    $('#moreinfo').removeClass('seemore');
    $('.uarrow').removeClass().addClass('barrow');
});

$('#tablist').on('click', 'li', function() {
    var mid = $(this).attr('id');
    switch (mid) {
        case 'binfo':
            var showobj = $('#result');
            break;
        case 'einfo':
            var showobj = $('#extra');
            break;
        case 'minfo':
            var showobj = $('#map');
            loadmap(map_s_address, map_msg, mapzoom);
            break;
        case 'winfo1':
            var showobj = $('#whois');
            requestWhois(ip1);
            break;
        case 'winfo2':
            var showobj = $('#whois');
            requestWhois(ip2);
            break;
    }
    showobj.fadeIn().siblings(':not(.resulttab)').hide();
    $(this).addClass('current').siblings().removeClass('current');
    footReplace();
});

(function($) {
    var inputField = $('#ipfield');
    $.fn.checksubmit = function() {
        $obj = $(this);
        this.click(function() {
            var inputValue = inputField.val();

            if (/^([\w]+:\/\/)?([^\/\?\#\:]+)/.test(inputValue)) {
                inputValue = RegExp.$2;
            }
            inputField.val(inputValue);
            var origin = window.location.href;
            window.location.href = origin.slice(0, origin.lastIndexOf('/') + 1) + inputValue;
        }).mouseover(function() {
            $obj.addClass('sbton');
        }).mouseout(function() {
            $obj.removeClass('sbton');
        });
        inputField.keyup(function(e) {
            if (e.keyCode === 13) {
                $obj.click();
            }
        });
    }

    var autofill = $("#autofill");
    $.fn.setautofill = function() {
        this.click(function() {
            var chis = getcookie('search_history');
            if (chis) chis = chis.split('|');
            var boxVal = '<ul><li><a href="javascript:;" class="delautoitem">+删除查询记录+</a></li>'
            for (var i in chis) {
                if (chis[i] != undefined) boxVal += '<li><a href="javascript:;" class="autoitem">' + chis[i] + '</a></li>';
                else break;
            }
            autofill.html(boxVal + '</ul>')
                .mouseover(function() {
                $(this).show()
            }).mouseout(function() {
                $(this).hide()
            }).show();
        }).mouseout(function() {
            autofill.hide();
        });


        $(document).on('click', '.autoitem', function() {
            $('#ipfield').val($(this).text());
            autofill.hide();
        }).on('click', '.delautoitem', function() {
            delcookie('search_history');
            autofill.hide();
        });
    }
})(jQuery);

function back_in() {
    var wheight = $(window).height();
    var bheight = $('body').height();
    var cheight;
    cheight = wheight < bheight ? bheight : wheight;
    $(".backcover").css({
        opacity: 0.5,
        height: cheight + "px"
    }).fadeIn(500);
}

function time(timeNow) {
    var sDate = new Date(timeNow);
    var cDate = new Date();
    var cTimeZone = cDate.getTimezoneOffset() / 60 * (-1);
    cTimeZone = cTimeZone > 0 ? '+' + cTimeZone : cTimeZone;
    sTime = sDate.getHours() + ':' + sDate.getMinutes() + ':' + sDate.getSeconds();
    cTime = cDate.getHours() + ':' + cDate.getMinutes() + ':' + cDate.getSeconds();
    cTime = cTime.replace(/(\b\d\b)/g, '0$1') + ' (GMT' + cTimeZone + ')';
    sTime = sTime.replace(/(\b\d\b)/g, '0$1');
    $("#stime").text(sTime);
    $("#ctime").text(cTime);
    setTimeout("time(" + (timeNow + 1800) + ")", 1800);
}

function delcookie(name) {
    var exp = new Date();
    exp.setTime(exp.getTime() - 10000);
    document.cookie = name + "=;expires=" + exp.toGMTString();
}

function getcookie(name) {
    var cname = '/(^| )' + name + '=([^;]*)(;|$)/';
    var s = document.cookie.match(eval(cname));
    return s == null ? '' : unescape(s[2]);
}

function requestWhois(ip) {
    $("#whois").html(loadimg).fadeIn();
    $.ajax({
        url: './whois/_whois.php?q=' + ip,
        cache: false,
        success: function(data) {
            $("#whois").html(data);
        },
        error:function(){
            $("#whois").html('出现了一点错误，请重试');
        }
    });
}


function loadmap(address, info, mapzoom) {
    $("#map").html(loadimg).fadeIn();
    var geocoder = new google.maps.Geocoder();
    if (geocoder) {
        geocoder.geocode({
            'address': address
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                //var latlng = new google.maps.LatLng(results[0].geometry.location);
                var myOptions = {
                    zoom: mapzoom,
                    center: results[0].geometry.location,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }
                var map = new google.maps.Map(document.getElementById("map"), myOptions);
                var infowindow = new google.maps.InfoWindow({
                    content: info
                });
                //map.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location
                });
                infowindow.open(map, marker);
                $("#maploading").hide();
                $("#map").show();
            } else {
                $("#maploading").hide();
                $("#map").html("无地图");

            }
        });
    }
}

function footReplace() {
    if ($(window).height() > $('body').height()) $("#foot").css({
        "top": ($(window).height() - $('body').height()) + "px",
        'visibility': 'visible'
    });
    else $("#foot").css({
        'visibility': 'visible'
    });
}

footReplace();
$(window).resize(footReplace);

$('#ipfield').setautofill();
$('#ipbutton').checksubmit();
$("#dialog").newsticker();