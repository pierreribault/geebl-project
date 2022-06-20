<div class="h-90 max-h-90 w-50 max-w-50">
    <img src=""/>
    <h1>{{ $invoice->product->name }}</h1>
    <p>{{ $invoice->price }} * {{ $invoice->quantity }} = {{ $invoice->total }}</p>
</div>
