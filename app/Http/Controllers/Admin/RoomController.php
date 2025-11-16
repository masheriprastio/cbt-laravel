<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:admin']);
    }

    public function index()
    {
        $rooms = Room::withCount('users')->orderBy('id', 'asc')->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        $students = User::where('role', 'siswa')->orderBy('name')->get();
        return view('admin.rooms.create', compact('students'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'students' => 'nullable|array',
            'students.*' => 'exists:users,id',
        ]);

        $room = Room::create(['name' => $data['name']]);

        if (!empty($data['students'])) {
            $room->users()->sync($data['students']);
        }

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil dibuat.');
    }

    public function show(Room $room)
    {
        // Not typically used for admin resource management, can be ignored
    }

    public function edit(Room $room)
    {
        $students = User::where('role', 'siswa')->orderBy('name')->get();
        $roomStudentIds = $room->users()->pluck('users.id')->toArray();
        return view('admin.rooms.edit', compact('room', 'students', 'roomStudentIds'));
    }

    public function update(Request $request, Room $room)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'students' => 'nullable|array',
            'students.*' => 'exists:users,id',
        ]);

        $room->update(['name' => $data['name']]);

        // Sync students, pass empty array if 'students' is not present
        $room->users()->sync($data['students'] ?? []);

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy(Room $room)
    {
        // Detach all users before deleting the room
        $room->users()->detach();

        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil dihapus.');
    }

    public function participants(Room $room)
    {
        $participants = $room->users()->select('name')->get();
        return response()->json($participants);
    }
}
