@extends('layouts.app')

@section('content')
    <h1>{{ __("Batch Mail Details for") }} {{ $batch->name }}</h1>
    <table class="table">
        <tbody>
            <tr>
                <th>{{ __("Mail ID") }}</th>
                <td>{{ $batchMail->mail_id }}</td>
            </tr>
            <tr>
                <th>{{ __("Insert Date") }}</th>
                <td>{{ $batchMail->insert_date }}</td>
            </tr>
            <tr>
                <th>{{ __("Remove Date") }}</th>
                <td>{{ $batchMail->remove_date }}</td>
            </tr>
        </tbody>
    </table>
    <a href="{{ route('batch.mail.edit', [$batch, $batchMail]) }}" class="btn btn-primary">{{ __("Edit") }}</a>
    <form action="{{ route('batch.mail.destroy', [$batch, $batchMail]) }}" method="POST" style="display: inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this batch mail?')">{{ __("Delete") }}</button>
    </form>
@endsection
