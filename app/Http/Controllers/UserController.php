<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Department;
use App\Models\DocumentItem;
use App\Models\Position;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
  
    public function __construct() {
        $this->authorizeResource(User::class, 'user');
    }

    public function index()
    {

        $users = User::paginate(User::PER_PAGE);
        $content_header = "Employees list";
        $breadcrumbs = [
            [ 'name' => 'Home', 'link' => '/' ],
            [ 'name' => 'Employees list', 'link' => '/users' ],
        ];
        return view('users.index', compact(['users', 'content_header', 'breadcrumbs']));
    }

    public function create()
    {
        $roles = Role::all();
        $departments = Department::all();
        $content_header = "Add New Employee";
        $breadcrumbs = [
            [ 'name' => 'Home', 'link' => '/' ],
            [ 'name' => 'Employees list', 'link' => '/users' ],
            [ 'name' => 'New Employee', 'link' => '/users/create' ],
        ];
        return view('users.create', compact(['departments', 'content_header', 'breadcrumbs', 'roles']));
    }

    public function store(UserRequest $request)
    {
        // dd($request, $request->validated());
        $new_user = User::query()->create($request->validated());
        if ($new_user) {
            alert()->success('New user added!', 'Success!');
        } else {
            alert()->error('Something went wrong!', 'Oops..');
        }
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
        // displaying only the currently assigned items 
        $items = $user->current_items;
        return view('users.show', compact(['content_header', 'breadcrumbs', 'user', 'items']));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $departments = Department::all();
        $content_header = "Edit Employee details";
        $user->append('department_id');
        $breadcrumbs = [
            [ 'name' => 'Home', 'link' => '/' ],
            [ 'name' => 'Employees list', 'link' => '/users' ],
            [ 'name' => 'Edit Employee details', 'link' => '/users/'.$user->id.'/edit' ],
        ];
        return view('users.edit', compact(['departments', 'content_header', 'breadcrumbs', 'user', 'roles']));
    }

    public function update(UserRequest $request, User $user)
    {
        $update = $user->update($request->except(['password']));
        if ($update) {
            alert()->success('User data updated!', 'Success!');
        } else {
            alert()->error('Something went wrong!', 'Oops..');
        }
        return redirect('/users');
    }

    public function destroy(User $user)
    {
        if($user->id != auth()->id()) {
            if ($user->documents->count() > 0 || $user->tickets->count() > 0) {
                alert()->error('You must first delete documents and/or tickets related to this user!', 'Oops..');
            } else if ($user->delete()) {
                alert()->success('User deleted!', 'Success!');
            }
        }
        return redirect('/users');
    }

}
