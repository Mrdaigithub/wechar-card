<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>wechat-card</title>
    <style>
        body, div {
            margin: 0;
            padding: 0;
        }

        @-webkit-keyframes weuiLoading {
            0% {
                transform: rotate3d(0, 0, 1, 0deg);
            }

            100% {
                transform: rotate3d(0, 0, 1, 360deg);
            }
        }

        @keyframes weuiLoading {
            0% {
                transform: rotate3d(0, 0, 1, 0deg);
            }

            100% {
                transform: rotate3d(0, 0, 1, 360deg);
            }
        }

        .loading {
            width: 65%;
            margin: 250px auto;
            line-height: 1.6em;
            font-size: 14px;
            text-align: center;
        }

        .loading-icon {
            width: 20px;
            height: 20px;
            display: inline-block;
            vertical-align: middle;
            -webkit-animation: weuiLoading 1s steps(12, end) infinite;
            animation: weuiLoading 1s steps(12, end) infinite;
            background: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMjAiIGhlaWdodD0iMTIwIiB2aWV3Qm94PSIwIDAgMTAwIDEwMCI+PHBhdGggZmlsbD0ibm9uZSIgZD0iTTAgMGgxMDB2MTAwSDB6Ii8+PHJlY3Qgd2lkdGg9IjciIGhlaWdodD0iMjAiIHg9IjQ2LjUiIHk9IjQwIiBmaWxsPSIjRTlFOUU5IiByeD0iNSIgcnk9IjUiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgLTMwKSIvPjxyZWN0IHdpZHRoPSI3IiBoZWlnaHQ9IjIwIiB4PSI0Ni41IiB5PSI0MCIgZmlsbD0iIzk4OTY5NyIgcng9IjUiIHJ5PSI1IiB0cmFuc2Zvcm09InJvdGF0ZSgzMCAxMDUuOTggNjUpIi8+PHJlY3Qgd2lkdGg9IjciIGhlaWdodD0iMjAiIHg9IjQ2LjUiIHk9IjQwIiBmaWxsPSIjOUI5OTlBIiByeD0iNSIgcnk9IjUiIHRyYW5zZm9ybT0icm90YXRlKDYwIDc1Ljk4IDY1KSIvPjxyZWN0IHdpZHRoPSI3IiBoZWlnaHQ9IjIwIiB4PSI0Ni41IiB5PSI0MCIgZmlsbD0iI0EzQTFBMiIgcng9IjUiIHJ5PSI1IiB0cmFuc2Zvcm09InJvdGF0ZSg5MCA2NSA2NSkiLz48cmVjdCB3aWR0aD0iNyIgaGVpZ2h0PSIyMCIgeD0iNDYuNSIgeT0iNDAiIGZpbGw9IiNBQkE5QUEiIHJ4PSI1IiByeT0iNSIgdHJhbnNmb3JtPSJyb3RhdGUoMTIwIDU4LjY2IDY1KSIvPjxyZWN0IHdpZHRoPSI3IiBoZWlnaHQ9IjIwIiB4PSI0Ni41IiB5PSI0MCIgZmlsbD0iI0IyQjJCMiIgcng9IjUiIHJ5PSI1IiB0cmFuc2Zvcm09InJvdGF0ZSgxNTAgNTQuMDIgNjUpIi8+PHJlY3Qgd2lkdGg9IjciIGhlaWdodD0iMjAiIHg9IjQ2LjUiIHk9IjQwIiBmaWxsPSIjQkFCOEI5IiByeD0iNSIgcnk9IjUiIHRyYW5zZm9ybT0icm90YXRlKDE4MCA1MCA2NSkiLz48cmVjdCB3aWR0aD0iNyIgaGVpZ2h0PSIyMCIgeD0iNDYuNSIgeT0iNDAiIGZpbGw9IiNDMkMwQzEiIHJ4PSI1IiByeT0iNSIgdHJhbnNmb3JtPSJyb3RhdGUoLTE1MCA0NS45OCA2NSkiLz48cmVjdCB3aWR0aD0iNyIgaGVpZ2h0PSIyMCIgeD0iNDYuNSIgeT0iNDAiIGZpbGw9IiNDQkNCQ0IiIHJ4PSI1IiByeT0iNSIgdHJhbnNmb3JtPSJyb3RhdGUoLTEyMCA0MS4zNCA2NSkiLz48cmVjdCB3aWR0aD0iNyIgaGVpZ2h0PSIyMCIgeD0iNDYuNSIgeT0iNDAiIGZpbGw9IiNEMkQyRDIiIHJ4PSI1IiByeT0iNSIgdHJhbnNmb3JtPSJyb3RhdGUoLTkwIDM1IDY1KSIvPjxyZWN0IHdpZHRoPSI3IiBoZWlnaHQ9IjIwIiB4PSI0Ni41IiB5PSI0MCIgZmlsbD0iI0RBREFEQSIgcng9IjUiIHJ5PSI1IiB0cmFuc2Zvcm09InJvdGF0ZSgtNjAgMjQuMDIgNjUpIi8+PHJlY3Qgd2lkdGg9IjciIGhlaWdodD0iMjAiIHg9IjQ2LjUiIHk9IjQwIiBmaWxsPSIjRTJFMkUyIiByeD0iNSIgcnk9IjUiIHRyYW5zZm9ybT0icm90YXRlKC0zMCAtNS45OCA2NSkiLz48L3N2Zz4=) no-repeat;
            background-size: 100%;
        }

        .loading-text {
            display: inline-block;
            vertical-align: middle;
        }
    </style>
</head>
<body>
<div class="loading">
    <i class="loading-icon"></i>
    <span class="loading-text">正在加载</span>
</div>
<div id="openid" style="display: none">{{$openid}}</div>
<div id="url" style="display: none">{{$url}}</div>
<div id="shopLocation" style="display: none">{{$shopLocation}}</div>
<script src="https://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
<script>
    /**
     * 校验用户的地理位置通过并跳转至抽奖页面
     */
    wx.config(<?php
        use EasyWeChat\Factory;
        $app = Factory::officialAccount([
            'app_id'        => env("WECHAT_OFFICIAL_ACCOUNT_APPID_1", ""),
            'secret'        => env("WECHAT_OFFICIAL_ACCOUNT_SECRET_1", ""),
            'response_type' => 'array',
        ]);
        echo $app->jssdk->buildConfig(array("getLocation"), FALSE);
        ?>);
    var shopId = null;
    var openid = document.getElementById("openid").innerHTML;
    var url = document.getElementById("url").innerHTML;
    var shopLocation = document.getElementById("shopLocation").innerHTML;
    var pass = false;

    function getQueryVariable(variable) {
        var query = window.location.search.substring(1);
        var vars = query.split('&');
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split('=');
            if (decodeURIComponent(pair[0]) === variable) {
                return decodeURIComponent(pair[1]);
            }
        }
        alert("url参数错误");
    }

    function ajax(method, url, callback) {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.open(method, url, true);
        xmlHttp.send();
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState === 4) {
                if (xmlHttp.status === 200) {
                    callback(xmlHttp.responseText);
                } else {
                    alert("网络错误");
                }
            }
        }
    }

    function dialog(msg) {
        alert(msg);
        document.write(msg)
    }

    if (!(shopId = getQueryVariable("shopid"))) {
        dialog("url参数错误")
    }

    wx.ready(function () {
        wx.checkJsApi({
            jsApiList: ['getLocation'],
            success: function (res) {
                pass = res.checkResult.getLocation === true;
                if (!pass) {
                    dialog("无法获取你的位置信息");
                    return;
                }
                wx.getLocation({
                    type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
                    success: function (res) {
                        var city = null;
                        var address = null;
                        var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                        var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                        var geocoderUrl = '<?php echo env("DOMAIN") . "/wechat/geocoder"?>';
                        ajax("GET", geocoderUrl + "?location=" + latitude + "," + longitude + "&time=" + new Date().getTime(), function (res) {
                            city = JSON.parse(res).result.addressComponent.city;
                            address = JSON.parse(res).result.formatted_address;
                            if (city !== shopLocation) {
                                dialog("当前所在区域无法参加活动");
                            } else {
                                window.location.href = `${url}?openid=${openid}&shopid=${shopId}&location=${encodeURIComponent(city)}&address=${encodeURIComponent(address)}&t=${(new Date()).getTime()}`;
                            }
                        });
                    },
                    fail: function () {
                        alert("无法获取你的位置信息")
                    },
                    cancel: function () {
                        alert("无法获取你的位置信息")
                    }
                });
            }
        });
    });

</script>
</body>
</html>
