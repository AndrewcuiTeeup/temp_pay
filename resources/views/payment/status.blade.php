<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no,viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>订单支付</title>
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
    </div>
</div>
<div class="content max1100">
    <div class="desktop">
        <p style="color:#878787">订单ID：{{$order->orderId}}</p>
        <p>&nbsp;</p>
        @if($order->status==3)
        <h4>该订单已经过期。</h4>
        @endif
        @if($order->status==1)
            <h4>该订单已经完成。</h4>
        @endif
    </div>

</div>
@if($order->status==1)
<form id='frmSuccess'  data-toggle="validator" class="mui-input-group" action="{{$order['redirect_url']}}" method="post" style="text-align:center">
    <input type="hidden" name="refId" value="{{$order['refId']}}">
    <input type="hidden" name="orderId" value="{{$order['orderId']}}">
    <input type="hidden" name="amount" value="{{$order['amount']}}">
    <input type="hidden" name="sign" value="{{$order['sign']}}">
    <input type="hidden" name="status" value="success">
<button type="submit" class="btn-complete" >完成</button>
</form>

    @if(!empty($order['notify_url']))
    <form id="frmNotify" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{$order['id']}}">
    </form>
    <script src="{{asset('/template/assets/js/jquery.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var url = '{{route('payment.notify')}}';
            var data = $('#frmNotify').serialize();
            $.ajax({
                url: url,
                type: "post",
                data: data,
                success: function (data) {

                },
                error: function (e) {


                }
            });
        });
    </script>
@endif
@endif
</body>
</html>