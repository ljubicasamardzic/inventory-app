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
use App\Notifications\NewEquipmentNotification;
use App\Notifications\RestockedNotification;
use Illuminate\Support\Facades\Notification;
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

        // foreach($equipment as $item) {
        //     $serial_nums = $item->serial_numbers;
        //     // dd($serial_nums);
        // $filtered = $serial_nums->filter(function($value, $key) {
        //     return !$value->is_used;
        // });
        //     DD($filtered);
        // }
        $content_header = "Equipment list";
        $breadcrumbs = [
            [ 'name' => 'Home', 'link' => '/' ],
            [ 'name' => 'Equipment list', 'link' => '/equipment' ],
        ];
        return view('equipment.index', compact(['equipment', 'content_header', 'breadcrumbs']));
    }

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

    public function store(EquipmentRequest $request)
    {
        $new_equipment = Equipment::query()->create($request->validated());
        if ($new_equipment) {
            $recipients = User::query()->isSupportOrSuperadmin();
            Notification::send($recipients, new NewEquipmentNotification($new_equipment));
            alert()->success('New equipment added!', 'Success!');
        } else {
            alert()->error('Something went wrong!', 'Oops..');
        }

        return redirect(route('equipment.index'));
    }

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

    public function update(EquipmentRequest $request, Equipment $equipment)
    {
        $current_available_quantity = $equipment->available_quantity;
        
        $update = $equipment->update($request->validated());

        if ($update) {
            if ($request->available_quantity > $current_available_quantity) {
                $recipients = User::query()->isSupportOrSuperadmin();
                Notification::send($recipients, new RestockedNotification($equipment));
            }
            alert()->success('Equipment successfully updated!', 'Success!');
        } else {
            alert()->error('Something went wrong!', 'Oops..');
        }

        return redirect("/equipment/$equipment->id");
    }

    public function destroy(Equipment $equipment)
    {
        $equipment->delete();
        return redirect('/equipment');
    }

    public function serial_numbers(Equipment $equipment) {
        $serial_nums = $equipment->serial_numbers;
        
        $result = [];
        foreach($serial_nums as $sn) {
            if (!$sn->is_used) {
                $result[] = $sn;
            }
        }
        return response()->json($result);
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
        $department_ids = Department::searchIds($request);

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
            $title = 'ASSIGNED EQUIPMENT BY DEPARTMENTS';
            return Excel::download(new EquipmentReportExport($data, $title), 'assigned_equipment_by_departments.xlsx');
    }

    public function report_by_position(Request $request) {
        $position_ids = Position::searchIds($request);
    
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
            $title = 'ASSIGNED EQUIPMENT BY POSITIONS';
            return Excel::download(new EquipmentReportExport($data, $title), 'assigned_equipment_by_positions.xlsx');

    }

    public function report_by_category(Request $request) {

        $category_ids = EquipmentCategory::searchIds($request);
        
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
            $title = 'ASSIGNED EQUIPMENT BY CATEGORIES';
            return Excel::download(new EquipmentReportExport($data, $title), 'equipment_by_categories.xlsx');
    }

    public function report_by_employee(Request $request) {

        $employee_ids = User::searchIds($request);

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
        $title = 'ASSIGNED EQUIPMENT BY EMPLOYEES';
        return Excel::download(new EquipmentReportExport($data, $title), 'equipment_by_employees.xlsx');
    }

    public function report_available_equipment(Request $request) {

        $equipment_ids = Equipment::searchIds($request);
        
        $data = [[]];

        $counter = 0;
        $data[] = ['#', 'Equipment name', 'Available quantity'];
        foreach($equipment_ids as $equipment_id) {
            $item = Equipment::find($equipment_id);

            $counter += 1;
            $data[] = [$counter, $item->name, $item->available_quantity];
        }
        $title = 'AVAILABLE QUANTITIES FOR ALL/SELECT EQUIPMENT';
        return Excel::download(new EquipmentReportExport($data, $title), 'available_equipment.xlsx');

    }
        
}
