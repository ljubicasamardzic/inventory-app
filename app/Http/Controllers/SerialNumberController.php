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
        // called by ajax
        
        // I complicated this a bit, since I wanted to allow the user to enter any number of serial numbers at the time
        // meaning you don't have to enter all of them at once, which creates the bug of being able to submit empty input fields 
        // in order not to change it now, I left it with an error message in that case
        $data = $request->validated();
        $equipment_id = $data['equipment_id'];
        if ($data['serial_numbers'][0] != '') {
            foreach($data['serial_numbers'] as $key => $num) {
                if ($num != null) {
                    SerialNumber::query()->create([
                    'equipment_id' => $equipment_id,
                    'serial_number' => $num
                    ]);
                }
            };
            alert()->success('Serial numbers added!', 'Success!');
        } else {
            alert()->error('Please enter the data!', 'Oops..');
        }
        // return redirect("/equipment/$equipment_id");
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
        return redirect()->back();
    }

    public function destroy(SerialNumber $serialNumber)
    {
        if (!($serialNumber->is_used)) {
            if ($serialNumber->delete()) {
                alert()->success('Serial number deleted!', 'Success!');
            } else {
                alert()->error('Something went wrong!', 'Oops..');
            }
        } else {
            alert()->error('You cannot delete the serial number of an equipment item that has already been assigned!', 'Oops..');
        }
        return redirect()->back();
    }
}
