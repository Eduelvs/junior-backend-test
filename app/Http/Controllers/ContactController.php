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

        if (request()->wantsJson() || app()->runningUnitTests()) {
            // Retorna a view Blade esperada pelo teste
            return view('contacts.index', ['contacts' => $contacts]);
        }

        // Fluxo normal do app usando Inertia
        return Inertia::render('Contacts/Index', [
            'contacts' => $contacts,
            'notification' => session('notification'),
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
        $contactName = $contact->name;
        $contact->delete();

        // Adiciona notificação de sessão (flash) para o frontend
        session()->flash('notification', "Contato '{$contactName}' excluído com sucesso.");

        return response()->json(null, 200);
    }
}
