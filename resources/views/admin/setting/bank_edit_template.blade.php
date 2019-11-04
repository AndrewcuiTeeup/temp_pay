 <form id="frmEditTemplate">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{$data['id']}}">
                        <div class="form-group">
                            <label for="">{{__('common.bank_name')}} *</label>
                            <select class="form-control" name="bank_name" id="" placeholder="" required>>
                                <option value="">-</option>
                                @foreach($availableBank as $val)
                                    @if($val==$data['bank_name'])
                                    <option value="{{$val}}" selected="selected">{{$val}}</option>
                                    @else
                                    <option value="{{$val}}">{{$val}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">{{__('common.bank_branch')}}</label>
                            <input type="text" class="form-control" name="bank_branch" id="" placeholder="" value="{{$data['bank_branch']}}" >
                        </div>
                        <div class="form-group">
                            <label for="">{{__('common.bank_account')}} *</label>
                            <input type="text" class="form-control" name="bank_account" id="" placeholder="" value="{{$data['bank_account']}}"  required>
                        </div>
                        <div class="form-group">
                            <label for="">{{__('common.bank_cardholder')}} * </label>
                            <input type="text" class="form-control" name="bank_cardholder" id="" placeholder="" value="{{$data['bank_cardholder']}}"  required>
                        </div>

     <div class="form-group">
         <label for="">QR Code * </label>
         <p>
             <img class="img-qrcode" src="{{$data['qrcode']}}" height="80px">
         </p>
         <input type="text" class="form-control link-qrcode" name="qrcode" id="" placeholder="" value="{{$data['qrcode']}}"  required>
         <input type="file" name="image" onchange="encodeImagetoBase64(this)">
     </div>

                        <div class="form-group">
                            <label for="">{{__('common.status')}} *</label>
                            <select name="status" class="form-control" required>
                                <option value="1" {{($data['status']==1)? 'selected="selected"':''}}>{{__('common.activated')}}</option>
                                <option value="0" {{($data['status']==0)? 'selected="selected"':''}}>{{__('common.suspended')}}</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
<script>
    // edit account
    $('#frmEditTemplate').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {
            // handle the invalid form...
        } else {
            var url='{{route('admin.setting.bank.edit')}}';
            // everything looks good!
            var data=$('#frmEditTemplate').serialize();
            $.ajax({
                url:url,
                type:"post",
                data:data,
                success:function(data){
                    $('#myModal').modal('hide')
                    window.location.reload();
                },
                error:function(e){
                    alert("Error！！");
                    $('#myModal').modal('hide');
                }
            });
        }
        return false;
    });
</script>