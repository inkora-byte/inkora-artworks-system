@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <h1 class="h3 mb-4">Settings</h1>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Key</th>
                        <th>Value</th>
                        <th>Group</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($settings as $setting)
                        <tr>
                            <td><code>{{ $setting->key }}</code></td>
                            <td>
                                <form method="POST" action="{{ route('settings.update', $setting) }}" class="d-flex gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input
                                        type="text"
                                        name="value"
                                        value="{{ old('value', $setting->value) }}"
                                        class="form-control form-control-sm"
                                        style="max-width: 200px;"
                                    >
                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                </form>
                            </td>
                            <td>
                                <x-status-badge :status="$setting->group ?? 'general'" color="secondary" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
