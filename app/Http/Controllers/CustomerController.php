<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\Customer\Cart;
use App\Services\Customer\Catalog;
use App\Services\Customer\Ordering;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function __construct(private Catalog $catalog, private Cart $cart, private Ordering $ordering) {}

    public function home(Request $request): View
    {
        $code = $request->query('meja');

        return $this->page($request, 'home', ['suggestedTable' => is_string($code) ? mb_substr($code, 0, 255) : null]);
    }

    public function catalog(Request $request, ?string $category = null): View
    {
        return $this->page($request, 'catalog', function (array $customer) use ($category): array {
            abort_if($category !== null && !isset($customer['categories'][$category]), 404);

            return ['category' => $category ?? array_key_first($customer['categories'])];
        });
    }

    public function item(Request $request, string $item): View
    {
        return $this->page($request, 'item', function (array $customer) use ($request, $item): array {
            abort_unless(isset($customer['items'][$item]), 404);
            $category = $customer['items'][$item]['category'];
            $back = match ($request->query('dari')) {
                'pesanan' => route('customer.cart'),
                'beranda' => route('customer.home'),
                default => route('customer.catalog', $category),
            };

            return ['item' => $customer['items'][$item], 'back' => $back];
        });
    }

    public function table(Request $request): RedirectResponse
    {
        $input = $request->validate(['code' => 'required|string|max:255']);
        $this->cart->selectTable($request->session(), $input['code']);

        return redirect()->route('customer.home')->with('customer_notice', 'Nomor meja sudah dipilih.');
    }

    public function cart(Request $request): View
    {
        return $this->page($request, 'cart');
    }

    public function cartState(Request $request): JsonResponse
    {
        return response()->json($this->cart->state($request->session()));
    }

    public function updateCart(Request $request): JsonResponse
    {
        $input = $request->validate([
            'action' => ['required', Rule::in(['item', 'quantity', 'remove', 'details'])],
            'menu' => 'required_if:action,item|integer|min:1',
            'line' => 'required_if:action,quantity,remove|nullable|uuid',
            'quantity' => 'required_if:action,item,quantity|integer|min:1|max:99',
            'options' => 'nullable|array|max:20',
            'options.*' => 'integer|min:0|max:99',
            'note' => 'nullable|string|max:160',
            'type' => ['nullable', Rule::in(['dine-in', 'takeaway'])],
        ]);

        return response()->json($this->cart->change($request->session(), $input));
    }

    public function checkout(Request $request): View
    {
        return $this->page($request, 'checkout', ['simulation' => $this->ordering->simulationEnabled()]);
    }

    public function storeOrder(Request $request): JsonResponse
    {
        $input = $request->validate([
            'checkout_key' => 'required|uuid',
            'quote' => 'required|string|size:64',
            'name' => 'required|string|min:2|max:80',
            'phone' => ['bail', 'nullable', 'string', 'max:24', 'regex:/^\+?[\d ()-]+$/', function ($attribute, $value, $fail) {
                $length = strlen(preg_replace('/\D/', '', $value));
                if ($length < 8 || $length > 15) {
                    $fail('Masukkan nomor ponsel 8–15 digit.');
                }
            }],
            'email' => 'nullable|email:rfc|max:100',
            'payment' => ['required', Rule::in(['qris'])],
            'consent' => 'accepted',
        ]);

        $order = $this->ordering->place($request->session(), $input);

        return response()->json(['url' => route('customer.payment', $order->number)]);
    }

    public function payment(Request $request, string $number): View|RedirectResponse
    {
        $order = $this->ordering->owned($request->session(), $number);
        if ($order->status !== Order::AWAITING) {
            return redirect()->route($order->status === Order::CONFIRMED ? 'customer.success' : 'customer.order', $number);
        }

        return $this->page($request, 'payment', [
            'order' => $order,
            'simulation' => $this->ordering->simulationEnabled() && $order->payment?->provider === Payment::SIMULATION,
        ]);
    }

    public function pay(Request $request, string $number): RedirectResponse
    {
        $order = $this->ordering->owned($request->session(), $number);
        $this->ordering->resolveSimulation($order, true);

        return redirect()->route($order->refresh()->status === Order::CONFIRMED ? 'customer.success' : 'customer.order', $number);
    }

    public function cancel(Request $request, string $number): RedirectResponse
    {
        $order = $this->ordering->owned($request->session(), $number);
        $this->ordering->resolveSimulation($order, false);

        return redirect()->route('customer.order', $number);
    }

    public function success(Request $request, ?string $number = null): View|RedirectResponse
    {
        $order = $this->receiptOrder($request, $number);
        if ($order && $order->status !== Order::CONFIRMED) {
            return redirect()->route('customer.order', $order->number);
        }

        return $this->page($request, 'receipt', ['order' => $order, 'summary' => true]);
    }

    public function order(Request $request, ?string $number = null): View
    {
        return $this->page($request, 'receipt', ['order' => $this->receiptOrder($request, $number), 'summary' => false]);
    }

    public function outlet(Request $request): View
    {
        return $this->page($request, 'outlet');
    }

    private function receiptOrder(Request $request, ?string $number): ?Order
    {
        $number ??= $request->session()->get('customer.last_order');
        if (!$number) {
            return null;
        }

        return $this->ordering->owned($request->session(), $number)->load(['items', 'payment']);
    }

    private function page(Request $request, string $view, array|callable $extra = []): View
    {
        $customer = $this->catalog->data();
        $state = $this->cart->state($request->session());

        return view('customer.'.$view, ['customer' => $customer, 'state' => $state] + (is_callable($extra) ? $extra($customer) : $extra));
    }
}
