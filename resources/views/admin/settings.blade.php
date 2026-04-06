@extends('layouts.admin')

@section('content')
<section class="admin-settings-grid">
    <div class="admin-panel admin-panel-wide-full">
        <div class="panel-head">
            <h2>Site Settings</h2>
        </div>

        @if (session('success'))
            <div class="admin-alert admin-alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="admin-alert admin-alert-error">Please review the highlighted fields and try again.</div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST" class="admin-form-grid admin-form-grid-two">
            @csrf
            @method('PUT')

            @foreach ($settingsFields as $field)
                <label class="admin-field">
                    <span>{{ $field['label'] }}</span>
                    @if (($field['type'] ?? 'text') === 'textarea')
                        <textarea name="{{ $field['input_name'] }}" rows="4">{{ old($field['path'], $field['value']) }}</textarea>
                    @else
                        <input type="text" name="{{ $field['input_name'] }}" value="{{ old($field['path'], $field['value']) }}">
                    @endif
                </label>
            @endforeach

            <div class="admin-form-actions">
                <button type="submit" class="admin-submit-btn">Save Settings</button>
            </div>
        </form>
    </div>
</section>
@endsection
