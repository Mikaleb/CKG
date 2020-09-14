@extends('layouts.app.app')

@section('content')
    <div class="section">
        <div class="container">
            <div class="flex mb-4">
                <div class="flex-1 " id="left_column">
                    @include('user.user_space.parts.menu')
                </div>
                <div class="flex-1 is-three-quarters blockcontent">
                    @lang('account.my-account')
                    WIP
                    @include('user.user_space.parts.danger')
{{--                    TODO : Danger zone--}}
{{--                    TODO : Unlink social medias--}}
                </div>
            </div>
        </div>
    </div>
@endsection
