<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
  public function dashboardApi(Request $request)
  {
    $lokasi_id = $request->query('lokasi_id', 1);

    $lokasi = DB::table('lokasi')->where('id', $lokasi_id)->first();
    if (!$lokasi) {
      return response()->json([
        'status' => 'fail',
        'message' => 'Lokasi tidak ditemukan'
      ], 404);
    }

    $node = DB::table('node')->where('lokasi_id', $lokasi_id)->latest('tanggal')->first();
    return response()->json($node);
  }

  public function locationApi()
  {
    $location = DB::table('lokasi')->get();

    $location = $location->map(function ($item) {
      $latitude = str_replace(",", "", explode(" ", $item->kordinat)[0]);
      $longtitude =  explode(" ", $item->kordinat)[1];

      $item->kordinat = [
        'latitude' => $latitude,
        'longtitude' => $longtitude
      ];

      return $item;
    });

    return response()->json($location);
  }

  public function historyApi(Request $request)
  {
    $date = $request->query('date', Carbon::now()->toDateString());
    $lokasi_id = $request->query('lokasi_id', 1);

    $lokasi = DB::table('lokasi')->where('id', $lokasi_id)->first();
    if (!$lokasi) {
      return response()->json([
        'status' => 'fail',
        'message' => 'Lokasi tidak ditemukan'
      ], 404);
    }

    $node = DB::table('node')->where('lokasi_id', $lokasi_id)->whereDate('tanggal', '=', $date)->latest('tanggal')->first();

    return response()->json($node);
  }

  public function statsApi(Request $request)
  {
    $lokasi_id = $request->query('lokasi_id');

    $node_first = DB::table('node');
    $node_last = DB::table('node');

    if ($lokasi_id) {
      $lokasi = DB::table('lokasi')->where('id', $lokasi_id)->first();
      if (!$lokasi) {
        return response()->json([
          'status' => 'fail',
          'message' => 'Lokasi tidak ditemukan'
        ], 404);
      }

      $node_first = $node_first->where('lokasi_id', $lokasi_id);
      $node_last = $node_last->where('lokasi_id', $lokasi_id);
    }

    $node_first = $node_first->first('tanggal');
    $node_last = $node_last->latest('tanggal')->first();

    return response()->json([
      'first_date' => Carbon::parse($node_first->tanggal)->format('Y-m-d'),
      'last_date' => Carbon::parse($node_last->tanggal)->format('Y-m-d')
    ]);
  }
}
