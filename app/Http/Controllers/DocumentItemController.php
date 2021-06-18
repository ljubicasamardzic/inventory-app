<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentItemController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request, Document $document)
    {
        DB::beginTransaction();
        $new_item = $document->items()->create($request->only(['equipment_id', 'serial_number_id']));
        
        if ($new_item) {
            if ($new_item->equipment()->update(['available_quantity' => $new_item->equipment->available_quantity - 1])) {
                DB::commit();
                alert()->success('Document item added!', 'Success!');
            } else {
                DB::rollBack();
                alert()->error('Something went wrong!', 'Oops..');
            }
        } else {
            DB::rollBack();
            alert()->error('Something went wrong!', 'Oops..');
        }
        return redirect("documents/$document->id");
    }

    public function show(DocumentItem $documentItem)
    {
        //
    }

    public function edit(DocumentItem $documentItem)
    {
        //
    }

    public function update(Request $request, DocumentItem $documentItem)
    {
        DB::beginTransaction();
        if ($documentItem->update([ 'return_date' => date('Y-m-d H:i:s')])) {
            if ($documentItem->equipment()->update(['available_quantity' => $documentItem->equipment->available_quantity + 1])) {
                DB::commit();
                alert()->success('Equipment returned!', 'Success!');
            } else {
                DB::rollBack();
                alert()->error('Something went wrong!', 'Oops..');
            }
        } else {
            DB::rollBack();
            alert()->error('Something went wrong!', 'Oops..');
        }
        
        return redirect()->back();
    }

    public function update_serial_number(Request $request) {
        $update = DocumentItem::find($request->id)->update(['serial_number_id' => $request->serial_number_id]);
        if ($update) {
            alert()->success('Serial number updated!', 'Success!');
        } else {
            alert()->error('Something went wrong!', 'Oops..');
        }
        return redirect()->back();
    }

    public function destroy(DocumentItem $documentItem)
    {
        //
    }
}
