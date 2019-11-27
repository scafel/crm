@if($list->question_id != 1548054825927)
    @include("wechat.question.common.header",['title'=>"自检结果",'style'=>"background: url('/static/wechat/question/ortherbg.png') top no-repeat;background-size: 100% 100%;"])
@else
    @include("wechat.question.common.header",['title'=>"自检结果",'style'=>"background: url('/static/wechat/question/fkbg.png') top no-repeat;background-size: 100% 100%;"])
@endif
<div class="page__hd" style=" width: 80%;margin: 0px auto;margin-top: 1em;">
    <h1 class="page__title">Result</h1>
    <p class="page__desc">自测结果展示</p>
</div>
<div class="page_bd" style="width: 80%;margin: 0px auto;">
    <div class="weui-form-preview" style="background: transparent">
        <div class="weui-form-preview__hd">
            <label class="weui-form-preview__label" >您测试的结果</label>
            <em class="weui-form-preview__value" >{{ $total }}</em>
        </div>
        <div class="weui-form-preview__bd">
            <div class="weui-form-preview__item">
                <label class="weui-form-preview__label">分值范围</label>
                <span class="weui-form-preview__value" >{{ $list->range }}</span>
            </div>
            <div class="weui-form-preview__item">
                <label class="weui-form-preview__label" >当前状态</label>
                <span class="weui-form-preview__value" >{{ $list->title }}</span>
            </div>
            <div class="weui-form-preview__item">
                <label class="weui-form-preview__label">建议</label>
                <span class="weui-form-preview__value" >{{ $list->answer }}</span>
            </div>
        </div>
        <div class="weui-form-preview__ft">
            <a class="weui-form-preview__btn weui-form-preview__btn_default" href="/wechat/{{ $wechat_id }}/question">返回自检首页</a>
            {{--<a class="weui-form-preview__btn weui-form-preview__btn_primary" href="/wechat/{{ $wechat_id }}/mall">咨询医生</a>--}}
        </div>
    </div>
    <article class="weui-article">
        <h1>友情提示</h1>
        <section>
            <h2 class="title">@if(!empty($article)){{ $article->title }}@endif:</h2>
            @if(!empty($article))
                @foreach(unserialize($article->message) as $key=>$value)
                    <section>
                        <h3>{{$key+1}}.{{$value['title']}}</h3>
                        <p>{{ $value['message'] }}</p>
                    </section>
                @endforeach
            @endif
        </section>
    </article>
</div>
<style>
    .layui-layer{background: rgb(255, 0, 0);color: white;margin: 0px auto;}
</style>
@include("wechat.question.common.footer")
<script src="/static/layer/layer.js"></script>
<script>
    layer.open({
        title: false,
        skin: 'my-class',
        closeBtn: 0,
        button:false,
        shade:[0.3,'#000'],
        shadeClose:true,
        anim: 2,
        resize:false,
        scrollbar:true,
        content: '阳泉桃河医院全体员工祝您身体健康，阖家欢乐'
    })
</script>