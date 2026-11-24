<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CustomerDemoController extends Controller
{
    public function home(): View
    {
        return $this->page('home');
    }

    public function catalog(?string $category = null): View
    {
        return $this->page('catalog', function (array $demo) use ($category): array {
            $category ??= array_key_first($demo['categories']);
            abort_unless(isset($demo['categories'][$category]), 404);

            return ['category' => $category];
        });
    }

    public function item(Request $request, string $item): View
    {
        return $this->page('item', function (array $demo) use ($request, $item): array {
            abort_unless(isset($demo['items'][$item]), 404);
            $category = $request->query('kategori');
            $category = is_string($category) && isset($demo['categories'][$category]) ? $category : $demo['items'][$item]['category'];
            $back = match ($request->query('dari')) {
                'pesanan' => route('customer.cart'),
                'beranda' => route('customer.home'),
                default => route('customer.catalog', $category),
            };

            return ['item' => $demo['items'][$item], 'back' => $back];
        });
    }

    public function cart(): View
    {
        return $this->page('cart');
    }

    public function checkout(): View
    {
        return $this->page('checkout');
    }

    public function success(): View
    {
        return $this->page('receipt', fn () => ['summary' => true]);
    }

    public function order(): View
    {
        return $this->page('receipt', fn () => ['summary' => false]);
    }

    public function outlet(): View
    {
        return $this->page('outlet');
    }

    private function page(string $view, ?callable $extra = null): View
    {
        $demo = require resource_path('demo/customer.php');

        return view('customer.'.$view, ['demo' => $demo] + ($extra ? $extra($demo) : []));
    }
}
