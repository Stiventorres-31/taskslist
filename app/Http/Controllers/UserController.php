<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('id', 'DESC')->get();


        return response()->json($users, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|max:500|email',
        ]);
        $user = User::where('email', $validated['email'])->first();

        // Si el usuario se encuentra, devolver un error
        if ($user) {
            return response()->json(['message' => 'existing user.'], 400);
        }

        // Crear y guardar el usuario
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],

        ]);

        return response()->json(['message' => 'User created successfully.', 'result' => $user], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $validated = $request->validate([
            'name' => 'required|max:255|min:4',
            'email' => 'required|email|max:500|min:10'
        ]);

        $user = User::find($id);
        if(!$user){
            return response()->json(['message' => 'User not found.'], 404);
        }
        $user->update($validated);
        return response()->json(['message'=>'User updated successfully.','result'=>$user], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(["message" => "User not found"], 404);
        }

        $user->delete();
        return response()->json(["message" => "User deleted successfully."]);
    }
}
