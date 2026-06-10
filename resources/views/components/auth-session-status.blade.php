@props(['status'])

@if ($status)
<script>
    Swal.fire({ icon: 'success', title: 'Berhasil', text: '{{ addslashes($status) }}', timer: 3000, showConfirmButton: false, position: 'top-end', toast: true });
</script>
@endif
