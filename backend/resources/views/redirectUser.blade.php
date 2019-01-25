<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
</head>
<body>
<div id="openid" style="display: none">{{$openid}}</div>
<div id="url" style="display: none">{{$url}}</div>
<h2>
    <script>
        const openid = document.querySelector("#openid").innerHTML;
        const url = document.querySelector("#url").innerHTML;
        const getQueryVariable = (variable) => {
            const query = window.location.search.substring(1);
            const vars = query.split('&');
            for (let i = 0; i < vars.length; i++) {
                const pair = vars[i].split('=');
                if (decodeURIComponent(pair[0]) === variable) {
                    return decodeURIComponent(pair[1]);
                }
            }
            document.querySelector("body").innerHTML = "url参数错误";
        };
        if (!!(shopId = getQueryVariable("shopid"))) {
            document.write(`${url}?openid=${openid}&shopid=${shopId}`);
            window.location.href = `${url}?openid=${openid}&shopid=${shopId}`;
        }
    </script>
</h2>
</body>
</html>
