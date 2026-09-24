@php
    $currentRole = $user?->roles->first()?->name;
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label">Name</label>
        <input
            type="text" name="name" id="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $user->name ?? '') }}"
            required
        >
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input
            type="email" name="email" id="email"
            class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $user->email ?? '') }}"
            required
        >
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="phone" class="form-label">Phone <span class="text-muted">(optional)</span></label>
        <input
            type="text" name="phone" id="phone"
            class="form-control @error('phone') is-invalid @enderror"
            value="{{ old('phone', $user->phone ?? '') }}"
        >
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="role" class="form-label">Role</label>
        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
            <option value="" disabled {{ old('role', $currentRole) ? '' : 'selected' }}>Select a role</option>
            @foreach ($roles as $role)
                <option value="{{ $role }}" {{ old('role', $currentRole) === $role ? 'selected' : '' }}>
                    {{ $role }}
                </option>
            @endforeach
        </select>
        @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="password" class="form-label">
            Password
            @if ($user)
                <span class="text-muted">(leave blank to keep current)</span>
            @endif
        </label>
        <input
            type="password" name="password" id="password"
            class="form-control @error('password') is-invalid @enderror"
            {{ $user ? '' : 'required' }}
        >
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <input
            type="password" name="password_confirmation" id="password_confirmation"
            class="form-control"
            {{ $user ? '' : 'required' }}
        >
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-primary">
        {{ $user ? 'Update User' : 'Create User' }}
    </button>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>
