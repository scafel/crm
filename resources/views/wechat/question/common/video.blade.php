<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
<link rel="stylesheet" href="/video/videojs/video-js.min.css">
    <style>
    </style>
</head>
<body>
<video id="example_video_1" class="video-js vjs-default-skin videoOne" controls preload="none" width="640" height="264"
       poster="/video/video_bg.png">
    <source src="/video/taoheyiyuanbainianshiping.mp4" type="video/mp4">
</video>
</body>
</html>
<script src="/video/videojs/video.min.js"></script>
<script src="/static/home/js/jquery.js"></script>
<script src="/static/layer/layer.js"></script>
<script>
    var windowHeight  =   document.body.scrollHeight;
    var windowWidth =   document.body.scrollWidth;
    console.log();
    var player = videojs('example_video_1',{
        muted: true,
        controls : true,
        height:windowHeight,
        width:windowWidth,
        loop : true,
        // 更多配置.....
    },
        function onPlayerReady() {
            this.play();
        }
        );
</script>