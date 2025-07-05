<!-- resources/views/contacts/index.blade.php -->

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Contatos</title>
</head>
<body>
    <h1>Lista de Contatos</h1>

    <ul>
        @foreach ($contacts as $contact)
            <li>{{ $contact->name }} — {{ $contact->email }} — {{ $contact->phone }}</li>
        @endforeach
    </ul>

    {{ $contacts->links() }}
</body>
</html>
