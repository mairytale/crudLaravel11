<?php

namespace App\Http\Controllers;

use App\Models\Contact; 
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
//import Facades Storage
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    /**
     * index
     *
     * @return View
     */
    public function index() : View
    {
        // get all contacts
        $contacts = Contact::latest()->paginate(10);

        // render view with contacts
        return view('contacts.index', compact('contacts'));
    }

    /**
     * create
     *
     * @return View
     */
    public function create(): View
    {
        return view('contacts.create');
    }

    /**
     * store
     *
     * @param  Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // validate form
        $request->validate([
            'number' => 'required|min:5',
            'name'   => 'required|min:10',
        ]);

        // create contact
        Contact::create([
            'number' => $request->number,
            'name'   => $request->name,
        ]);

        // redirect to index
        return redirect()->route('contacts.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * edit
     *
     * @param  string $id
     * @return View
     */
    public function edit(string $id): View
    {
        // get contact by ID
        $contact = Contact::findOrFail($id);

        // render view with contact
        return view('contacts.edit', compact('contact'));
    }

    /**
     * update
     *
     * @param  Request $request
     * @param  string $id
     * @return RedirectResponse
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        // validate form
        $request->validate([
            'number' => 'required|min:5',
            'name'   => 'required|min:10',
        ]);

        // get contact by ID
        $contact = Contact::findOrFail($id);

        // update contact
        $contact->update([
            'number' => $request->number,
            'name'   => $request->name,
        ]);

        // redirect to index
        return redirect()->route('contacts.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

      /**
     * destroy
     *
     * @param  mixed $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        //get product by ID
        $contact = Contact::findOrFail($id);

        //delete image
        Storage::delete('public/contacts/'. $contact->image);

        //delete product
        $contact->delete();

        //redirect to index
        return redirect()->route('contacts.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}
