
                    <form id="frmEdit" autocomplete="off">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{$data['id']}}">
                        <div class="form-group">
                            <label for="">{{__('common.name_person') }} *</label>
                            <input type="text" class="form-control" name="name" id="" placeholder="" value="{{$data['name']}}" required>
                        </div>
                        <div class="form-group">
                            <label for="">{{__('common.email') }} *</label>
                            <input type="text" class="form-control" name="email" readonly id="" placeholder="" value="{{$data['email']}}" required email>
                        </div>
                        <div class="form-group">
                            <label for="">{{__('common.password') }} </label>
                            <input id="inputPassword" type="password" name="password" placeholder="" class="form-password form-control" autocomplete="new-password" data-minlength="6" data-maxlength="6" maxlength="10" value="" >
                            <p class="help-block">{{__('common.password_tip') }}</p>
                        </div>
                        <div class="form-group">
                            <label for="">{{__('common.retype_password') }} </label>
                            <input id="password-confirm" type="password" placeholder="" class="form-control" data-minlength="6" data-maxlength="6" maxlength="10" data-match="#inputPassword" name="password_confirmation"  value="">
                        </div>

                        <div class="form-group">
                            <label for="">角色</label>
                            <select name="type" class="form-control" required>
                                <option value="1" {{$data['type']==1 ? 'selected':''}}>{{__('common.role_admin') }}</option>
                            </select>
                        </div>
                        <div class="div-loading text-info" style="display: none">Loading.....</div>
                        <button id="btn-update-admin" type="submit" class="btn btn-primary btn-submit-waiting">{{__('buttons.submit') }}</button>
                    </form>
<script>
    // edit account
    $('#frmEdit').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {
            // handle the invalid form...
        } else {
            $('#btn-update-admin').hide();
            $('.div-loading').show();
            var url='{{route('admin.setting.admin.update')}}';
            // everything looks good!
            var data=$('#frmEdit').serialize();
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
                    $('#btn-update-admin').show();
                    $('#myModal').modal('hide');
                    $('.div-loading').hide();
                }
            });
        }
        return false;
    });
</script>