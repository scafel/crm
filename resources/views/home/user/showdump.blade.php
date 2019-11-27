@include('home.common.header')
<body>
<form method="post" action="" id="listform">
    <div class="panel-head" id="add"><a href="{{ url('home\page\user\getdumpuser') }}"><strong><span class="icon-pencil-square-o" ></span>导出</strong></a></div>
    <div class="panel admin-panel">
        <table class="table table-hover text-center">
            <tr>
                @foreach($header as $value)
                    <th>{{ $value }}</th>
                @endforeach
            </tr>
            @if(empty($users))
                <tr>
                    <td style="text-align:left; padding-left:20px;" colspan="5">
                        还没有任何登记用户
                    </td>
                </tr>
            @else
                @foreach($users as $value)
                    <tr>
                        @foreach($value as $v)
                            <td>{{ $v }}</td>
                        @endforeach
                    </tr>
                @endforeach
            @endif
        </table>
    </div>
</form>
@include('home.common.footer')
