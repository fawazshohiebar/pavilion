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
<?php
try {
    echo $item['value'];
} catch (\Throwable $th) {
    
    foreach ($item['value'] as $key => $ii) {
        try {
            if (isset($ii['value']) && is_array($ii['value'])) {
                echo implode(', ', $ii['value']);
                echo '<br>';
            } elseif (isset($ii['value'])) {
                echo $ii['value'];
                echo '<br>';
            }
        } catch (\Throwable $e) {
            info('Error displaying array item: ' . $e->getMessage());
            info('Array item data: ' . print_r($ii, true));
        }
    }
}
?>
<br />
<br />
@endif
@endforeach
</x-mail::panel>
</x-mail::message>