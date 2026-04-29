@extends('adminlte::page')

@section('title', 'System | Roles & Permissions')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Roles & Permissions</h1>
        <x-adminlte-button label="Add New Role" theme="primary" icon="fas fa-plus" data-toggle="modal" data-target="#modalAddRole"/>
    </div>
@stop

@section('content')
<div class="row">
    @foreach($roles as $role)
    <div class="col-md-12">
        <div class="card card-outline card-navy shadow-sm">
            <div class="card-header">
                <h3 class="card-title text-uppercase font-weight-bold">
                    <i class="fas fa-user-tag mr-2"></i> {{ $role->name }}
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                @csrf @method('PATCH')
                <div class="card-body">
                    <p class="text-muted small mb-4">Set specific permissions for this role. Access will be granted immediately upon saving.</p>
                    
                    <div class="row">
                        @php
                            // Mengelompokkan permission berdasarkan prefix (misal: 'user.', 'order.')
                            $groupedPermissions = $permissions->groupBy(function($item) {
                                return explode('.', $item->name)[0];
                            });
                        @endphp

                        @foreach($groupedPermissions as $group => $items)
                        <div class="col-md-3 mb-4">
                            <h6 class="text-navy font-weight-bold border-bottom pb-1 text-uppercase">{{ $group }} Management</h6>
                            @foreach($items as $permission)
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                       class="custom-control-input" id="perm-{{ $role->id }}-{{ $permission->id }}"
                                       {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                <label class="custom-control-label font-weight-normal" for="perm-{{ $role->id }}-{{ $permission->id }}">
                                    {{ str_replace($group.'.', '', $permission->name) }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer bg-white border-top">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        <i class="fas fa-save mr-1"></i> Update Permissions
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endforeach
</div>

{{-- Modal Add Role --}}
<form action="{{ route('admin.roles.store') }}" method="POST">
    @csrf
    <x-adminlte-modal id="modalAddRole" title="Create New System Role" theme="primary" icon="fas fa-shield-alt">
        
        {{-- Body Modal --}}
        <x-adminlte-input name="name" label="Role Name" placeholder="e.g. warehouse-manager" igroup-size="md">
            <x-slot name="prependSlot">
                <div class="input-group-text bg-navy">
                    <i class="fas fa-id-badge"></i>
                </div>
            </x-slot>
        </x-adminlte-input>
        
        <p class="small text-muted">
            <i class="fas fa-info-circle"></i> Note: Role name should be lowercase and use dashes (slug format).
        </p>

        {{-- Footer Modal --}}
        <x-slot name="footerSlot">
            <x-adminlte-button theme="secondary" label="Cancel" data-dismiss="modal"/>
            {{-- Tombol ini sekarang ada di dalam lingkup <form> --}}
            <x-adminlte-button theme="success" label="Create Role" type="submit"/>
        </x-slot>

    </x-adminlte-modal>
</form>
@stop