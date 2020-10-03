<style>
    .alert {
        padding-top: 5px;
        padding-bottom: 5px;
    }
    .alert-error,
    .alert-danger {
        color: red
    }
    .alert-warning {
        color: orange
    }
</style>
@vardump($attributes)
<div class="alert alert-{{ $type }}">
   {{ $message }}
   {{ $slot }}
</div>
