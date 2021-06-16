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

    protected function resourceAbilityMap()
    {
        return array_merge(parent::resourceAbilityMap(), [
            'serial_numbers' => 'serial_numbers',
            'reports_index' => 'reports_index',
            'report_by_department' => 'report_by_department',
            'report_by_position' => 'report_by_position',
            'report_by_category' => 'report_by_category',
            'report_by_employee' => 'report_by_employee'
        ]);
    } 

    protected function resourceMethodsWithoutModels()
    {
        return array_merge(parent::resourceMethodsWithoutModels(), ['serial_numbers', 'reports_index', 'report_by_department', 'report_by_position', 'report_by_category', 'report_by_employee']);
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

    // nezavrsena funkcija - treba da vraca samo dostupne serijske brojeve za odredjeni komad opreme
    public function serial_numbers(Equipment $equipment) {

        return $equipment->serial_numbers;
    }

    public function reports_index() {

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
        $department_ids = [];
        if ($request->department_ids != null) {
            $department_ids = $request->department_ids;
        }  else {
            $department_ids = Department::query()->ids();
        }

        $data = [[]];

        foreach($department_ids as $department_id) {

            $department = Department::find($department_id);
            $data[] = [strtoupper($department->name)];
            $data[] = ['#', 'Equipment category', 'Equipment name', 'Serial number'];
            $users = $department->users;            
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
            }
            $data[] = [];
        }
            $title = 'DEPARTMENTS';
            return Excel::download(new EquipmentReportExport($data, $title), 'assigned_equipment_by_departments.xlsx');
    }

    public function report_by_position(Request $request) {
        $position_ids = [];
        if ($request->position_ids != null) {
            $position_ids = $request->position_ids;
        } else {
            $position_ids = Position::query()->ids();
        }

        $data = [[]];
        foreach($position_ids as $position_id) {
            $position = Position::find($position_id);
            $data[] = [strtoupper($position->name)];
            $data[] = ['#', 'Equipment category', 'Equipment name', 'Serial number'];
            $users = $position->users;

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
            }
            $data[] = [];
        }
            $title = 'POSITIONS';
            return Excel::download(new EquipmentReportExport($data, $title), 'assigned_equipment_by_positions.xlsx');

    }

    public function report_by_category(Request $request) {

        $category_ids = [];
        if ($request->category_ids != null) {
                $category_ids = $request->category_ids;
        } else {
            $category_ids = EquipmentCategory::query()->ids();
        }
            $data = [[]];
            foreach($category_ids as $category_id) {
                $category = EquipmentCategory::find($category_id);
                $data[] = [strtoupper($category->name)];
                $data[] = ['#', 'Equipment name', 'Available quantity', 'Assigned quantity', 'Remarks'];
                
                $equipment = $category->equipment;
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
    }

    public function report_by_employee(Request $request) {

        $employee_ids = [];
        if ($request->employee_ids != null) {
            $employee_ids = $request->employee_ids;
        } else {
            $employee_ids = User::query()->ids();
        }
        $data = [[]];
        foreach($employee_ids as $employee_id) {
            $user = User::find($employee_id);

            $data[] = [strtoupper($user->name)];
            $data[] = ['#', 'Equipment category', 'Equipment name', 'Serial number', 'Date assigned'];
            
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

