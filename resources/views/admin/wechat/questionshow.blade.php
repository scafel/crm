@include('home.common.header')
<body>
<div class="panel admin-panel">
    <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>增加客户</strong></div>
    <div class="body-content">
        <form id="form" method="post" class="form-x" action="#">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="question_id" value="{{ $question->question_id }}">
            <input type="hidden" name="question_token" value="{{ idMd5Token($question->question_id) }}">
            <div class="form-group">
                <div class="label">
                    <label>问卷名称：</label>
                </div>
                <div class="field">
                    <input readonly id="wechatname" type="text" class="input w50" value="{{ $question->question_name }}" name="question_name" data-validate="required:请输入问卷名称" />
                    <div class="tips"></div>
                </div>
            </div>
            <div class="addQuestionTitle">
                @if(!empty($question->answer))
                    @foreach($question->answer as $key=>$value)
                        <div class="form-group questiontitle questiontitle{{$key}}">
                            <div class="label">
                                <label>第{{ $key+1 }}题：</label>
                            </div>
                            <div class="field">
                                <input readonly type="text" class="input w50" value="{{ $value['title'] }}" name="question_title[]" />
                                <div class="tips"></div>
                            </div>
                            <br>
                            <hr>
                            <div class='addQuestionAnswer{{$key}}'>
                                @if(!empty($value['child']))
                                    @foreach($value['child'] as $k=>$v)
                                        <div class="field questionanswer questionanswer{{$k}}" style='float: left;display: inline-block;width: 50px;'>
                                            <input readonly type="text" value="{{$v['answer']}}" name="question_answer_{{$k}}[]" />
                                            <br>
                                            <input readonly type="text" value="{{$v['code']}}" name="question_code_{{$k}}[]" />
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </form>
    </div>
</div>
@include('home.common.footer')