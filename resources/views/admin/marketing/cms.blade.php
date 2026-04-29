@extends('adminlte::page')

@section('title', 'CMS | Banner Manager')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="font-weight-bold text-dark">Banner & CMS Manager</h1>

    <button class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#modalAddBanner">
        <i class="fas fa-plus-circle mr-1"></i> Add New Banner
    </button>
</div>
@stop

@section('content')

<div class="row">

@forelse($banners as $banner)

<div class="col-md-4 mb-4">
    <div class="card h-100 shadow-sm border-0">

        <img src="{{ $banner->image_path ? asset('storage/'.$banner->image_path) : asset('images/no-image.png') }}"
             style="height:180px;object-fit:cover; width:100%;" class="card-img-top">

        <div class="card-body">
            <h5 class="font-weight-bold">{{ $banner->title }}</h5>
            <p class="text-muted small">{{ Str::limit($banner->subtitle, 100) }}</p>

            <div class="d-flex justify-content-between align-items-center">
                <span class="badge badge-{{ $banner->is_active ? 'success':'danger' }} px-3 py-2">
                    {{ $banner->is_active ? 'Active':'Inactive' }}
                </span>
                <small class="text-secondary">Priority: <b>{{ $banner->order_priority }}</b></small>
            </div>
        </div>

        <div class="card-footer bg-white text-right border-top-0">

            {{-- EDIT --}}
            <button
                type="button"
                class="btn btn-sm btn-outline-primary btn-edit-banner"
                data-url="{{ route('admin.marketing.cms.update', $banner->id) }}"
                data-title="{{ $banner->title }}"
                data-subtitle="{{ $banner->subtitle }}"
                data-link="{{ $banner->link_url }}"
                data-priority="{{ $banner->order_priority }}"
                data-active="{{ $banner->is_active }}"
                data-toggle="modal"
                data-target="#modalEditBanner">
                <i class="fas fa-edit"></i> Edit
            </button>

            {{-- DELETE --}}
            <form action="{{ route('admin.marketing.cms.destroy', $banner->id) }}"
                  method="POST"
                  class="d-inline delete-form">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-sm btn-outline-danger btn-delete-banner">
                    <i class="fas fa-trash"></i>
                </button>
            </form>

        </div>

    </div>
</div>

@empty

<div class="col-12 text-center mt-5">
    <div class="text-muted">
        <i class="fas fa-images fa-4x opacity-25"></i>
        <p class="mt-3">Belum ada banner yang diunggah.</p>
    </div>
</div>

@endforelse

</div>


{{-- ADD MODAL --}}
<form action="{{ route('admin.marketing.cms.store') }}"
      method="POST"
      enctype="multipart/form-data">

@csrf

<x-adminlte-modal id="modalAddBanner" title="Upload Banner" theme="primary" v-centered shadow>

    <x-adminlte-input-file name="image" label="Banner Image" placeholder="Pilih gambar..." required legend="Pilih"/>

    <x-adminlte-input name="title" label="Title" placeholder="Judul Banner" required />

    <x-adminlte-input name="link_url" label="Link URL" placeholder="https://..." />

    <x-adminlte-textarea name="subtitle" label="Subtitle" placeholder="Deskripsi singkat..." />

    <x-adminlte-input name="order_priority" type="number" label="Priority" value="0" />

    <div class="custom-control custom-switch mt-3">
        <input type="checkbox"
               name="is_active"
               class="custom-control-input"
               id="isActiveSwitch"
               checked
               value="1">
        <label class="custom-control-label" for="isActiveSwitch">
            Publish Banner
        </label>
    </div>

    <x-slot name="footerSlot">
        <button type="submit" class="btn btn-success shadow-sm">
            <i class="fas fa-save mr-1"></i> Save Banner
        </button>
    </x-slot>

</x-adminlte-modal>

</form>



{{-- EDIT MODAL --}}
<form id="editBannerForm"
      method="POST"
      enctype="multipart/form-data">

@csrf
@method('PUT')

<x-adminlte-modal id="modalEditBanner" title="Edit Banner" theme="info" v-centered shadow>

    <x-adminlte-input-file name="image" label="Change Image" placeholder="Kosongkan jika tidak ingin ganti..." legend="Pilih"/>

    <x-adminlte-input name="title" id="edit_title" label="Title" required />

    <x-adminlte-input name="link_url" id="edit_link" label="Link URL" />

    <x-adminlte-textarea name="subtitle" id="edit_subtitle" label="Subtitle" />

    <x-adminlte-input name="order_priority" id="edit_priority" type="number" label="Priority" />

    <div class="custom-control custom-switch mt-3">
        <input type="checkbox"
               name="is_active"
               class="custom-control-input"
               id="edit_is_active"
               value="1">
        <label class="custom-control-label" for="edit_is_active">
            Banner Active
        </label>
    </div>

    <x-slot name="footerSlot">
        <button type="submit" class="btn btn-info shadow-sm">
            <i class="fas fa-save mr-1"></i> Update Changes
        </button>
    </x-slot>

</x-adminlte-modal>

</form>

@stop



@section('js')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {

    // Toast Configuration
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });

    // 1. NOTIFIKASI SUKSES
    @if(session('success'))
        Toast.fire({
            icon: 'success',
            title: "{{ session('success') }}"
        });
    @endif

    // 2. NOTIFIKASI ERROR/VALIDASI
    @if($errors->any() || session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "{{ session('error') ?? 'Ada kesalahan pada input Anda.' }}",
        });
    @endif

    // EDIT DATA LOGIC
    $('.btn-edit-banner').click(function () {
        let url      = $(this).data('url');
        let title    = $(this).data('title');
        let subtitle = $(this).data('subtitle');
        let link     = $(this).data('link');
        let priority = $(this).data('priority');
        let active   = $(this).data('active');

        $('#editBannerForm').attr('action', url);
        $('#edit_title').val(title);
        $('#edit_subtitle').val(subtitle);
        $('#edit_link').val(link);
        $('#edit_priority').val(priority);
        $('#edit_is_active').prop('checked', active == 1);
    });

    // DELETE CONFIRMATION
    $('.btn-delete-banner').click(function () {
        let form = $(this).closest('form');

        Swal.fire({
            title: 'Hapus Banner?',
            text: 'Tindakan ini tidak dapat dibatalkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

});
</script>

@stop