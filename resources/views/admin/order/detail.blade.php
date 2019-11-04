<div class="row">
    <div class="col-md-6">
        <h3>{{__('common.order_info')}}</h3>
        <div><label class="col-md-6">{{__('common.order_id')}}:</label> {{$data->orderId}} </div>
        <div><label class="col-md-6">{{__('common.status')}}:</label>
        <?php  switch ($data->status){
            case 0 : {echo '<span class="text-default">Pending</span>'; break;}
            case 1 : {echo '<span class="text-success">Success</span>'; break;}
            case 2 : {echo '<span class="text-danger">Cancelled</span>'; break;}
            case 3 : {echo '<span class="text-danger">Expired</span>'; break;}
            }
            ?>
        </div>

        <div><label class="col-md-6">{{__('common.creation_time')}}:</label> {{$data->created_at}} </div>
        <div><label class="col-md-6">{{__('common.updated_time')}}:</label> {{$data->updated_at}} </div>
        <div><label class="col-md-6">{{__('common.expired_time')}}:</label> {{$data->expire_time}} </div>


        <div><label class="col-md-6">{{__('common.receive_bank_name')}}:</label>{{$data->bank_name}} </div>
        <div><label class="col-md-6">{{__('common.receive_bank_cardholder')}}:</label>{{$data->bank_cardhoder}} </div>
        <div><label class="col-md-6">{{__('common.receive_bank_account')}}:</label>{{$data->bank_account}} </div>

        <div><label class="col-md-6">USD 金额 :</label> {{$data->amount_usd}} USD </div>
        <div><label class="col-md-6">汇率:</label>  {{$data->payment_currency}} <small>( 1 USD= {{$data->currency_rate}} {{$data->payment_currency}})</small></div>
        <div><label class="col-md-6">支付金额:</label> <b style="font-size: 150%">{{number_format($data->final_amount,2)}} {{$data->payment_currency}}</b> </div>
    </div>
    <div class="col-md-6">
        <h3>{{__('common.client_info')}}</h3>
        <div><label class="col-md-4">Ref ID</label> {{$data->refId}} </div>
        <div><label class="col-md-4">{{__('common.name_person')}}:</label> {{$data->payee}} </div>
        <div><label class="col-md-4">短讯ID:</label> {{$data->sms_bank_message_id}} </div>
        <div><label class="col-md-4">短讯内容:</label> <small>{{$sms_bank_message_content}}</small> </div>
    </div>
</div>
