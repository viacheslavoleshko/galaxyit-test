<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заявка кандидата</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Форма подачі заявки</h4>
                </div>
                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('apply.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="first_name" class="form-label">Ім'я</label>
                            <input type="text" class="form-control" id="firstname" name="firstname"
                                   value="{{ old('firstname') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="last_name" class="form-label">Прізвище</label>
                            <input type="text" class="form-control" id="lastname" name="lastname"
                                   value="{{ old('lastname') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="birth_date" class="form-label">Дата народження</label>
                            <input type="date" class="form-control" id="birth_date" name="birth_date"
                                   value="{{ old('birth_date') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Електронна пошта</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="{{ old('email') }}" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Відправити заявку</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
