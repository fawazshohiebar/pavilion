<x-mail::message>
# {{ $email_config['subject'] }}
 
Here are the details:
 
<x-mail::panel>
@foreach ($fields as $item)
@continue($item['handle'] == 'turnstile_token')
@continue($item['fieldtype'] == 'assets')
@continue($item['fieldtype'] == 'files')
@if ($item['handle'] == 'country')
<strong>{{ $item['display'] }}:</strong><br />
{{ \App\Helpers\CountryHelper::getCountryName($item['value'], 'en') }}<br>
@else
<strong>{{ $item['display'] }}:</strong><br />
{{ isset($item['value']) ? $item['value'] : '' }}<br>
@endif
@endforeach
</x-mail::panel>
</x-mail::message>