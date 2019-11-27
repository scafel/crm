@include('home.common.header')
<body>
@include('home.common.footer')
<script>
    layer.msg('{{ $message }}', function(){
        parent.layer.closeAll();
    });
</script>