<x-mail::message>
# Export {{ ucfirst($type) }} Siap

File export data **{{ $type }}** telah siap diunduh.

<x-mail::button :url="$url">
Download File
</x-mail::button>

Salam,<br>
{{ config('app.name') }}
</x-mail::message>
