<?php

// app/Http/Controllers/ContactController.php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::paginate(10);
        return view('contacts.index', compact('contacts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:contacts,email',
            'phone' => ['required', 'string', 'regex:/^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/']
        ]);

        $data['phone'] = preg_replace('/\D/', '', $data['phone']);

        Contact::create($data);

        return response()->json(null, 200);
    }

    public function update(Request $request, Contact $contact)
    {
        $data = $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:contacts,email,' . $contact->id,
            'phone' => ['required', 'string', 'regex:/^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/']
        ]);

        $data['phone'] = preg_replace('/\D/', '', $data['phone']);

        $contact->update($data);

        return response()->json(null, 200);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->json(null, 200);
    }
}
