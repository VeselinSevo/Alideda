<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function create()
    {
        return view('contact.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'subject' => ['required', 'string', 'max:120'],
            'message' => ['required', 'string', 'max:5000'],

            // Only required for guests
            'name' => [auth()->check() ? 'nullable' : 'required', 'string', 'max:80'],
            'email' => [auth()->check() ? 'nullable' : 'required', 'email', 'max:120'],
        ]);

        $payload = [
            'user_id' => $user?->id,
            'name' => $user?->name ?? $data['name'],
            'email' => $user?->email ?? $data['email'],
            'subject' => $data['subject'],
            'message' => $data['message'],
        ];

        ContactMessage::create($payload);

        // Optional later: email all admins
        // $admins = User::where('is_admin', true)->pluck('email')->all();
        // Mail::to($admins)->send(new ContactMessageMail(...));

        return redirect()
            ->route('contact.create')
            ->with('success', 'Message sent to admin. Thanks!');
    }
}
