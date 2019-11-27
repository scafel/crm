<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>crm阳泉桃河医院</title>
    <link rel="stylesheet" href="/static/home/css/pintuer.css">
    <link rel="stylesheet" href="/static/home/css/admin.css">
    <script src="/static/home/js/jquery.js"></script>
    <!--[if lt IE 9]>
    <script src="/static/common/html5shiv.js"></script>
    <script src="/static/common/respond.min.js"></script>
    <![endif]-->
    <script src="/static/home/js/pintuer.js"></script>
    <script src="/static/layer/layer.js"></script>
    <script src="/static/laydate/laydate.js"></script>
    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
        });
    </script>
    <script src="/static/common/common.js?v=2"></script>
</head>