@extends('layouts.admin')

@section('content')
<section class="admin-workspace-grid">
    <div class="admin-panel">
        <div class="panel-head">
            <h2>{{ $sectionTitle }} List</h2>
            <a href="{{ $createUrl }}">Add New</a>
        </div>

        @if (session('success'))
            <div class="admin-alert admin-alert-success">{{ session('success') }}</div>
        @endif

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        @foreach ($tableColumns as $column)
                            <th>{{ $column }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tableRows as $row)
                        <tr class="{{ $selectedId === $row['id'] ? 'is-selected' : '' }}">
                            @foreach ($row['cells'] as $index => $cell)
                                <td>
                                    @if ($loop->last)
                                        <span class="status-pill status-{{ $row['tone'] }}">{{ $cell }}</span>
                                    @else
                                        {{ $cell }}
                                    @endif
                                </td>
                            @endforeach
                            <td class="admin-table-actions">
                                <a href="{{ $row['edit_url'] }}" class="admin-mini-link">Edit</a>
                                <form action="{{ $row['delete_url'] }}" method="POST" onsubmit="return confirm('Delete this item?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-danger-link">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="admin-side-stack">
        <div class="admin-panel">
            <div class="panel-head">
                <h2>{{ $formTitle }}</h2>
            </div>

            @if ($errors->any())

                <div class="admin-alert admin-alert-error">Please review the highlighted fields and try again.{{ $errors->first() }}</div>
            @endif

            <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="admin-form-grid">
                @csrf
                @if ($formMethod !== 'POST')
                    @method($formMethod)
                @endif

                @foreach ($formFields as $field)
                    <label class="admin-field">
                        <span>{{ $field['label'] }}</span>

                        @if (($field['type'] ?? 'text') === 'textarea')
                            <textarea name="{{ $field['input_name'] }}" rows="4">{{ old($field['path'], $field['value']) }}</textarea>
                        @elseif (($field['type'] ?? 'text') === 'file')
                            <input type="file" name="{{ $field['input_name'] }}" accept=".jpg,.jpeg,.png,.webp,.svg">
                            @if ($field['value'])
                                <div class="admin-image-preview">
                                    <img src="{{ $field['value'] }}" alt="{{ $field['label'] }}">
                                    <small>Current image</small>
                                </div>
                            @endif
                        @elseif (($field['type'] ?? 'text') === 'select')
                            <select name="{{ $field['input_name'] }}">
                                @foreach ($field['options'] as $optionValue => $optionLabel)
                                    <option value="{{ $optionValue }}" @selected(old($field['path'], $field['value']) == $optionValue)>{{ $optionLabel }}</option>
                                @endforeach
                            </select>
                        @elseif (($field['type'] ?? 'text') === 'url')
                            <input type="url" name="{{ $field['input_name'] }}" value="{{ old($field['path'], $field['value']) }}" placeholder="https://example.com">
                        @else
                            <input type="text" name="{{ $field['input_name'] }}" value="{{ old($field['path'], $field['value']) }}">
                        @endif
                    </label>
                @endforeach

                <div class="admin-form-actions">
                    <button type="submit" class="admin-submit-btn">Save Changes</button>
                    <a href="{{ $createUrl }}" class="admin-ghost-link">New Item</a>
                </div>
            </form>
        </div>

        <div class="admin-panel admin-preview-panel">
            <div class="panel-head">
                <h2>{{ $previewTitle }}</h2>
            </div>
            <div class="admin-preview-list">
                @foreach ($previewLines as $line)
                    <div>
                        @if(is_array($line))
                            {{ $line['en'] ?? '' }} {{-- or 'bn' --}}
                        @else
                            {{ $line }}
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection
