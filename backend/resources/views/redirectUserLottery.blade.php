<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
</head>
<body>
<div>Loding...</div>
<div id="openid" style="display: none">{{$openid}}</div>
<div id="url" style="display: none">{{$url}}</div>
<script src="https://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
<script>
    /**
     * 校验用户的地理位置通过并跳转至抽奖页面
     */
    wx.config(<?php
        $app = app('wechat.official_account');
        echo $app->jssdk->buildConfig(array("getLocation"), FALSE)
        ?>);
    var shopId = null;
    var openid = document.getElementById("openid").innerHTML;
    var url = document.getElementById("url").innerHTML;
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
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.open(method, url, true);
        xmlhttp.send();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4) {
                if (xmlhttp.status == 200) {
                    callback(xmlhttp.responseText);
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
                        var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                        var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                        var geocoderUrl = '<?php echo env("DOMAIN") . "/wechat/geocoder"?>';
                        ajax("GET", geocoderUrl + "?location=" + latitude + "," + longitude + "&time=" + new Date().getTime(), function (res) {
                            city = JSON.parse(res).result.addressComponent.city;
                            var requestShopLocationUrl = '<?php echo env("DOMAIN") . "/wechat/shop/"?>' + shopId + '/location';
                            ajax("GET", requestShopLocationUrl, function (res) {
                                if (city !== res) {
                                    dialog("当前所在区域无法参加活动");
                                } else {
                                    window.location.href = `${url}?openid=${openid}&shopid=${shopId}&location=${encodeURIComponent(city)}`;
                                }
                            })
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
