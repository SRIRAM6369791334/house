<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;background:#f5f3f7;font-family:Arial,sans-serif;color:#171b2a;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0"><tr><td align="center" style="padding:24px 12px;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:680px;background:#fff;border-radius:12px;overflow:hidden;">
<tr><td align="center" style="background:#1a1a1a;border-bottom:4px solid #cc0000;padding:26px;color:#fff;font-size:26px;font-weight:800;letter-spacing:2px;">HOUSE OF KNP</td></tr>
<tr><td style="padding:34px 28px;">
<div style="text-align:center;font-size:44px;color:#12b981;">&#10003;</div>
<h1 style="margin:8px 0;text-align:center;font-size:26px;">Order Confirmed!</h1>
<p style="text-align:center;color:#677086;line-height:1.6;">Thank you for shopping with House of KNP. Your order has been successfully confirmed.</p>
<p style="margin-top:28px;">Hi {{ $order->billing_name ?: 'Customer' }},</p>
<div style="background:#fff7ed;border:1px solid #fdc98b;border-radius:8px;padding:16px;text-align:center;margin:18px 0 30px;">Order number: <strong style="color:#cc0000;">#{{ $order->order_number }}</strong></div>
<h2 style="font-size:18px;">Order Summary</h2>
<table width="100%" cellpadding="10" cellspacing="0" style="border-collapse:collapse;font-size:14px;">
<thead><tr style="background:#1a1a1a;color:#fff;"><th align="left">Product</th><th align="center">Qty</th><th align="right">Total</th></tr></thead>
<tbody>@foreach($items as $item)<tr style="border-bottom:1px solid #e5e7eb;"><td>{{ $item->product_name }}</td><td align="center">{{ $item->quantity }}</td><td align="right">{!! house_money($item->total) !!}</td></tr>@endforeach</tbody>
</table>
<table width="100%" cellpadding="7" cellspacing="0" style="margin-top:22px;background:#f8f8f8;border-radius:8px;font-size:14px;">
<tr><td>Subtotal</td><td align="right">{!! house_money($order->subtotal) !!}</td></tr>
<tr><td>Included GST</td><td align="right">{!! house_money($order->gst_amount) !!}</td></tr>
<tr><td>Shipping</td><td align="right">{!! house_money($order->shipping_charge) !!}</td></tr>
@if((float)$order->coupon_discount > 0)<tr><td>Coupon Discount</td><td align="right">-{!! house_money($order->coupon_discount) !!}</td></tr>@endif
<tr><td style="border-top:1px dashed #ccd0d8;font-size:18px;font-weight:800;">Grand Total</td><td align="right" style="border-top:1px dashed #ccd0d8;color:#cc0000;font-size:20px;font-weight:800;">{!! house_money($order->total_amount) !!}</td></tr>
</table>
<h2 style="font-size:18px;margin-top:30px;">Shipping Address</h2>
<p style="color:#566074;line-height:1.65;">{{ $order->shipping_name }}<br>{{ $order->shipping_street }}<br>{{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_pincode }}<br>Phone: {{ $order->shipping_phone }}</p>
<div style="background:#e8f4fd;border:1px solid #b6d4fe;border-radius:8px;padding:14px;margin-top:20px;font-size:13px;color:#084298;">
    <strong>Note:</strong> If you checked out as a guest, an account has been securely created for you to track your orders. To access it, please use the <strong>Forgot Password</strong> option on our login page to set your password.
</div>
</td></tr>
<tr><td align="center" style="background:#fff2dc;padding:20px;color:#74511c;font-size:13px;line-height:1.6;">We will notify you once your order is shipped.<br>Thank you for choosing House of KNP.</td></tr>
</table></td></tr></table>
</body></html>
