<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::select('id', 'name', 'email', 'role_id')->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<a href="/user/' . $row->id . '/edit" class="edit btn btn-primary btn-sm"><i class="fa fa-edit"></i></a><a href="javascript:void(0)" data-id="' . $row->id . '" class="edit btn btn-danger btn-sm delete"><i class="fa fa-trash-o"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User();
        $action = 'users.store';
        return view('user.create', ['user' => $user, 'action' => $action, 'type' => 'post']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'unique:users', 'email'],
            'password' => [
                'required',
                'min:6',
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
            ],
            'confirm' => 'required_with:password|same:password|min:6',
            'role_id'=> 'required',

        ]);

        $data = array_merge($request->all(), ['password' => Hash::make($request->password)]);
        User::create($data);
        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $action = 'users.update';
        $action_id = $user->id;
        return view('user.create', ['user' => $user, 'action' => $action, 'type' => 'edit', 'action_id' => $action_id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => "required|string",
            'email' => 'required|email|unique:users,email,' . $user->id . ',id',
            'role_id'=> 'required',
        ]);


        if (trim($request->get('password')) == '') {
            $data = $request->except(['password']);
        } else {
            $request->validate([
                'password' => [
                    'required',
                    'min:6',
                    'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
                ],
                'confirm' => 'required_with:password|same:password|min:6'
            ]);
            $data = array_merge($request->all(), ['password' => Hash::make($request->password)]);
        }
        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($user)
    {
        try {
            User::where('id', $user)->delete();
            return true;
        } catch (Exception $e) {
            return response($e->getMessage(), 500);
        }
    }
}
