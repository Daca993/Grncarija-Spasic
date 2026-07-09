<?php

namespace App\Http\Controllers;

use App\Domain\Cart\Services\CartService;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cart
    ) {}

    public function show(): View
    {
        return view('checkout.show');
    }
}
