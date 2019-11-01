@extends('layouts.common')

@section('content')
    <div class="bg-gradient-blue">
        <div class="login-top"></div>
    </div>
    <div class="login-register">
        <div class="text-center mb-4">
            <h1 style="color: #fff">云支付管理</h1>
        </div>
        <div class="login-box card">
            <div class="card-body">
                <div class="card-body">
                    <div class="heading mb-3">
                        <h3 class="box-title float-left">{{__('login.login')}}</h3>
                        <div class="float-right bg-light rounded">
                            <div class="dropdown">
                                <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php $_lang=Cookie::get('locale'); ?>
                                    @switch($_lang)
                                        @case('en')
                                        <i class="flag-icon flag-icon-gb mr-2"></i>
                                        @break
                                        @case('zh-CN')
                                        <i class="flag-icon flag-icon-cn mr-2"></i>
                                        @break
                                        @case('jp')
                                        <i class="flag-icon flag-icon-jp mr-2"></i>
                                        @break
                                        @default
                                        <i class="flag-icon flag-icon-gb"></i>
                                        <?php $_lang='en'; ?>
                                    @endswitch
                                </a>
                                <div class="dropdown-menu  dropdown-menu-right">
                                    @if($_lang!='en')
                                        <a class="dropdown-item lang-change" href="{{ url('admin/lang/en')}}" data-lang="en"><i class="flag-icon flag-icon-gb mr-2"></i>English</a>
                                    @endif
                                    @if($_lang!='zh-CN')
                                        <a class="dropdown-item lang-change"   href="{{ url('admin/lang/zh-CN') }}" data-lang="cn"><i class="flag-icon flag-icon-cn mr-2"></i>简体中文</a>
                                    @endif
                                    @if($_lang!='jp')
                                        <a class="dropdown-item lang-change"   href="{{ url('admin/lang/jp') }}" data-lang="jp"><i class="flag-icon flag-icon-jp mr-2"></i>日本語</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                <form class="form-horizontal" role="form" method="POST" action="{{ route('admin.login.submit') }}" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="mdi mdi-email-outline"></i></span>
                            </div>
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                        </div>
                    </div>
                    <div class="form-group  {{ $errors->has('password') ? ' has-error' : '' }}">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="mdi mdi-lock"></i></span>
                            </div>
                            <input id="password" type="password" class="form-control" name="password" required>
                        </div>
                    </div>
                    <div class="form-group  {{ $errors->has('captcha') ? 'has-error' : '' }} ">
                        <div class="input-group">
                            <input id="captcha" type="text" class="form-control" placeholder="" name="captcha" required>
                            <div class="input-group-prepend captcha">
                                <img src="{{captcha_src()}}" style="cursor: pointer" onclick="this.src='{{captcha_src()}}'+Math.random()">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <div class="form-group text-center mt-3">
                        <div class="col-xs-12">
                            <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">{{__('login.login')}}</button>
                        </div>
                    </div>
                    <div class="form-group">
                    </div>
                </form>
            </div>
        </div>
    </div>









@endsection
