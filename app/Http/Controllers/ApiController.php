<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LogAttendance;
use Illuminate\Http\Request;


class ApiController extends Controller
{
    public function getUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'gagal menambahkan user'], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'penambahan user berhasil',
            'data' => $user
        ], 201);

        //return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

public function nyetor(Request $request)
{
    $validated = $request->validate([
        'machine_id' => 'required|integer',
        'employee_no' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'card_no'=> 'required|string|max:255',
        'event_type'=> 'required|string|max:255',
        'attendance_status'=> 'required|string|max:255',
        'is_synced'=> 'required|boolean',
        'created_at'=> 'required|date',
        'updated_at'=> 'required|date'
    ]);

   $item = logAttendance::create($validated);

    return response()->json([
        'success' => true,
        'message' => 'Item berhasil ditambahkan',
        'data' => $item
    ], 201);
}

}


