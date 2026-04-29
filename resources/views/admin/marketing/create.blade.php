@extends('adminlte::page')

@section('title', 'Create Coupon')

@section('content_header')
    <h1>Create Promotion</h1>
@stop

@section('content')
<form action="{{ route('admin.coupons.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <x-adminlte-card title="Coupon Configuration" theme="navy">
                <div class="row">
                    <div class="col-md-6">
                        <x-adminlte-input name="code" label="Coupon Code" placeholder="E.g. LEBARAN2026" igroup-size="md">
                            <x-slot name="prependSlot"><div class="input-group-text bg-primary"><i class="fas fa-barcode"></i></div></x-slot>
                        </x-adminlte-input>
                    </div>
                    <div class="col-md-6">
                        <x-adminlte-select name="type" label="Discount Type">
                            <option value="fixed">Fixed Amount (Nominal)</option>
                            <option value="percentage">Percentage (%)</option>
                        </x-adminlte-select>
                    </div>
                    <div class="col-md-6">
                        <x-adminlte-input name="value" type="number" label="Discount Value" placeholder="10000 / 10">
                            <x-slot name="appendSlot"><div class="input-group-text font-weight-bold">Val</div></x-slot>
                        </x-adminlte-input>
                    </div>
                    <div class="col-md-6">
                        <x-adminlte-input name="min_purchase" type="number" label="Min. Purchase (Optional)" placeholder="50000"/>
                    </div>
                </div>
            </x-adminlte-card>
        </div>

        <div class="col-md-4">
            <x-adminlte-card title="Limits & Validity" theme="dark">
                <x-adminlte-input name="limit" type="number" label="Usage Limit" placeholder="100 users">
                    <x-slot name="prependSlot"><div class="input-group-text"><i class="fas fa-users"></i></div></x-slot>
                </x-adminlte-input>

                <div class="form-group">
                    <label>Duration Range</label>
                    <div class="input-group">
                        <input type="date" name="start_date" class="form-control">
                        <div class="input-group-append"><span class="input-group-text">to</span></div>
                        <input type="date" name="end_date" class="form-control">
                    </div>
                </div>

                <x-adminlte-select name="is_active" label="Status">
                    <option value="1">Active</option>
                    <option value="0">Draft / Disabled</option>
                </x-adminlte-select>

                <button type="submit" class="btn btn-success btn-block shadow mt-4">
                    <i class="fas fa-save"></i> PUBLISH PROMO
                </button>
            </x-adminlte-card>
        </div>
    </div>
</form>
@stop