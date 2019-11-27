</div>
<audio id="myAudio" autoplay="autoplay" loop="loop" preload >
    <source src="/video/gxfc.mp3" type="audio/mpeg">
</audio>
<script src="/weui/zepto.min.js"></script>
<script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="https://res.wx.qq.com/open/libs/weuijs/1.0.0/weui.min.js"></script>
<script src="/static/home/js/jquery.js"></script>
<script src="/static/layer/layer.js"></script>
</body>
</html>
<script>
    window.onload = function () {
        function audioAutoPlay (id) {
            var audio=document.getElementById(id);
            audio.play();
            document.addEventListener("WeixinJSBridgeReady",function() {
                audio.play();
            },false);
            document.addEventListener("YixinJSBridgeReady",function() {
                audio.play();
            },false);
        }
        audioAutoPlay("myAudio");
    }
</script>