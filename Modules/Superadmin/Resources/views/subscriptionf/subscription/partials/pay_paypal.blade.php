<div class="col-md-12">

	{{-- <a href="{{action('\Modules\Superadmin\Http\Controllers\SubscriptionController@paypalExpressCheckout', [$package->id])}}"
		class="btn btn-primary"><i class="fa fa-paypal"></i> PayPal</a> --}}

		<a href="{{route('subscription-paypalExpressCheckout', [$package->id])}}"
			class="btn btn-primary"><i class="fa fa-paypal"></i> PayPal</a>
	
</div>