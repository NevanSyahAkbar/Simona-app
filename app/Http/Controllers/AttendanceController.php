<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    /**
     * แสดงรายการ semua data absensi.
     * Mendukung paginasi.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Ambil data dengan paginasi, 15 item per halaman
        $attendances = Attendance::latest()->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Daftar data absensi berhasil diambil.',
            'data' => $attendances
        ], 200);
    }

    /**
     * Menyimpan data absensi baru ke database.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id'   => 'required|string|max:100',
            'present_date'  => 'required|date',
            'time_in'       => 'required|date_format:H:i:s',
            'time_out'      => 'nullable|date_format:H:i:s',
            'machine_id'    => 'nullable|string|max:100',
            'shift_id'      => 'nullable|string|max:100',
            'spk'           => 'nullable|string|max:100',
            'sync'          => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Buat data absensi baru
        $attendance = Attendance::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data absensi berhasil ditambahkan.',
            'data' => $attendance
        ], 201);
    }

    /**
     * Menampilkan satu data absensi spesifik berdasarkan ID.
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Attendance $attendance)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail data absensi.',
            'data' => $attendance
        ], 200);
    }

    /**
     * Memperbarui data absensi yang ada di database.
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Attendance $attendance)
    {
        $validator = Validator::make($request->all(), [
            'employee_id'   => 'sometimes|required|string|max:100',
            'present_date'  => 'sometimes|required|date',
            'time_in'       => 'sometimes|required|date_format:H:i:s',
            'time_out'      => 'nullable|date_format:H:i:s',
            // Aturan lainnya bisa ditambahkan sesuai kebutuhan update
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update data absensi
        $attendance->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data absensi berhasil diperbarui.',
            'data' => $attendance
        ], 200);
    }

    /**
     * Menghapus data absensi dari database.
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data absensi berhasil dihapus.',
        ], 200); // Atau bisa juga mengembalikan status 204 No Content
    }
}
