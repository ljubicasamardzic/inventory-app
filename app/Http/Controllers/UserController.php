<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
  
    public function __construct() {
        $this->authorizeResource(User::class, 'user');
    }

    public function index()
    {

        $users = User::all();
        $content_header = "Employees list";
        $breadcrumbs = [
            [ 'name' => 'Home', 'link' => '/' ],
            [ 'name' => 'Employees list', 'link' => '/users' ],
        ];
        return view('users.index', compact(['users', 'content_header', 'breadcrumbs']));
    }

    public function create()
    {
        $departments = Department::all();
        $content_header = "Add New Employee";
        $breadcrumbs = [
            [ 'name' => 'Home', 'link' => '/' ],
            [ 'name' => 'Employees list', 'link' => '/users' ],
            [ 'name' => 'New Employee', 'link' => '/users/create' ],
        ];
        return view('users.create', compact(['departments', 'content_header', 'breadcrumbs']));
    }

    public function store(UserRequest $request)
    {
        User::query()->create($request->validated());
        return redirect(route('users.index'));
    }

    public function show(User $user)
    {

        $content_header = "Employee details";
        $breadcrumbs = [
            [ 'name' => 'Home', 'link' => '/' ],
            [ 'name' => 'Employees list', 'link' => '/users' ],
            [ 'name' => 'Employee details', 'link' => '/users/'.$user->id ],
        ];
        $items = $user->items;
        return view('users.show', compact(['content_header', 'breadcrumbs', 'user', 'items']));
    }

    public function edit(User $user)
    {
        $departments = Department::all();
        $content_header = "Edit Employee details";
        $user->append('department_id');
        $breadcrumbs = [
            [ 'name' => 'Home', 'link' => '/' ],
            [ 'name' => 'Employees list', 'link' => '/users' ],
            [ 'name' => 'Edit Employee details', 'link' => '/users/'.$user->id.'/edit' ],
        ];
        return view('users.edit', compact(['departments', 'content_header', 'breadcrumbs', 'user']));
    }

    public function update(UserRequest $request, User $user)
    {
        $user->update($request->except(['password']));
        return redirect('/users');
    }

    public function destroy(User $user)
    {
        if($user->id != auth()->id()){
            $user->delete();
        }
        return redirect('/users');
    }
}
