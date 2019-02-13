<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
</head>
<body>
<div style="text-align: center">Loading...</div>
<div id="openid" style="display: none">{{$openid}}</div>
<div id="url" style="display: none">{{$url}}</div>
<script>
    /**
     * 校验用户的地理位置通过并跳转至抽奖页面
     */
    var openid = document.getElementById("openid").innerHTML;
    var url = document.getElementById("url").innerHTML;

    window.location.href = `${url}?openid=${openid}`;
</script>
</body>
</html>
