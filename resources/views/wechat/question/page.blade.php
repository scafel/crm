@include("wechat.question.common.header",['title'=>'','style'=>"background: linear-gradient(to right,#910907,#930c09,#8d0a09,#820704);"])
    <img src="/static/wechat/question/indexbg.png" alt="" border="0" usemap="#planetmap" style="width: 100%;">
    <map name="planetmap" id="planetmap">
        <area id="one" shape="poly"  href="question/page/1548054825927/{{idMd5Token(1548054825927)}}">
        <area id="two" shape="poly"  href="question/page/1548142623744/{{idMd5Token(1548142623744)}}">
        <area id="three" shape="poly"  href="https://mp.weixin.qq.com/s?__biz=MzAwOTI1MDI0Mw==&mid=2650039308&idx=1&sn=9dcdc5811adf8381ef3d71cd65721537&chksm=8362b0a0b41539b63554d68ffec6eb8f0f0fc5c538fa0ee88ddaf61d74357120172b5409c5ed&token=775282362&lang=zh_CN#rd">
        <area id="four" shape="poly"  href="question/page/1548754485876/{{idMd5Token(1548754485876)}}">
        <area id="five" shape="poly"  href="question/page/154880987465/{{idMd5Token(154880987465)}}">
    </map>
@include("wechat.question.common.footer")
<script>
    $(function () {
        var oWidth  =   541,oHeight = 960;
        var width   =   $("img").width();
        var bi  =   oWidth/width;
        changeCoords(bi);
    })
    function changeCoords(bi) {
        var one = new Array(196,323,258,360,258,432,196,468,134,432,134,360,196,323);
        var two = new Array(344,323,408,360,408,432,346,468,282,432,282,360,344,323);
        var three = new Array(272,451,335,487,335,560,272,596,209,560,209,487,272,451);
        var four = new Array(196,578,258,613,258,686,196,722,134,686,134,613,196,578);
        var five = new Array(344,578,408,613,408,686,344,722,282,686,282,613,344,578);
        var oneStr="",twoStr="",threeStr="",fourStr="",fiveStr = "";
        for (var i = 0 ; i < one.length ; i ++){
            one[i]  =   parseInt(one[i]/bi);
            oneStr  +=   one[i]+",";
            two[i]  =   parseInt(two[i]/bi);
            twoStr  +=   two[i]+",";
            three[i]  =   parseInt(three[i]/bi);
            threeStr  +=   three[i]+",";
            four[i]  =   parseInt(four[i]/bi);
            fourStr  +=   four[i]+",";
            five[i]  =   parseInt(five[i]/bi);
            fiveStr  +=   five[i]+",";
        }
        oneStr  =   oneStr.substr(0,oneStr.length -1);
        twoStr  =   twoStr.substr(0,twoStr.length -1);
        threeStr  =   threeStr.substr(0,threeStr.length -1);
        fourStr  =   fourStr.substr(0,fourStr.length -1);
        fiveStr  =   fiveStr.substr(0,fiveStr.length -1);
        $("#one").attr("coords",oneStr);
        $("#two").attr("coords",two);
        $("#three").attr("coords",threeStr);
        $("#four").attr("coords",fourStr);
        $("#five").attr("coords",fiveStr);
    }
    window.onload = function () {
        $("img").attr("src","/static/wechat/question/indexbg_m.png");

    }
    if (window.localStorage.getItem("wechat_video")){

    } else{
        var windowHeight  =   document.body.scrollHeight;
        var windowWidth =   document.body.scrollWidth;
        layer.open({
            type:2,
            title:false,
            offset:"t",
            area:[windowWidth+"px",windowHeight+"px"],
            shade: 0.8,
            closeBtn: 0,
            shadeClose: true,
            time:13000,
            content:"/video/taoheyiyuanbainianshiping.mp4",
            end:function () {
                window.localStorage.setItem("wechat_video",1);
            }
        })
    }
</script>