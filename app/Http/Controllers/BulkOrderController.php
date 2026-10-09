<?php

namespace App\Http\Controllers;

use App\Models\BulkOrder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BulkOrderController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->orderBy('category_name')
            ->get();
        $products = Product::query()
            ->whereNull('deleted_at')
            ->orderBy('category_id')
            ->orderBy('product_name')
            ->get(['id', 'category_id', 'product_name', 'cate_name', 'slug', 'product_image', 'product_regular_price']);
        $comboCategory = $categories->first(function ($cat) {
            $cName = strtolower($cat->category_name);

            return str_contains($cName, 'box') || str_contains($cName, 'combo') || str_contains($cName, 'suite');
        });
        $comboProducts = $products->filter(function ($p) use ($comboCategory) {
            if ($comboCategory && $p->category_id == $comboCategory->id) {
                return true;
            }
            $name = strtolower($p->product_name.' '.($p->cate_name ?? ''));

            return str_contains($name, 'box') || str_contains($name, 'combo') || str_contains($name, 'gift');
        });

        return view('pages.bulk-order', compact('categories', 'products', 'comboCategory', 'comboProducts'));
    }

    public function store(Request $request)
    {
        if (! empty($request->input('website_hp'))) {
            return back()->with('success', 'Your corporate gifting inquiry has been submitted successfully.');
        }
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'company_name' => ['required', 'string', 'max:200'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:50'],
            'product_interest' => ['required', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:5000'],
        ], [
            'name.required' => 'Please enter your full name.',
            'company_name.required' => 'Please enter your company or organization name.',
            'email.required' => 'Please enter your corporate email address.',
            'email.email' => 'Please enter a valid email address.',
            'phone.required' => 'Please enter your contact phone or WhatsApp number.',
            'product_interest.required' => 'Please select a product or combo box of interest.',
        ]);
        $enrichedMessageLines = ['Company: '.($data['company_name'] ?? 'N/A'), 'Selected Product / Combo Box: '.($data['product_interest'] ?? 'General Curation'), '--------------------------------------------------', 'Client Message / Scope & Requirements:', $data['message'] ?? 'No additional notes provided.'];
        $formattedMessage = implode("\n", $enrichedMessageLines);
        $subjectLine = 'Bulk Order Inquiry: '.($data['company_name'] ?? $data['name']).' - '.($data['product_interest'] ?? 'Corporate Gifting');
        $insertData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'subject' => $subjectLine,
            'phone' => $data['phone'],
            'message' => $formattedMessage,
            'created_at' => now('UTC'),
            'updated_at' => now('UTC'),
        ];
        BulkOrder::query()
            ->create($insertData);
        // 1. Send Admin Alert Email
        try {
            $adminEmail = env('CONTACT_MAIL_TO') ?: config('mail.from.address');
            if ($adminEmail) {
                Mail::send('emails.bulk-order-message', ['inquiry' => $data], function ($message) use ($adminEmail, $data, $subjectLine) {
                    $message->to($adminEmail)
                        ->subject($subjectLine);
                    if (! empty($data['email'])) {
                        $message->replyTo($data['email'], $data['name']);
                    }
                });
            }
        } catch (\Throwable $exception) {
            Log::error('Bulk order admin notification mail failed', ['email' => $data['email'] ?? null, 'error' => $exception->getMessage()]);
        }
        // 2. Send Customer Confirmation / Acknowledgement Email
        try {
            if (! empty($data['email'])) {
                Mail::send('emails.bulk-order-user-acknowledgement', ['inquiry' => $data], function ($message) use ($data) {
                    $message->to($data['email'], $data['name'])
                        ->subject('Inquiry Received — House of KNP Corporate Gifting');
                });
            }
        } catch (\Throwable $exception) {
            Log::error('Bulk order customer acknowledgement mail failed', ['email' => $data['email'] ?? null, 'error' => $exception->getMessage()]);
        }
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Your corporate inquiry has been submitted successfully. Our VIP concierge will contact you shortly.']);
        }

        return back()->with('success', 'Thank you! Your bulk order inquiry has been received. Our corporate gifting concierge will reach out to you within 2 business hours.');
    }
}
