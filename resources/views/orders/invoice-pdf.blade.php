<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #111; }
        .top { display: table; width: 100%; margin-bottom: 18px; }
        .col { display: table-cell; vertical-align: top; }
        .right { text-align: right; }
        h1 { font-size: 18px; margin: 0 0 6px 0; }
        .muted { color: #666; }
        .box { border: 1px solid #e5e7eb; padding: 10px; border-radius: 6px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #e5e7eb; padding: 8px; }
        th { background: #f3f4f6; text-align: left; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .totals { margin-top: 10px; width: 100%; }
        .totals td { border: none; padding: 4px 0; }
        .grand { font-size: 14px; font-weight: bold; }

        /* Customer block (no box/table) */
        .customer-block { margin: 6px 0 12px 0; }
        .customer-title { font-weight: bold; margin-bottom: 4px; }

    </style>
</head>
<body>

@php
    $orderNumber = $order->order_number ?? ('ORD-' . $order->id);
    $orderDate   = $order->created_at ? $order->created_at->format('d M Y') : '';
    $shipping    = (float) ($order->shipping_cost ?? 0);
    $discount    = (float) ($order->discount_amount ?? 0);
    $total       = (float) ($order->total_amount ?? 0);
    $subtotal    = 0.0;

    // Customer
    $customerName  = $order->customer_name ?? optional($order->user)->name ?? 'N/A';
    $customerEmail = $order->customer_email ?? optional($order->user)->email ?? 'N/A';
    $customerPhone = $order->phone
        ?? optional($order->user)->phone
        ?? optional($order->shippingAddress)->phone
        ?? optional($order->billingAddress)->phone;

    // Address formatting helper
    $formatAddress = function ($addr) {
        if (!$addr) return null;

        $lines = [];

        $name = trim(($addr->first_name ?? '') . ' ' . ($addr->last_name ?? ''));
        if ($name !== '') $lines[] = $name;

        if (!empty($addr->phone)) $lines[] = $addr->phone;

        // If your Address model has formatted_address accessor, use it
        if (!empty($addr->formatted_address)) {
            $lines[] = $addr->formatted_address;
        } else {
            // Fallback common fields
            foreach (['address_line_1','address_line_2','city','state','postcode','zip','country'] as $f) {
                if (!empty($addr->$f)) $lines[] = $addr->$f;
            }
        }

        $out = trim(implode("\n", array_filter($lines)));
        return $out !== '' ? $out : null;
    };

    $pickupAddress = 'Lot # 5-34, Imbi Plaza, 28, Jln Imbi, Bukit Bintang, 55100 Kuala Lumpur.';

    $methodRaw =
        $order->delivery_method
        ?? $order->shipping_method
        ?? $order->shipping_type
        ?? '';

    $method = strtolower(trim((string) $methodRaw));

    $isSelfPickup =
        in_array($method, [
            'self_pickup',
            'self-pickup',
            'pickup',
            'collect',
            'store_pickup',
            'self_collection',
        ], true)
        || is_null($order->shipping_address_id);

    $billingText =
        $formatAddress($order->billingAddress)
        ?? ($isSelfPickup ? $formatAddress(optional($order->user)->addresses()->where('is_default',1)->first()) : null)
        ?? $formatAddress($order->shippingAddress)
        ?? 'N/A';

    $shippingText = $isSelfPickup
        ? $pickupAddress
        : ($formatAddress($order->shippingAddress) ?? 'N/A');

@endphp

<div class="top">
    <div class="col">
        <h1>INVOICE</h1>
        <div class="muted">Invoice #{{ $orderNumber }}</div>
        <div class="muted">Order Date: {{ $orderDate }}</div>
        <div class="muted">Payment: {{ $order->payment_method ?? 'N/A' }}</div>
        <div class="muted">Status: {{ $order->status ?? 'N/A' }} / {{ $order->payment_status ?? 'N/A' }}</div>
    </div>
    <div class="col right">
        <div style="font-weight:bold;">NR INTELLITECH SDN BHD</div>
        <div class="muted">store.nr-it.com</div>
        <div class="muted">Kuala Lumpur, Malaysia</div>
    </div>
</div>

{{-- Customer details ABOVE address (no box/table) --}}
<div class="customer-block">
    <div class="customer-title">Receiver</div>
    <div>
        {{ $customerName }}<br>
        {{ $customerEmail }}
        @if(!empty($customerPhone))
            <br>{{ $customerPhone }}
        @endif
    </div>
</div>

{{-- Address only in boxes --}}
<div class="top">
    <div class="col box" style="margin-right:10px;">
        <div style="font-weight:bold; margin-bottom:6px;">Billing Address</div>
        <div>{!! nl2br(e($billingText)) !!}</div>
    </div>
    <div class="col box">
        <div style="font-weight:bold; margin-bottom:6px;">
            {{ $isSelfPickup ? 'Self Pickup Location' : 'Shipping Address' }}
        </div>
        <div>{!! nl2br(e($shippingText)) !!}</div>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>Item</th>
            <th class="text-center" style="width:70px;">Qty</th>
            <th class="text-right" style="width:100px;">Price</th>
            <th class="text-right" style="width:110px;">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->orderItems as $item)
            @php
                $qty = (int) ($item->quantity ?? 0);
                $price = (float) ($item->price ?? 0);
                $lineTotal = $qty * $price;
                $subtotal += $lineTotal;

                // Safer name fallback
                $itemName = $item->product_name
                    ?? optional($item->product)->name
                    ?? ($item->name ?? 'Item');
            @endphp
            <tr>
                <td>{{ $itemName }}</td>
                <td class="text-center">{{ $qty }}</td>
                <td class="text-right">RM {{ number_format($price, 2) }}</td>
                <td class="text-right">RM {{ number_format($lineTotal, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<table class="totals">
    <tr>
        <td></td>
        <td class="text-right" style="width:200px;">Subtotal:</td>
        <td class="text-right" style="width:120px;">RM {{ number_format($subtotal, 2) }}</td>
    </tr>
    <tr>
        <td></td>
        <td class="text-right">Shipping Cost:</td>
        <td class="text-right">RM {{ number_format($shipping, 2) }}</td>
    </tr>
    <tr>
        <td></td>
        <td class="text-right">Discount:</td>
        <td class="text-right">-RM {{ number_format($discount, 2) }}</td>
    </tr>
    <tr>
        <td></td>
        <td class="text-right grand">Total Amount:</td>
        <td class="text-right grand">RM {{ number_format($total ?: (($subtotal + $shipping) - $discount), 2) }}</td>
    </tr>
</table>

</body>
</html>
