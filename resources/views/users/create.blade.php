@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Добавить новость</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('news.index') }}"> Back</a>
        </div>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        Ошибка ввода<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Название:</strong>
                <input type="text" name="name" class="form-control" id="name" placeholder="Введите название">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Текст:</strong>
                <textarea class="form-control" style="height:150px" id="text" name="text" placeholder="Введите текст"></textarea>
            </div>
        </div>
        <div class="form-group">
                <strong>Изображение:</strong>
                <input type="file" name="file_image" id="file_image" class="form-control">
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </div>

</form>
@endsection
