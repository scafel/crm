<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>桃河医院{{ $title }}自检系统</title>
    <link rel="stylesheet" href="/weui/weui.min.css" media="screen">
    <link rel="stylesheet" href="/weui/example.css" media="screen">
    <style>
        *:focus{
            border: 0px;
            outline: none;
        }
        .page{
            overflow-x: hidden;
        }
        .my-class{
            background: red;
        }
    </style>
    <!--[if lt IE 9]-->
    <script src="/js/html5media.min.js"></script>
    <!--[endif]-->
</head>
<body ontouchstart>
<div class="weui-toptips weui-toptips_warn js_tooltips">错误提示</div>
<div class="container" id="container"></div>
<div class="page" @if(isset($style))style=" {{ $style }}" @endif >