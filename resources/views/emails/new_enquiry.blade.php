<x-mail::message>
# {{ $email_config['subject'] }}
 
Here are the details:
 
<x-mail::panel>
@foreach ($fields as $item)
@continue($item['handle'] == 'turnstile_token')
@if ($item['handle'] == 'country')
<strong>{{ $item['display'] }}:</strong> {{ \App\Helpers\CountryHelper::getCountryName($item['value'], 'en') }}<br>
@else
<strong>{{ $item['display'] }}:</strong> {{ $item['value'] }}<br>
@endif
@endforeach
</x-mail::panel>
</x-mail::message>