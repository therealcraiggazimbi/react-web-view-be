<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log;


class CheckoutController extends Controller
{
    public function createSession(Request $request)
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            Log::info('Stripe API Key set.');

            $successUrl = 'https://example.com/success';
            $cancelUrl = 'https://example.com/cancel';

            // Log the URLs to ensure they are correct
            Log::info('Success URL: ' . $successUrl);
            Log::info('Cancel URL: ' . $cancelUrl);

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Test Product',
                        ],
                        'unit_amount' => 2000,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
            ]);

            Log::info('Stripe session created successfully.', ['session' => $session]);

            return response()->json(['url' => $session->url]);
        } catch (\Exception $e) {
            Log::error('Error creating Stripe session.', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
