@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Показать новость</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('news.index') }}"> Назад</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Название:</strong>
                {{ $news->name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Текст:</strong>
                {{ $news->text }}
            </div>
        </div>
        <div class="form-group">
                <strong>Изображение:</strong>
                <img src="{{ asset('storage/images/' . $news->file_image) }}" alt="{{$news->name}}">
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Пользователь:</strong>
               <a href="{{ route('users.show', $users->id) }}"> {{ $users->name }}</a>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>ИД сообщения в телеграм:</strong>
                {{ $news->message_id }}
            </div>
        </div>
    </div>
@endsection
