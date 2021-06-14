<?php

namespace App\Http\Controllers;

use App\Models\SerialNumber;
use Illuminate\Http\Request;
use App\Http\Requests\SerialNumberRequest;

class SerialNumberController extends Controller
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SerialNumber  $serialNumber
     * @return \Illuminate\Http\Response
     */
    public function show(SerialNumber $serialNumber)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SerialNumber  $serialNumber
     * @return \Illuminate\Http\Response
     */
    public function edit(SerialNumber $serialNumber)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SerialNumber  $serialNumber
     * @return \Illuminate\Http\Response
     */
    public function update(SerialNumberRequest $request, SerialNumber $serialNumber)
    {
        // dd($request);
        $serialNumber->update(['serial_number' => $request->serial_number]);
        // dd($request->only(['serial_number']));
        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SerialNumber  $serialNumber
     * @return \Illuminate\Http\Response
     */
    public function destroy(SerialNumber $serialNumber)
    {
        if (!($serialNumber->is_used)) {
            $serialNumber->delete();
            return redirect()->back();
        }

    }
}
