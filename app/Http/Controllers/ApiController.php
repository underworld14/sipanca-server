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

    if ($lokasi_id > 2) {
      return response()->json([
        'status' => 'fail',
        'message' => 'Lokasi tidak ditemukan'
      ], 404);
    }

    $node = DB::table('node')->where('lokasi_id', $lokasi_id)->latest('id')->first();
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
    $node = DB::table('node')->whereDate('tanggal', '=', $date)->latest('id')->first();

    return response()->json($node);
  }

  public function statsApi()
  {
    $node_last = DB::table('node')->latest('tanggal')->first();
    $node_first = DB::table('node')->first();

    return response()->json([
      'first_date' => Carbon::parse($node_first->tanggal)->format('Y-m-d'),
      'last_date' => Carbon::parse($node_last->tanggal)->format('Y-m-d')
    ]);
  }
}
