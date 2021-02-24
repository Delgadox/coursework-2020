@extends('layouts.admin')

@section('content')
    <div class="row" style="margin-top: 5rem;">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Панель Администратора</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('register') }}"> Создать Пользователя</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Почта</th>
            <th>Пароль</th>
            <th width="340px">Действия</th>
        </tr>
        @foreach ($data as $key => $value)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $value->name }}</td>
            <td>{{ $value->email }}</td>
            <td>{{ $value->password }}</td>
            <td>
                <form action="{{ route('users.destroy',$value->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('users.show',$value->id) }}">Показать</a>
                    <a class="btn btn-primary" href="{{ route('users.edit',$value->id) }}">Изменить</a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Удалить</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    {!! $data->links() !!}
@endsection
