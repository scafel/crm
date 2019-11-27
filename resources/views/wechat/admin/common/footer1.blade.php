<div style="text-align:center;">
    <p>来源:<a href="http://www.scafel.top/" target="_blank">scafel个人</a></p>
</div>
<div class="fixed-bottom-right">
    <table class="table table-hover text-center" style="cursor:pointer;">
        <tr onclick="showWebMessage({{getToUserMessage()}})">
            <th>您有 <font color="red">{{ getToUserMessage() }}</font> 条新消息</th>
        </tr>
    </table>
</div>
</body>
</html>
<script>
    function showWebMessage($message) {
        layer.open({
            type: 2,
            title: "站内消息",
            area:["300px",'500px'],
            shade:0,
            anim: 2,
            fixed:true,
            resize:false,
            move: false,
            closeBtn: 1,
            shadeClose: true,
            content:"{{ url('home/page/notepad') }}",
            offset: 'rb',
        })
    }
</script>