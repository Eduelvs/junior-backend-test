<?php
namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::paginate(10);

        return Inertia::render('Contacts/Index', [
            'contacts' => $contacts,
        ]);
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

        return redirect()->route('contacts.index');
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

        return redirect()->route('contacts.index');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('contacts.index');
    }
}
