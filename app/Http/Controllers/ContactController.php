<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ContactController extends Controller
{
    public function index()
    {
        return view('pages.contact');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'regex:/^[0-9]{10,15}$/'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:2000'],
        ], [
            'name.required' => 'Please enter name.',
            'email.required' => 'Please enter email address.',
            'email.email' => 'Please enter a valid email address.',
            'phone.required' => 'Please enter phone number.',
            'phone.regex' => 'Phone number must be 10 to 15 digits.',
            'subject.required' => 'Please enter subject.',
            'message.required' => 'Please enter message.',
        ]);
        $contactData = [
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone_number' => $data['phone'] ?? null,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'created_at' => now('UTC'),
            'updated_at' => now('UTC'),
        ];
        Contact::query()
            ->create($contactData);
        try {
            $adminEmail = env('CONTACT_MAIL_TO') ?: config('mail.from.address');
            Mail::send('emails.contact-message', ['contact' => $data], function ($message) use ($adminEmail, $data) {
                $message->to($adminEmail)
                    ->subject('Contact Message Received - '.$data['name']);
                if (! empty($data['email'])) {
                    $message->replyTo($data['email'], $data['name']);
                }
            });
        } catch (Throwable $exception) {
            Log::error('Contact form mail failed', ['email' => $data['email'] ?? null, 'error' => $exception->getMessage()]);

            return back()->withErrors(['contact_form' => 'Message saved, but admin email could not be sent.'])
                ->withInput();
        }

        return back()->with('contact_success', 'Your message has been sent successfully.');
    }
}
