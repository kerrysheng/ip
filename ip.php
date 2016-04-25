<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IP查询系统</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="static/css/bootstrap.min.css" rel="stylesheet">
    <style>

    </style>
</head>

<body>
<div class="wrap-header">

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
    searchOneIP();

    $('#submitBtn').on('click', function (e) {
        var ip = $('#ipInput').val();
        searchOneIP(ip);
    });
    $('#ipInput').on('keyup', function (e) {
        if (e.keyCode === 13) {
            searchOneIP($(this).val());
            $(this).blur();
        }
    })

    function searchOneIP(ip) {
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

                //$('#IpCountryFlag').html($('<img />').attr('src', './static/images/flags/' + (data.country.toLowerCase()) + '.png'))
            }
        })
    }

    function request(ip) {
        return $.ajax('api' + (ip ? '/' + ip : ''), {
            dataType: 'json'
        })
    }

</script>
</html>