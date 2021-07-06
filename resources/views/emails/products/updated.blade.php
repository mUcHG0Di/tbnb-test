@component('mail::layout')

@slot('header')
@component('mail::header', ['url' => '#'])
	<img src="{{ asset('images/tbnb-logo.png') }}?dummy={{ rand() }}" width="200" height="40" align="center" style="display: block; max-width:200px; max-height: 120px; margin:auto;" />
@endcomponent
@endslot

<div style="text-align: center; margin-top: 50px; margin-bottom: 30px; font-size: 18px;">
<h1 aligin="center" style="text-align:center;">Your product was modified</h1>

<p align="center" style="text-align: center;">
    Your product was modified by {{ $user_involved->name }}
</p>
</div>

@component('mail::button', ['url' => route('products.show', $product->uuid)])
Show product
@endcomponent

@slot('footer')
@component('mail::footer')
&copy; {{ date('Y') }} {{ config('app.name') }}
@endcomponent
@endslot
@endcomponent
