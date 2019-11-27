<div style="text-align:center;">
    <p>来源:<a href="http://www.scafel.top/" target="_blank">scafel个人</a></p>
</div>
</body>
</html>
<script>
    function findUser(time,line,type) {
        var time_id = 0;
        if(time == -1 && line == -1){
            time_id = $(".scafel_td")[0].innerText;
            var id = -1;
        } else if (time == -1){
            time_id = $(".scafel_td")[0].innerText;
            var id = $($("th")[Number(line)+1]).attr("value");
        }else if (line == -1){
            var id = -1;
        } else{
            var id = $($("th")[Number(line)+1]).attr("value");
        }
        var url = "{{ url('home/page/user/search/type') }}?type="+type+"&time="+time+"&id="+id+"&time_id="+time_id;
        openUrl(url,'用户信息加载');
    }
</script>
