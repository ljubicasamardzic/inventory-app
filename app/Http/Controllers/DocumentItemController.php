<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentItem;
use Illuminate\Http\Request;

class DocumentItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Document $document)
    {
        $new_item = $document->items()->create($request->only(['equipment_id', 'serial_number_id']));
        $new_item->equipment()->update(['available_quantity' => $new_item->equipment->available_quantity - 1]);
        return redirect(route('documents.edit', [$document->id]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DocumentItem  $documentItem
     * @return \Illuminate\Http\Response
     */
    public function show(DocumentItem $documentItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DocumentItem  $documentItem
     * @return \Illuminate\Http\Response
     */
    public function edit(DocumentItem $documentItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DocumentItem  $documentItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DocumentItem $documentItem)
    {
        $documentItem->update([ 'return_date' => date('Y-m-d H:i:s') ]);
        $documentItem->equipment()->update(['available_quantity' => $documentItem->equipment->available_quantity + 1]);
        return redirect()->back();
    }

    public function update_serial_number(Request $request) {
        DocumentItem::find($request->id)->update(['serial_number_id' => $request->serial_number_id]);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DocumentItem  $documentItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(DocumentItem $documentItem)
    {
        //
    }
}
