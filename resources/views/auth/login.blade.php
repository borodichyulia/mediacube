<div>
    @extends('layouts.app')

    @section('content')
        <div class="container">
            <h2>Вход</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div>
                    <label>Email:</label>
                    <input type="email" name="email" required>
                </div>
                <div>
                    <label>Пароль:</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit">Войти</button>
            </form>
        </div>
    @endsection
</div>
