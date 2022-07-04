<?php

namespace App\Http\Controllers;

use App\Data\ProductData;
use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Product;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ShopController extends Controller
{
    public function show(Product $product)
    {
        return Inertia::render('Shop/Show', [
            'product' => ProductData::fromModel($product),
            'pspPublicKey' => config('services.stripe.public_key'),
        ]);
    }

    public function index()
    {
        return Inertia::render('Shop/Index', [
            'products' => ProductData::collection(Product::available()->get()),
        ]);
    }

    public function preparePayment(Product $product, Request $request)
    {
        $this->abortIfNotJson();

        /**
         * @todo: move to an action
         */

        /** @var StripeClient $stripe */
        $stripe = app(StripeService::class);
        $intent = $stripe->paymentIntents->create([
            'amount' => $product->price * $request->get('quantity') * 100,
            'currency' => 'eur',
            'automatic_payment_methods' => ['enabled' => true],
            'metadata' => [
                'email' => Auth::user()->email,
                'product' => $product->id,
            ]
        ]);

        $invoice = Invoice::create([
            'user_id' => Auth::user()->id,
            'product_id' => $product->id,
            'company_id' => Auth::user()->company->id,
            'quantity' => $request->get('quantity'),
            'transaction' => $intent->id,
            'price' => $product->price,
            'total' => $product->price * $request->get('quantity'),
            'status' => InvoiceStatus::Pending,
        ]);

        return [
            'client_secret' => $intent->client_secret,
            'invoice' => $invoice->id,
        ];
    }

    public function verifyPayment(Request $request)
    {
        $this->abortIfNotJson();

        $invoice = Invoice::findOrFail($request->get('invoice'));

        /** @var StripeClient $stripe */
        $stripe = app(StripeService::class);

        $intent = $stripe->paymentIntents->retrieve($invoice->transaction);

        if ($intent->status === 'succeeded') {
            $invoice->status = InvoiceStatus::Completed;
            $invoice->save();
        }

        return [
            'status' => $intent->status,
            'redirect' => sprintf('/nova/resources/invoices/%s', $invoice->id),
        ];
    }

}
