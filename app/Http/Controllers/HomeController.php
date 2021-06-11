<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Equipment;
use App\Models\User;
use App\Models\EquipmentCategory;
use App\Models\Ticket;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = User::query()->find(auth()->id());
        $equipment = $user->items;
        $equipment_categories = EquipmentCategory::all();

        $categories = EquipmentCategory::query()
                                        ->whereHas('available_equipment')
                                        ->with('available_equipment')
                                        ->get();

        if ($user->isSuperAdmin()) {
            $tickets = Ticket::query()->open();
        } else if ($user->isSupportOfficer()) {
            $tickets = Ticket::query()->equipmentRequests();
        } else if ($user->isAdministrativeOfficer()) {
            $tickets = Ticket::query()->suppliesRequests();
        } else if ($user->isHR()) {
            $tickets = Ticket::query()->readyForHR();
        } else {
            $tickets = [];
        }

        return view('home', compact(['categories', 'equipment', 'equipment_categories', 'tickets']));
    }
}
