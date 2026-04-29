@extends('adminlte::page')

@section('title', 'System Admin | Users')

@section('content_header')
    <h1>User Management</h1>
@stop

@section('content')
<x-adminlte-card title="System Users" theme="navy" icon="fas fa-users-cog" shadow>
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>User</th>
                <th>Email Status</th>
                <th>Roles / Access</th>
                <th>Joined Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded-circle d-flex justify-content-center align-items-center mr-3" style="width: 40px; height: 40px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <span class="font-weight-bold d-block">{{ $user->name }}</span>
                            <small class="text-muted">{{ $user->email }}</small>
                        </div>
                    </div>
                </td>
                <td>
                    @if($user->email_verified_at)
                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Verified</span>
                    @else
                        <span class="badge badge-secondary">Unverified</span>
                    @endif
                </td>
                <td>
                    @foreach($user->getRoleNames() as $role)
                        <span class="badge badge-outline-primary px-2 py-1 text-uppercase">{{ $role }}</span>
                    @endforeach
                </td>
                <td>{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    <button class="btn btn-xs btn-default text-navy shadow-sm mx-1" data-toggle="modal" data-target="#editRole-{{ $user->id }}" title="Edit Roles">
                        <i class="fas fa-key"></i>
                    </button>
                    <button class="btn btn-xs btn-default text-danger shadow-sm mx-1" title="Deactivate User">
                        <i class="fas fa-user-slash"></i>
                    </button>
                </td>
            </tr>

            {{-- Modal Edit Role --}}
            <x-adminlte-modal id="editRole-{{ $user->id }}" title="Manage Access: {{ $user->name }}" theme="navy" icon="fas fa-shield-alt">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="form-group">
                        <label>Assign Roles</label>
                        <select name="roles[]" class="form-control select2" multiple="multiple" style="width: 100%;">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                    {{ strtoupper($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <p class="small text-muted italic">*Admin roles have full access to all modules including Finance and Inventory.</p>
                    <x-slot name="footerSlot">
                        <x-adminlte-button theme="primary" label="Save Permissions" type="submit"/>
                    </x-slot>
                </form>
            </x-adminlte-modal>
            @endforeach
        </tbody>
    </table>
    <div class="mt-3">
        {{ $users->links() }}
    </div>
</x-adminlte-card>
@stop