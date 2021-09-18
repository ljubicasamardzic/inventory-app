<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipmentRequest;
use App\Models\Department;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Exports\EquipmentReportExport;
use App\Models\DocumentItem;
use App\Models\Ticket;
use App\Notifications\NewEquipmentNotification;
use App\Notifications\RestockedNotification;
use Database\Seeders\DepartmentSeeder;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;


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
        $equipment = Equipment::paginate(Equipment::PER_PAGE);
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

        return redirect("/equipment");
    }

    public function destroy(Equipment $equipment)
    {
        DB::beginTransaction();
        foreach($equipment->serial_numbers as $sn) {
            if ($sn->ticket) {
                if(!$sn->ticket->delete()) {
                    DB::rollBack();
                    alert()->error('Something went wrong!', 'Oops..');
                }
            }
            if (!$sn->delete()) {
                DB::rollBack();
                alert()->error('Something went wrong!', 'Oops..');
            }
        }

        foreach($equipment->document_items as $item) {
            if ($item->ticket) {
                if (!$item->ticket->delete()) {
                    DB::rollBack();
                    alert()->error('Something went wrong!', 'Oops..');
                }
            }

            if (!$item->delete()) {
                DB::rollBack();
                alert()->error('Something went wrong!', 'Oops..');
            }
        }

        if ($equipment->tickets) {
            foreach($equipment->tickets as $ticket) {
                if (!$ticket->delete()) {
                    DB::rollBack();
                    alert()->error('Something went wrong!', 'Oops..');
                }
                
            }
        }

        if ($equipment->delete()) {
            DB::commit();
            alert()->success('Equipment, serial numbers, any tickets and related document items successfully deleted!', 'Success!');
        } else {
            DB::rollBack();
            alert()->error('Something went wrong!', 'Oops..');
        }
    
        return redirect('/equipment');
    }

    public function serial_numbers($id) {
        $equipment = Equipment::find($id);
        
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
        $this->authorize('reports_index', Equipment::class);

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
        $this->authorize('report_by_department', Equipment::class);

        $data = [[]];

        // used left joins so that we list out even those positions that have no equipment associated with them
        $results = Department::report_results($request);

        $results_grouped = $results->groupBy('department_name');
        
        foreach($results_grouped as $key => $value) {
            $data[] = [strtoupper($key)];
            $data[] = ['#', 'Equipment category', 'Equipment name', 'Serial number'];
            foreach($value as $key => $info) {
                if ($info->eq_cat_name != null && $info->equip_name != null) {
                    $info->sn ? $sn = $info->sn : $sn = '/';
                    $data[] = [$key + 1, $info->eq_cat_name, $info->equip_name, $sn];
                }
            }
            $data[] = [];
        }

        $title = 'ASSIGNED EQUIPMENT BY DEPARTMENTS';
        return Excel::download(new EquipmentReportExport($data, $title), 'assigned_equipment_by_departments.xlsx');
    }

    public function report_by_position(Request $request) {
        $this->authorize('report_by_position', Equipment::class);

        $data = [[]];

        // used left joins so that we list out even those positions that have no equipment associated with them
        $results = Position::report_results($request);

        // dd($results);
        $results_grouped = $results->groupBy('position_name');

        foreach($results_grouped as $key => $value) {
            $data[] = [strtoupper($key)];
            $data[] = ['#', 'Equipment category', 'Equipment name', 'Serial number'];
            foreach($value as $key => $info) {
                if ($info->eq_cat_name != null && $info->equip_name != null) {
                    $info->sn ? $sn = $info->sn : $sn = '/';
                    $data[] = [$key + 1, $info->eq_cat_name, $info->equip_name, $sn];
                }
            }
            $data[] = [];
        }

        $title = 'ASSIGNED EQUIPMENT BY POSITIONS';
        return Excel::download(new EquipmentReportExport($data, $title), 'assigned_equipment_by_positions.xlsx');

    }

    public function report_by_category(Request $request) {
        $this->authorize('report_by_category', Equipment::class);

        $category_ids = EquipmentCategory::searchIds($request);

        // implode array to string
        $ids = (implode(',', $category_ids));
        $data = [[]];

        $results = DB::select(
            "select 
            DISTINCT results.*,
            (results.complete_quantity - results.reserved) as available_quantity
            from (
            select 
            ec.name as equip_cat_name, 
            e.id as equip_id,
            e.name as equip_name, 
            e.description as remarks,
            e.available_quantity as complete_quantity,
            
            (SELECT COUNT('r.id')
            FROM reservations as r 
            JOIN tickets as t on t.id = r.ticket_id
            left join equipment as e on e.id = t.equipment_id 
            WHERE e.id = equip_id) as reserved,

            (SELECT COUNT('di.id')
            FROM document_items as di
            WHERE di.equipment_id = equip_id
            ) as assigned

            FROM equipment_categories as ec
            LEFT JOIN equipment as e on e.equipment_category_id = ec.id
            LEFT JOIN document_items as di on di.equipment_id = e.id
            LEFT JOIN tickets as t on t.equipment_id = e.id
            LEFT JOIN reservations as r on r.ticket_id = t.id
            WHERE (di.return_date IS NULL)
            AND (ec.id IN ($ids))
            AND (available_quantity IS NOT NULL)
            ) as results
            ORDER BY results.equip_cat_name"
        );

        $category = '';
        foreach($results as $key => $val) {
            if ($category == '') {
                $category = $val->equip_cat_name;

                $data[] = [strtoupper($val->equip_cat_name)];
                $data[] = ['#', 'Equipment name', 'Available quantity', 'Reserved quantity', 'Assigned quantity', 'Remarks'];
                $data[] = [1, $val->equip_name, $val->available_quantity, $val->reserved, $val->assigned, $val->remarks];
            } else if ($category != '') {
                if ($category == $val->equip_cat_name) {
                    count($data);
                    $data[] = [$data[count($data) - 1][0] + 1, $val->equip_name, $val->available_quantity, $val->reserved, $val->assigned, $val->remarks];
                } else {
                    $category = $val->equip_cat_name;
                    $data[] = [];
                    $data[] = [strtoupper($val->equip_cat_name)];
                    $data[] = ['#', 'Equipment name', 'Available quantity', 'Reserved quantity', 'Assigned quantity', 'Remarks'];
                    $data[] = [1, $val->equip_name, $val->available_quantity, $val->reserved, $val->assigned, $val->remarks];
                }
            }
        }
           
        $title = 'ASSIGNED EQUIPMENT BY CATEGORIES';
        return Excel::download(new EquipmentReportExport($data, $title), 'equipment_by_categories.xlsx');
    }

    public function report_by_employee(Request $request) {
        $this->authorize('report_by_employee', Equipment::class);

        $results = User::query()
                                ->leftJoin('documents', 'documents.user_id', '=', 'users.id')
                                ->leftJoin('document_items', 'document_items.document_id', '=', 'documents.id')
                                ->leftJoin('equipment', 'equipment.id', '=', 'document_items.equipment_id')
                                ->leftJoin('equipment_categories', 'equipment_categories.id', '=', 'equipment.equipment_category_id')
                                ->leftJoin('serial_numbers', 'serial_numbers.id', '=', 'document_items.serial_number_id')
                                ->selectRaw("users.name as name, 
                                            equipment_categories.name as eq_cat_name, 
                                            equipment.name as equip_name, 
                                            serial_numbers.serial_number as sn,
                                            SPLIT_STRING(document_items.created_at, ' ', 1) as created_at")
                                ->where('document_items.return_date', '=', null)
                                ->where('equipment_categories.name', '<>', null)
                                ->where('equipment.name', '<>', null)
                                ->when($request->employee_ids != null, function($query) use ($request) {
                                    $query->whereIn('users.id', $request->employee_ids);
                                })
                                ->get();
                                dd($results);
                                // dd($results[0]['created_at'], $results[0]->name);

                                // foreach($results as $result) {
                                //     dd($result->created_at);
                                //     // $result->created_at->format('d.m.Y');
                                // }
                                // $results->date->format('d.m.Y');

        // $employee_ids = User::searchIds($request);

        // $data = [[]];
        // foreach($employee_ids as $employee_id) {
        //     $user = User::find($employee_id);

        //     $data[] = [strtoupper($user->name)];
        //     $data[] = ['#', 'Equipment category', 'Equipment name', 'Serial number', 'Date assigned'];
            
        //     $counter = 0;
        //     // document items in which this user is referenced
        //     foreach($user->current_items as $item) {
        //         $counter += 1;
        //         $cat = $item->equipment->category->name;
        //         $name =  $item->equipment->name;
        //         ($item->created_at != null) ? $date = $item->assignment_date : $date = '/';
        //         $item->serial_number ? $sn = $item->serial_number->serial_number : $sn = '/';
        //         $data[] = [$counter, $cat, $name, $sn, $date];
        //     }
        //     $data[] = [];
        // }
        $title = 'ASSIGNED EQUIPMENT BY EMPLOYEES';
        return Excel::download(new EquipmentReportExport($data, $title), 'equipment_by_employees.xlsx');
    }

    public function report_available_equipment(Request $request) {
        $this->authorize('report_available_equipment', Equipment::class);

        $equipment_ids = Equipment::searchIds($request);
        
        $data = [[]];

        $counter = 0;
        $data[] = ['#', 'Equipment category', 'Equipment name', 'Available quantity'];
        foreach($equipment_ids as $equipment_id) {
            $item = Equipment::find($equipment_id);
            $category = $item->category->name;
            $counter += 1;
            $data[] = [$counter, $category, $item->name, $item->available_quantity];
        }
        $title = 'AVAILABLE QUANTITIES FOR ALL/SELECT EQUIPMENT';
        return Excel::download(new EquipmentReportExport($data, $title), 'available_equipment.xlsx');
    }
        
}
