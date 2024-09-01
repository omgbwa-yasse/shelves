@extends('layouts.app')

@section('content')
    <h1>Parapheurs envoyés</h1>
    <table class="table">
        <thead>
            <tr>
                <th>code</th>
                <th>Intitulé </th>
                <th>Organisation de départ</th>
                <th>Organisation d'arrivée</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($batchTransactions as $batchTransaction)
                <tr>
                    <td>{{ $batchTransaction->batch->code }}</td>
                    <td>{{ $batchTransaction->batch->name }}</td>
                    <td>{{ $batchTransaction->organisationSend->name }}</td>
                    <td>{{ $batchTransaction->organisationReceived->name }}</td>
                    <td>{{ $batchTransaction->created_at }}</td>
                    <td>
                        <a href="{{ route('batch-send.show', $batchTransaction) }}" class="btn btn-sm btn-info">Show</a>
                        <a href="{{ route('batch-send.edit', $batchTransaction) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('batch-send.destroy', $batchTransaction) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this batch transaction?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
