<?php

namespace App\Http\Controllers\Api;

use App\Models\Attendee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttendeeController extends Controller
{
    public function index()
    {
        return response()->json(Attendee::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:attendees,email',
        ]);

        $attendee = Attendee::create($data);
        return response()->json($attendee, 201);
    }
}

