<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IP查询系统</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="static/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .wrap-header {
            margin: 40px auto;
            text-align: center;
        }

        .wrap-body {
            margin: 0 auto;
            text-align: center;
        }

        .form-inline {
            padding: 10px;
        }

        .res-location span {
            margin-right: 10px;
        }

        .api-instruction {
            margin: 70px auto 30px;
            width: 75%;
            background: #f7f7f9;
            padding: 10px;
        }

        .table-let-fix {
            width: 75%;
            margin: 0 auto;
        }

        .wrap-footer {
            height: 30px;
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
        <div class="res-valid hidden">
            <div class="res-location">
                该IP的物理地址:<span id="IpLocation"></span> <span id="IpArea">所在区域:<span
                        id="IpAreaValue"></span></span> <span
                    id="IpIsp">运营商:<span id="IpIspValue"></span></span>
            </div>
            <div class="res-title">
                <h3>您查询的IP:<span id="IpAddr"></span> <span id="IpCountryFlag"></span></h3>
            </div>
        </div>
        <div class="res-invalid hidden">
            <div class="res-warning">
                <h3>您输入的IP:<span id="IpAddrInvalid"></span> <span class="ip-res-warning"></span></h3>
            </div>
        </div>

    </div>

    <div class="api-instruction">
        本系统的API如下:<code>https://www.blueera.net/ip/api/{ip_address}/{result_format}/{json_callback}</code>
    </div>
    <table class="table table-bordered table-striped  table-let-fix">
        <thead>
        <tr>
            <td>Name</td>
            <td>Classification</td>
            <td>Default Value</td>
            <td>Available Choice</td>
            <td>Comment</td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><code>ip_address</code></td>
            <td>请求参数</td>
            <td>访问者IP</td>
            <td>任意IP</td>
            <td>需要查询的IP地址</td>
        </tr>
        <tr>
            <td><code>result_format</code></td>
            <td>请求参数</td>
            <td><code>json</code></td>
            <td><code>json</code>|<code>jsonp</code>|<code>text</code></td>
            <td>返回的数据格式</td>
        </tr>
        <tr>
            <td><code>json_callback</code></td>
            <td>请求参数</td>
            <td></td>
            <td>任意字符</td>
            <td>回调函数名</td>
        </tr>
        <tr>
            <td><code>code</code></td>
            <td>返回结果</td>
            <td></td>
            <td><code>0</code>|正整数</td>
            <td>标示返回结果集的正确性</td>
        </tr>
        <tr>
            <td><code>country</code></td>
            <td>返回结果</td>
            <td></td>
            <td>ISO—3166国家码</td>
            <td>返回查询IP的国家码</td>
        </tr>
        <tr>
            <td><code>location</code></td>
            <td>返回结果</td>
            <td></td>
            <td>位置信息</td>
            <td>IP地址对应的位置</td>
        </tr>
        <tr>
            <td><code>area</code></td>
            <td>返回结果</td>
            <td></td>
            <td>区域</td>
            <td>IP地址对应的区域</td>
        </tr>
        <tr>
            <td><code>isp</code></td>
            <td>返回结果</td>
            <td></td>
            <td>运营商</td>
            <td>IP地址对应的运营商</td>
        </tr>
        </tbody>
    </table>
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
                $('.res-valid').removeClass('hidden');
                $('.res-invalid').addClass('hidden');


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
            else {
                $('.res-valid').addClass('hidden');
                $('.res-invalid').removeClass('hidden');
                $('#IpAddrInvalid').text(ip);
                $('.ip-res-warning').text(data.msg);

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