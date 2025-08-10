<div>
    @extends('layouts.app')

    @section('content')
        <div class="container">
            <h2>Регистрация</h2>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div>
                    <label>Имя:</label>
                    <input type="text" name="name" required>
                </div>
                <div>
                    <label>Email:</label>
                    <input type="email" name="email" required>
                </div>
                <div>
                    <label>Пароль:</label>
                    <input type="password" name="password" required>
                </div>
                <div>
                    <label>Подтвердите пароль:</label>
                    <input type="password" name="password_confirmation" required>
                </div>
                <button type="submit">Зарегистрироваться</button>
            </form>
        </div>
    @endsection</div>
