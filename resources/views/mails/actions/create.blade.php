@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ __('Create New Mail Action') }}</h1>
    <form method="POST" action="{{ route('mail-action.store') }}">
        @csrf
        <div class="form-group">
            <label for="name">{{ __('mail_action.name') }}</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="duration">{{ __('mail_action.duration') }}</label>
            <input type="number" name="duration" id="duration" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="to_return">{{ __('To Return') }}</label>
            <select name="to_return" id="to_return" class="form-select">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>
        <div class="form-group">
            <label for="description">{{ __('Description') }}</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">{{ __('mail_action.save') }}</button>
    </form>
</div>
@endsection
