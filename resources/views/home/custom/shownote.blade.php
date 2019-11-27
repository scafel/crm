@include('home.common.header')
<body>
<form method="post" action="" id="listform">
    <div class="panel admin-panel">
        <table class="table table-hover text-center">
            <tr>
                <th>添加时间</th>
                <th>备注</th>
                <th>下次服务时间</th>
            </tr>
            @if(empty($remarks))
                <tr>
                    <td style="text-align:left; padding-left:20px;" colspan="5">
                        还没有任何信息
                    </td>
                </tr>
            @else
                @foreach($remarks as $value)
                    <tr>
                        <td>{{ date('Y-m-d',(int)($value['addtime'])) }}</td>
                        <td>{{ $value['remarks']}}</td>
                        <td>{{ date('Y-m-d',(int)($value['nexttime'])) }}</td>
                    </tr>
                @endforeach
                    <tr>
                        <td colspan="3" align="right"><div class="pagelist" style="text-align: right;"><span style="cursor: pointer;color: red;"  onclick="openUrl('{{ url('home/page/custom/addcl') }}/{{ $custom->id }}/{{ idMd5Token($custom->id) }}','添加服务信息');">修改最后一条</span>  </div></td>
                    </tr>
            @endif
        </table>
    </div>
</form>
@include('home.common.footer')