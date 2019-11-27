@if($question->question_id != 1548054825927)
@include("wechat.question.common.header",['title'=>$question->question_name,'style'=>"background: url('/static/wechat/question/ortherbg.png') top no-repeat;background-size: 100% 100%;"])
@else
@include("wechat.question.common.header",['title'=>$question->question_name,'style'=>"background: url('/static/wechat/question/fkbg.png') top no-repeat;background-size: 100% 100%;"])
@endif
<form action="/wechat/{{ $wechat_id }}/question/add" method="post" style="width: 83%; margin: 0px auto;margin-top: 3em;">
     @csrf
    <input type="hidden" name="question_id" value="{{ $question->question_id }}">
    <input type="hidden" name="question_token" value="{{ idMd5Token($question->question_id) }}">
    @if(empty($answer))@else
        @foreach($answer as $key => $value)
            <div class="weui-cells__title">{{ $value['title'] }}</div>
            <div class="weui-cells weui-cells_radio" style="background-color: rgba(255, 255, 255, 0.38)">
                <div class="weui-flex">
                @foreach($value['child'] as $k=>$v)
                        <div class="weui-flex__item">
                    <label class="weui-cell weui-check__label" for="x{{ $key }}{{ $k }}">
                        <div class="weui-cell__bd">
                            <p>{{ $v['answer'] }}</p>
                        </div>
                        <div class="weui-cell__ft">
                            <input type="radio" class="weui-check" name="code{{ $key }}" value="{{ $v['code'] }}" id="x{{ $key }}{{ $k }}">
                            <span class="weui-icon-checked"></span>
                        </div>
                    </label>
                        </div>
                @endforeach
                </div>
            </div>
        @endforeach
    @endif
    <div class="weui-cells__title">选项都最好都选哦！</div>
    <br><br>
    @if($question->question_id != 1548054825927)
        <button  style="    z-index: 99999999999999;
    background: url('/static/wechat/question/button_orther.png') center no-repeat;
    width: 100%;
    margin: 0px auto;
    display: block;
    height: 3.5em;
    background-size: auto 100%;
    border: 0px;" type="submit" ></button>
    @else
        <button  style="    z-index: 99999999999999;
    background: url('/static/wechat/question/button_fk.png') center no-repeat;
    width: 100%;
    margin: 0px auto;
    display: block;
    height: 3.5em;
    background-size: auto 100%;
    border: 0px;" type="submit" ></button>
    @endif
    <br><br>
</form>
@include("wechat.question.common.footer")
