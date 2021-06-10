<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentRequest;
use App\Models\Document;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Http\Request;

class DocumentController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Document::class, 'document');
    }

    public function index()
    {
        $documents = Document::all();
        $content_header = "Documents list";
        $breadcrumbs = [
            [ 'name' => 'Home', 'link' => '/' ],
            [ 'name' => 'Documents list', 'link' => '/documents' ],
        ];
        return view('documents.index', compact(['documents', 'content_header', 'breadcrumbs']));
    }

    public function create()
    {
        $users = User::all();
        $content_header = "Add New Document";
        $breadcrumbs = [
            [ 'name' => 'Home', 'link' => '/' ],
            [ 'name' => 'Documents list', 'link' => '/documents' ],
            [ 'name' => 'New Document', 'link' => '/documents/create' ],
        ];
        return view('documents.create', compact(['users', 'content_header', 'breadcrumbs']));
    }

    public function store(DocumentRequest $request)
    {
        $request->merge(['admin_id' => auth()->id() ]);
        Document::query()->create($request->all());
        return redirect(route('documents.index'));
    }

    public function show(Document $document)
    {
        $users = User::all();
        $equipment = Equipment::query()->available()->get();
        $items = $document->items;
        $content_header = "Document details";
        $breadcrumbs = [
            [ 'name' => 'Home', 'link' => '/' ],
            [ 'name' => 'Document list', 'link' => '/documents' ],
            [ 'name' => 'Document details', 'link' => '/users/'.$document->id ],
        ];
        return view('documents.show', compact(['content_header', 'breadcrumbs', 'document', 'users', 'items', 'equipment']));
    }

    public function edit(Document $document)
    {
        $users = User::all();
        $equipment = Equipment::query()->available()->get();
        $items = $document->items;
        $content_header = "Document details";
        $breadcrumbs = [
            [ 'name' => 'Home', 'link' => '/' ],
            [ 'name' => 'Document list', 'link' => '/documents' ],
            [ 'name' => 'Document details', 'link' => '/users/'.$document->id ],
        ];
        return view('documents.edit', compact(['content_header', 'breadcrumbs', 'document', 'users', 'items', 'equipment']));
    }

    public function update(Request $request, Document $document)
    {
        //
    }

    public function destroy(Document $document)
    {
        //
    }
}
