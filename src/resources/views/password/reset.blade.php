@extends('HCACL::auth.layout')

@section('content')
    <div class="login-page" style="position: fixed; top:0; left: 0; bottom: 0; right: 0;">

        <div class="login-box">
            <div class="login-logo">
                <b>{{ config('app.name') }}</b>
            </div>

            <!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">{{ trans('HCACL::users.passwords.reset_view') }}</p>

                <div id="password-reset-form"></div>

                <a href="{{ route('auth.login')}}">{{ trans('HCACL::users.login.title') }}</a><br>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            HCService.FormManager.initialize();
            var form = HCService.FormManager.createForm({
                'structureURL': '{{  route('public.api.form-manager', 'password-reset-new')}}?token={{ $token }}',
                'divID': '#password-reset-form'
            });
        });
    </script>
@endsection