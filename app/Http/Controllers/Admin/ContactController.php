<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(15);
        return view('admin.contact.index', compact('messages'));
    }

    public function show(ContactMessage $contact)
    {
        $contact->update(['is_read' => true]);
        return view('admin.contact.show', compact('contact'));
    }

    public function destroy(ContactMessage $contact)
    {
        $contact->delete();
        return redirect()->route('admin.contact.index')
                        ->with('success', 'Pesan berhasil dihapus.');
    }
}
