@component('mail::layout')

@slot('header')
@component('mail::header', ['url' => '#'])
	<img src="{{ asset('images/tbnb-logo.png') }}?dummy={{ rand() }}" width="200" height="40" align="center" style="display: block; max-width:200px; max-height: 120px; margin:auto;" />
@endcomponent
@endslot

<div style="text-align: center; margin-top: 50px; margin-bottom: 30px; font-size: 18px;">
<h1 aligin="center" style="text-align:center;">{{ __('email.title') }}</h1>

<p align="center" style="text-align: center;">
    {{ __('email.message', ['user_involved' => $user_involved->name]) }}
</p>
</div>

@component('mail::button', ['url' => route('products.show', $product->uuid)])
{{ __('email.button') }}
@endcomponent

@slot('footer')
@component('mail::footer')
&copy; {{ date('Y') }} {{ config('app.name') }}
@endcomponent
@endslot
@endcomponent
