<?php

namespace App\Http\Controllers;

use app\Models\user;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = user::latest()->paginate(5);
        return view('users.index',compact('data'))->with('i', (request()->input('page', 1) - 1) * 5);
    } 


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return redirect()->route('register');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(user $user)
    {
        return view('users.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(user $user)
    {
        return view('users.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, user $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255','unique:users'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=Hash::make($request->password);
        $user->save();

        return redirect()->route('users.index')->with('success','Пользователь успешно изменен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(user $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success','Пользователь удален');
    }
}
