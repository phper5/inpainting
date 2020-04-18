<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>意见反馈-在线智能图片修复</title>
    <meta name="description" content="点滴在线修复工具是一款在线去水印软件。无需安装，即可快速批量去除水印，文字，杂物，并且保持原始文件的画质与格式。"/>
    <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1"/>
    <link rel="canonical" href="http://recovery.diandi.org" />
    <meta property="og:locale" content="zh_CN" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="点滴工具-在线智能图片修复" />
    <meta property="og:description" content="点滴在线修复工具是一款在线去水印软件。无需安装，即可快速批量去除水印，文字，杂物，并且保持原始文件的画质与格式。" />
    <meta property="og:url" content="http://recovery.diandi.org" />
    <meta property="og:site_name" content="点滴 - 为您提供专业的图片，视频，音频在线解决方案" />
    <!-- Fonts -->
    <link href="css/app.css" rel="stylesheet">

    <script src="{{ asset('vendor/kustomer/js/kustomer.js') }}" defer></script>

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
            font-display: swap;
        }


        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
            flex-direction:column;
        }

        .position-ref {
            position: relative;
        }


        .content {
            text-align: center;
            min-height:400px;
            height:auto!
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }
    .content .title{
        color: #000;
        font-size: 28px;
        color: #333;
    }

    </style>
</head>
<body itemscope itemtype="http://schema.org/diandi/feedback">
<nav class="nav">
    <div class="top">
        <div class="topbar">
            <img src="images/logo.webp">
        </div>
        <div class="menu">
            <!--
            <div class="item">
                <div class="txt"> 定价</div>
            </div> -->
            <div class="item">
                <div class="txt"><a href="/">首页</a></div>
            </div>
            <div class="item">
                <div class="txt"><a href="/feedback">反馈</a></div>
            </div>
            <div class="item">
                <div class="txt">登录</div>
            </div>
            <div class="item register">
                <div class="txt">注册</div>
            </div>
        </div>
    </div>
</nav>
<header>
    <h1>点滴在线修复</h1>
    <h3>点滴照片修复一款简单易用的在线图片修复工具，可以方便去水印，去杂物，去杂物，方便简单易上手，0基础也可轻松操作。<br>
        无论你是能熟练运用PS的大神，还是什么都不会的小白，使用点滴在线修复工具，只需动动手指，即可一键在线修复您的图片。
    </h3>
</header>
<div class="flex-center position-ref content-height">
    <div id="demoBox">
        <div class="content feedboxinfo">
            <div class="title">
                请使用右下角的反馈按钮就行反馈，谢谢
            </div>
            <ul>
                <li>服务的完善离不开您的积极参与</li>
                <li>反馈时可留下联系方式，我们会尽快和您联系</li>
            </ul>

        </div>
    </div>



</div>
<div class="bar">


</div>

@include('kustomer::kustomer')
<!--<div>已经累计处理文件1个</div>-->
</body>
</html>
