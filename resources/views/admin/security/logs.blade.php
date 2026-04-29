@extends('adminlte::page')

@section('title', 'Security | System Logs')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="font-weight-bold text-dark"><i class="fas fa-shield-alt mr-2 text-navy"></i>Security Audit Logs</h1>
        <div>
            <x-adminlte-button label="Export CSV" theme="outline-dark" icon="fas fa-file-export" size="sm" class="shadow-sm"/>
            <x-adminlte-button label="Clear Older Logs" theme="outline-danger" icon="fas fa-eraser" size="sm" class="shadow-sm btn-clear-logs ml-2"/>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    {{-- Summary Widget --}}
    <div class="col-md-3">
        <x-adminlte-info-box title="Total Events" text="{{ $ledgers->total() }}" icon="fas fa-fingerprint" theme="navy" shadow/>
    </div>
    <div class="col-md-3">
        <x-adminlte-info-box title="Suspicious" text="2" icon="fas fa-exclamation-triangle" theme="warning" shadow/>
    </div>
    <div class="col-md-6">
        <x-adminlte-card title="Quick Filter" theme="info" icon="fas fa-search" collapsible="collapsed" shadow>
            <form action="{{ route('admin.security.logs') }}" method="GET" class="row">
                <div class="col-md-5">
                    <x-adminlte-select name="event_type" label="Event Type" size="sm">
                        <option value="">All Events</option>
                        <option value="login">Login Attempt</option>
                        <option value="logout">Logout</option>
                        <option value="failed_login">Failed Login</option>
                        <option value="data_deletion">Data Deletion</option>
                    </x-adminlte-select>
                </div>
                <div class="col-md-5">
                    <x-adminlte-input name="ip_address" label="IP Address" placeholder="127.0.0.1" size="sm"/>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-sm btn-primary mb-3 w-100">Filter</button>
                </div>
            </form>
        </x-adminlte-card>
    </div>

    {{-- Main Log Table --}}
    <div class="col-12">
        <x-adminlte-card theme="dark" theme-mode="outline">
            <div class="table-responsive">
                <table class="table table-hover table-sm text-nowrap align-middle mb-0">
                    <thead class="bg-navy">
                        <tr>
                            <th style="width: 180px">Timestamp</th>
                            <th>User</th>
                            <th>Event</th>
                            <th>IP Address</th>
                            <th>Browser / Platform</th>
                            <th class="text-center">Severity</th>
                        </tr>
                    </thead>
                    <tbody class="font-family-monospace">
                        @forelse($ledgers as $log)
                        <tr>
                            <td class="text-muted small">
                                <i class="far fa-clock mr-1"></i> {{ $log->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-secondary rounded-circle mr-2 d-flex align-items-center justify-content-center" style="width: 25px; height: 25px;">
                                        <i class="fas fa-user-shield text-xs"></i>
                                    </div>
                                    <span class="font-weight-bold">{{ $log->user->name ?? 'System/Guest' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="text-dark">{{ $log->event_description }}</span>
                            </td>
                            <td>
                                <code class="bg-light px-2 py-1 rounded text-primary">{{ $log->ip_address }}</code>
                            </td>
                            <td>
                                <small class="text-muted" title="{{ $log->user_agent }}">
                                    {{ Str::limit($log->user_agent, 40) }}
                                </small>
                            </td>
                            <td class="text-center">
                                @php
                                    $severity = $log->severity ?? 'info';
                                    $badge = [
                                        'critical' => 'danger',
                                        'high'     => 'warning',
                                        'medium'   => 'info',
                                        'info'     => 'success'
                                    ];
                                @endphp
                                <span class="badge badge-{{ $badge[$severity] }} px-2 py-1" style="min-width: 70px">
                                    {{ strtoupper($severity) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-shield-virus fa-3x text-light mb-3"></i>
                                <p class="text-muted">No security logs recorded yet.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($ledgers->hasPages())
            <div class="card-footer bg-white border-top-0">
                <div class="float-right">
                    {{ $ledgers->links() }}
                </div>
            </div>
            @endif
        </x-adminlte-card>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Notifikasi Toast
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        @if(session('success'))
            Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
        @endif

        // Konfirmasi Clear Logs
        $('.btn-clear-logs').click(function() {
            Swal.fire({
                title: 'Archive Older Logs?',
                text: "Logs older than 30 days will be permanently removed!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, Clear them!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Logika AJAX atau Form Submit di sini
                    Toast.fire({ icon: 'info', title: 'System cleanup initiated...' });
                }
            });
        });
    });
</script>
@stop

@section('css')
<style>
    .font-family-monospace {
        font-family: 'SFMono-Regular', Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
    }
    .table td { vertical-align: middle !important; }
</style>
@stop