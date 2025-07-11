@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Selectionner un term</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Type </th>
                <th>Parent </th>
                <th>Description</th>
                <th>Langue</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($terms as $term)
                <tr>
                    <td>{{ $term->id }}</td>
                    <td>
                        <a href="{{ route('records.sort')}}?categ=term&id={{ $term->id }}">
                            {{ $term->name }}
                        </a>
                    </td>
                    <td>{{ $term->type->code }} - {{ $term->type->name }}</td>
                    <td>{{ $term->parent->name ?? 'Debut de la branche' }}</td>
                    <td>{{ $term->description }}</td>
                    <td>{{ $term->language->name }}</td>
                    <td>
                        <a href="{{ route('terms.show', $term->id) }}" class="btn btn-info">Paramètres</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item {{ $terms->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $terms->previousPageUrl() }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            @for ($i = 1; $i <= $terms->lastPage(); $i++)
                <li class="page-item {{ $terms->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="{{ $terms->url($i) }}">{{ $i }}</a>
                </li>
            @endfor
            <li class="page-item {{ $terms->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $terms->nextPageUrl() }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>

</div>
@endsection
