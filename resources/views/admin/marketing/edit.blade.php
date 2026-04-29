@extends('adminlte::page')

@section('title', 'Edit Coupon')

@section('content_header')
    <h1>Edit Kupon: {{ $coupon->code }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <x-adminlte-card title="Informasi Kupon" theme="primary" icon="fas fa-edit" shadow>
            <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <x-adminlte-input name="code" label="Kode Kupon" fgroup-class="col-md-6"
                        value="{{ old('code', $coupon->code) }}" placeholder="Contoh: PROMO2026" required />
                    
                    <x-adminlte-select name="type" label="Tipe Diskon" fgroup-class="col-md-6">
                        <option value="fixed" {{ $coupon->type == 'fixed' ? 'selected' : '' }}>Potongan Harga (Rp)</option>
                        <option value="percentage" {{ $coupon->type == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                    </x-adminlte-select>
                </div>

                <div class="row">
                    <x-adminlte-input name="value" type="number" label="Nilai Diskon" fgroup-class="col-md-6"
                        value="{{ old('value', $coupon->value) }}" required />
                    
                    <x-adminlte-input name="limit" type="number" label="Batas Penggunaan" fgroup-class="col-md-6"
                        value="{{ old('limit', $coupon->limit) }}" required />
                </div>

                <div class="row">
                    {{-- Pastikan start_date & end_date sudah di-cast sebagai date di Model Coupon --}}
                    <x-adminlte-input name="start_date" type="date" label="Tanggal Mulai" fgroup-class="col-md-6"
                        value="{{ old('start_date', optional($coupon->start_date)->format('Y-m-d')) }}" required />
                    
                    <x-adminlte-input name="end_date" type="date" label="Tanggal Berakhir" fgroup-class="col-md-6"
                        value="{{ old('end_date', optional($coupon->end_date)->format('Y-m-d')) }}" required />
                </div>

                <x-adminlte-select name="is_active" label="Status Kupon">
                    <option value="1" {{ $coupon->is_active ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ !$coupon->is_active ? 'selected' : '' }}>Non-Aktif</option>
                </x-adminlte-select>

                <div class="mt-3">
                    <x-adminlte-button type="submit" label="Simpan Perubahan" theme="primary" icon="fas fa-save"/>
                    <a href="{{ route('admin.marketing.coupons') }}" class="btn btn-default shadow-sm">Batal</a>
                </div>
            </form>
        </x-adminlte-card>
    </div>
</div>
@stop