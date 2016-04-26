<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IP查询系统</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="static/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .wrap-header{
            margin:40px auto;
            text-align: center;
        }
        .wrap-body{
            margin: 0 auto;
            text-align: center;
        }
        .form-inline{
            padding: 10px;
        }
        .res-location span{
            margin-right: 10px;
        }
        .wrap-footer{

        }
        #IpCountryFlag {
            display: inline-block;
            vertical-align: bottom;
        }
    </style>
</head>

<body>
<div class="wrap-header">
<h2>蓝色年代IP查询系统</h2>
</div>
<div class="wrap-body">


    <div class="form-inline">
        <input class="form-control" type="text" id="ipInput">
        <button type="button" class="btn btn-primary" id="submitBtn">查询</button>
    </div>
    <div class="result-blk">
        <div class="res-location hidden">
            该IP的物理地址:<span id="IpLocation"></span> <span id="IpArea">所在区域:<span
                    id="IpAreaValue"></span></span> <span
                id="IpIsp">运营商:<span id="IpIspValue"></span></span>
        </div>
        <div class="res-title hidden">
            <h3>您查询的IP:<span id="IpAddr"></span> <span id="IpCountryFlag"></span></h3>
        </div>
    </div>
</div>
<div class="wrap-footer">

</div>

</body>
<script src="static/js/jquery-1.12.3.min.js"></script>
<script src="static/js/bootstrap.min.js"></script>
<script>
    //searchOneIP(loadHash());

    $('#submitBtn').on('click', function (e) {
        var ip = $.trim($('#ipInput').val());
        if (ip.length)
            setHash(ip);
    });
    $('#ipInput').on('keyup', function (e) {
        var val = $.trim($(this).val());
        if (e.keyCode === 13 && val.length) {
            setHash(val);
            $(this).blur();
        }
    });

    $(window).on('hashchange', function (e) {
        loadSearchHash(loadHash());
    }).on('load', function (e) {
        loadSearchHash(loadHash());
    });

    function loadSearchHash(ip) {
        $('#ipInput').val(ip);
        searchOneIP(ip);
    }

    function searchOneIP(ip) {
        // setHash(ip);
        request(ip).done(function (data) {

            if (data && data.code === 0) {
                $('.res-location').removeClass('hidden');
                $('.res-title').removeClass('hidden');

                if (data.area != data.location) {
                    $('#IpArea').removeClass('hidden');
                } else {
                    $('#IpArea').addClass('hidden');
                }
                if (data.isp !== '') {
                    $('#IpIsp').removeClass('hidden');
                }
                else {
                    $('#IpIsp').addClass('hidden');

                }
                $('#IpAddr').text(data.ip);
                $('#IpLocation').text(data.location);
                $('#IpAreaValue').text(data.area);
                $('#IpIspValue').text(data.isp);

                var countryCode = data.country.toLowerCase();

                if (countryCode == 'iana' || countryCode == '') {
                    $('#IpCountryFlag').hide();
                } else {
                    var cflag = $('<img />').attr('src', 'static/flags/4x3/' + countryCode + '.svg').css({
                        width: 35, height: 24
                    });
                    $('#IpCountryFlag').html(cflag).show();
                }

            }
        })
    }

    function request(ip) {
        return $.ajax('api' + (ip ? '/' + ip : ''), {
            dataType: 'json'
        })
    }

    function loadHash() {
        var hash = location.hash;

        if (hash.length) {
            return hash.substr(1);
        } else {
            return '';
        }
    }

    function setHash(ip) {
        location.hash = '#' + ip;
    }

</script>
</html>