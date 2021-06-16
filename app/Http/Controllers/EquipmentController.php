<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipmentRequest;
use App\Models\Department;
use App\Models\User;
use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\Position;
use Illuminate\Http\Request;

use App\Exports\EquipmentReportExport;
use Maatwebsite\Excel\Facades\Excel;

class EquipmentController extends Controller
{
    public function __construct() {
        $this->authorizeResource(Equipment::class, 'equipment');
    }

    public function index()
    {
        $equipment = Equipment::all();
        $content_header = "Equipment list";
        $breadcrumbs = [
            [ 'name' => 'Home', 'link' => '/' ],
            [ 'name' => 'Equipment list', 'link' => '/equipment' ],
        ];
        return view('equipment.index', compact(['equipment', 'content_header', 'breadcrumbs']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = EquipmentCategory::all();
        $content_header = "Add New Equipment";
        $breadcrumbs = [
            [ 'name' => 'Home', 'link' => '/' ],
            [ 'name' => 'Equipment list', 'link' => '/equipment' ],
            [ 'name' => 'New Equipment', 'link' => '/equipment/create' ],
        ];
        return view('equipment.create', compact(['categories', 'content_header', 'breadcrumbs']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EquipmentRequest $request)
    {
        // dd($request);
        Equipment::query()->create($request->validated());
        return redirect(route('equipment.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function show(Equipment $equipment)
    {        
        // dd($equipment->required_input_fields);
        $content_header = "Equipment details";
        $breadcrumbs = [
            [ 'name' => 'Home', 'link' => '/' ],
            [ 'name' => 'Equipment list', 'link' => '/equipment' ],
            [ 'name' => 'Equipment details', 'link' => '/equipment/'.$equipment->id ],
        ];
        return view('equipment.show', compact(['content_header', 'breadcrumbs', 'equipment']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function edit(Equipment $equipment)
    {
        $categories = EquipmentCategory::all();
        $content_header = "Edit Equipment details";
        $breadcrumbs = [
            [ 'name' => 'Home', 'link' => '/' ],
            [ 'name' => 'Equipment list', 'link' => '/equipment' ],
            [ 'name' => 'Edit Equipment details', 'link' => '/equipment/'.$equipment->id.'/edit' ],
        ];
        return view('equipment.edit', compact(['categories', 'content_header', 'breadcrumbs', 'equipment']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function update(EquipmentRequest $request, Equipment $equipment)
    {
        $equipment->update($request->validated());
        return redirect('/equipment');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Equipment $equipment)
    {
        $equipment->delete();
        return redirect('/equipment');
    }

    public function serial_numbers(Equipment $equipment) {
        $this->authorize('serial_numbers');

        return $equipment->serial_numbers;
    }

    public function reports_index() {
        $this->authorize('reports_index');

        $categories = EquipmentCategory::all();
        $departments = Department::all();
        $positions = Position::all();
        $users = User::all();
        $content_header = "Reports";
        $breadcrumbs = [
            [ 'name' => 'Home', 'link' => '/' ],
            [ 'name' => 'Reports', 'link' => '/reports' ]
        ];
        return view('equipment.reports', compact(['content_header', 'breadcrumbs', 'categories', 'departments', 'positions', 'users']));
    }

    public function report_by_department(Request $request) {
        $this->authorize('report_by_department');

        if ($request->department_ids != null) {
                $data = [[]];
                foreach($request->department_ids as $department_id) {

                    $department = Department::find($department_id);
                    $data[] = [strtoupper($department->name)];
                    $data[] = ['#', 'Equipment category', 'Equipment name', 'Serial number'];
                    $users = $department->users;
                    if (count($users) <= 0) $data[] = ['/', '/', '/', '/'];
                    
                    $counter = 0;
                    foreach($users as $user)  {
    
                        $items = $user->items;
    
                        foreach($items as $i) {
                            $counter += 1;
                            $cat = $i->equipment->category->name;
                            $name =  $i->equipment->name;
                            $i->serial_number ? $sn = $i->serial_number->serial_number : $sn = '/';
                            $data[] = [$counter, $cat, $name, $sn];
                        }
                        $data[] = [];
                    }
                    $title = 'DEPARTMENTS';
                    return Excel::download(new EquipmentReportExport($data, $title), 'assigned_equipment_by_departments.xlsx');
                }
            } else {
                return redirect()->back();
            }

    }

    public function report_by_position(Request $request) {
        $this->authorize('report_by_position');

        if ($request->position_ids != null) {
            $data = [[]];
            foreach($request->position_ids as $position_id) {
                $position = Position::find($position_id);
                $data[] = [strtoupper($position->name)];
                $data[] = ['#', 'Equipment category', 'Equipment name', 'Serial number'];
                $users = $position->users;
                if (count($users) <= 0) $data[] = ['/', '/', '/', '/'];

                $counter = 0;
                foreach($users as $user)  {

                    $items = $user->items;

                    foreach($items as $i) {
                        $counter += 1;
                        $cat = $i->equipment->category->name;
                        $name =  $i->equipment->name;
                        $i->serial_number ? $sn = $i->serial_number->serial_number : $sn = '/';
                        $data[] = [$counter, $cat, $name, $sn];
                    }
                    $data[] = [];
                }
                $title = 'POSITIONS';
                return Excel::download(new EquipmentReportExport($data, $title), 'assigned_equipment_by_positions.xlsx');
            }
        } else {
            return redirect()->back();
        }
    }

    public function report_by_category(Request $request) {
        $this->authorize('report_by_category');

        if ($request->category_ids != null) {
            $data = [[]];
            foreach($request->category_ids as $category_id) {
                $category = EquipmentCategory::find($category_id);
                $data[] = [strtoupper($category->name)];
                $data[] = ['#', 'Equipment name', 'Available quantity', 'Assigned quantity', 'Remarks'];
                
                $equipment = $category->equipment;
                if (count($equipment) <= 0) $data[] = ['/', '/', '/', '/', '/'];

                $counter = 0;
                foreach($equipment as $item) {
                    $counter += 1;
                    $name =  $item->name;
                    $available_quantity = $item->available_quantity; 
                    $assigned_quantity = $item->assigned;
                    $item->description ? $remarks = $item->description : $remarks = '/';
                    $data[] = [$counter, $name, $available_quantity, $assigned_quantity, $remarks];
                }
                $data[] = [];
            }
            $title = 'CATEGORIES';
            return Excel::download(new EquipmentReportExport($data, $title), 'equipment_by_categories.xlsx');
        } else {
            return redirect()->back();
        }
    }

    public function report_by_employee(Request $request) {
        $this->authorize('report_by_employee');

        if ($request->employee_ids != null) {
            $data = [[]];
            foreach($request->employee_ids as $employee_id) {
                $user = User::find($employee_id);

                $data[] = [strtoupper($user->name)];
                $data[] = ['#', 'Equipment category', 'Equipment name', 'Serial number', 'Date assigned'];
                
                if (count($user->current_items) <= 0) $data[] = ['/', '/', '/', '/', '/'];

                $counter = 0;
                // document items in which this user is referenced
                foreach($user->current_items as $item) {
                    $counter += 1;
                    $cat = $item->equipment->category->name;
                    $name =  $item->equipment->name;
                    ($item->created_at != null) ? $date = $item->assignment_date : $date = '/';
                    $item->serial_number ? $sn = $item->serial_number->serial_number : $sn = '/';
                    $data[] = [$counter, $cat, $name, $sn, $date];
                }
                $data[] = [];
            }
            $title = 'EMPLOYEES';
            return Excel::download(new EquipmentReportExport($data, $title), 'equipment_by_employees.xlsx');
        } else {
            return redirect()->back();
        }
    }
        
}
//     $d = DocumentItem::query()->with('serial_number')->get();

//     $key = [];
//     $val = [];
//     foreach ($d as $a) {
//         $key[] = $a->serial_number->id;
//         $val[] = $a->serial_number->serial_number;
//     }

//     dd($key, $val);

//     for($i = 0; $i<count($key); $i++) {
//         $arr = 
//     }

//     // dd($equipment->serial_numbers);
//     $arr1 = [];
// foreach($equipment->serial_numbers as $sn) {
//     $arr1[] = [$sn->id => $sn->serial_number];
// }

// // dd($arr, $arr1);

// dd(array_diff_assoc($arr1, $arr)); //dobijemo id od available serijskih brojeva ali nemamo njihovu vrijednost 

        // $assigned_nums = $equipment->serial_numbers;

        // dd($assigned_nums);
        // return($assigned_nums);

