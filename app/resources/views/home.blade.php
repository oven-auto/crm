@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">CMS Овен-Авто</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Вы успешно вошли в систему. Для входа в CMS <a href="/">пройдите по ссылке</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
