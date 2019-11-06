<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no,viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="{{asset('pay_ui/css/index.css')}}" />
</head>
<body>
<div class="header">
    <div class="max1100 header_content">
        <div class="money">
            <p>支付金额：<b id="pay_money">{{$order->final_amount}}</b>&nbsp;<span class="currency">CNY</span></p>
            <div>
                <span>充值金额：<span id="recharge_money">{{$order->amount_usd}}</span>&nbsp;USD</span>
                <span>汇率：<span id="currency_usd">1</span>&nbsp;USD=<span id="currency_cny">{{$order->currency_rate}}</span>&nbsp;{{$order->payment_currency}}</span>
            </div>
        </div>
        <span id="down_time"><span id="minute">14</span>:<span id="second">59</span></span>
    </div>
</div>
<div class="content max1100">
    <div class="desktop">
        <p style="color:#878787">订单ID：{{$order->orderId}}</p>
        <img src="{{$bankInfo->qrcode}}" />
        <!-- <p style="color:#888888">二维码</p> -->
    </div>
    <div class="mobile">
        <div><span class="label">收款人<i></i></span><span class="text">{{$bankInfo->bank_cardholder}}</span><span class="copy">复制</span></div>
        <div><span class="label">账户<i></i></span><span class="text">{{$bankInfo->bank_account}}</span><span class="copy">复制</span></div>
    </div>
</div>
<div class="footer max1100">
    <p>在您进行入金之前，请注意以下几点：</p>
    <ol>
        <li>请确认您所有的入金账号姓名与您的交易账户的姓名相同</li>
        <li>提出申请后，即表示您授权将个人资料开放给第三方验证您的身份，包括：支付服务提供商、银行、卡系统、监管机构、执法机构、政府机构、信用咨询机构以及我方认定相关付款的单位</li>
    </ol>
    <p>&nbsp;</p>
</div>
<div class="popup">复制成功</div>
<script src="{{asset('/template/assets/js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('pay_ui/js/rem.js')}}"></script>
<script type="text/javascript">
    const copy = document.getElementsByClassName('copy');
    const copy_text = document.getElementsByClassName('text');
    const popup = document.querySelector('.popup')

    for (let j = 0; j < copy.length; j++) {
        // 复制链接
        copy[j].onclick=function(){
            if (navigator.userAgent.match(/(iPhone|iPod|iPad);?/i)) {//区分iPhone设备
                window.getSelection().removeAllRanges();//这段代码必须放在前面否则无效
                var Url2=copy_text[j];//要复制文字的节点
                var range = document.createRange();
                // 选中需要复制的节点
                range.selectNode(Url2);
                // 执行选中元素
                window.getSelection().addRange(range);
                // 执行 copy 操作
                var successful = document.execCommand('copy');

                // 移除选中的元素
                window.getSelection().removeAllRanges();
            }else{
                let Inputs = document.createElement('input')
                var Url2=copy_text[j];//要复制文字的节点
                Inputs.value = Url2.innerText
                document.body.appendChild(Inputs)
                Inputs.select()
                // Inputs.style.display='none'
                document.execCommand('copy')
                document.body.removeChild(Inputs);
            }
            popup.classList.add('open');
            // setTimeout(,30000)
            setTimeout('close()',1000)
        }

        // popup.addEventListener("animationend",console.log(123))
    }

    function close (){
        popup.classList.remove('open')
    }

    window.onload=function(){
        //   if(GetRequest().amount&&GetRequest().amount_pay){
        //     amount.innerText = GetRequest().amount;
        //     amount_pay.innerText = GetRequest().amount_pay;
        //     currency.innerText = Number(GetRequest().amount_pay)/Number(GetRequest().amount)
        //   }
        // 倒计时

        let interval = setInterval(()=> {
            let minute_ = Number(minute.innerText);
            let second_ = Number(second.innerText)-1;
            if(Number(second.innerText) === 0 && Number(minute.innerText) === 0) {
                clearInterval(interval)
            }else{
                if(Number(second.innerText) === 0) {
                    second_ = 59;
                    minute_ = Number(minute.innerText)-1;
                }; //当秒钟为00时，秒数重新给值
                if(minute_ < 10) minute_ = "0" + minute_;
                if(second_ < 10) second_ = "0" + second_;
                second.innerText = second_;
                minute.innerText = minute_;
            }
        }, 1000);
    }

</script>
<script>
    var timer = null;

    function destroyTimer(){
        if(!!timer) {
            clearInterval(timer);
            timer = null;
        }
    }
    function checkStatus(OrderId) {
        var url='{{route('payment.status')}}';

        $.post(url,{ "_token": "{{ csrf_token() }}",id:OrderId},function (rs) {
            if(rs.status=='success' && rs.data) {
                var status=rs.data.status;
                status=Number(status);
                if(status==1){
                    // success
                    window.location.href="{{$success_url}}";
                }else if(status==3)
                {
                    // expired
                    window.location.href="{{$expired_url}}";
                }
            }
        });
    }
    $( document ).ready(function () {
        destroyTimer();
        checkStatus({{$order->id}});
        timer = setInterval(function () {
            checkStatus({{$order->id}});
        },30*1000); // 30 second request
        //
    });
</script>
</body>
</html>