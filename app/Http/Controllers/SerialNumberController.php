<?php

namespace App\Http\Controllers;

use App\Models\SerialNumber;
use Illuminate\Http\Request;
use App\Http\Requests\SerialNumberRequest;

class SerialNumberController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(SerialNumberRequest $request)
    { 
        $data = $request->validated();
        // dd($data);
        $equipment_id = $data['equipment_id'];
        foreach($data['serial_numbers'] as $key => $num) {
            if ($num != null) {
                 SerialNumber::query()->create([
                'equipment_id' => $equipment_id,
                'serial_number' => $num
                ]);
            }
         };

        return redirect("/equipment/$equipment_id");
    }

    public function show(SerialNumber $serialNumber)
    {
        //
    }

    public function edit(SerialNumber $serialNumber)
    {
        //
    }

    public function update(SerialNumberRequest $request, SerialNumber $serialNumber)
    {
        $update = $serialNumber->update(['serial_number' => $request->serial_number]);
        if ($update) {
            alert()->success('Serial number updated!', 'Success!');
        } else {
            alert()->error('Something went wrong!', 'Oops..');
        }
        return redirect('/equipment');
    }

    public function destroy(SerialNumber $serialNumber)
    {
        if (!($serialNumber->is_used)) {
            $serialNumber->delete();
            return redirect()->back();
        }

    }
}
