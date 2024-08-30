<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\AbsenModel;
use App\Models\PersonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AbsenController extends Controller
{
    public function absensi(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'image_person' => 'required'
        ]);

        if($validation->fails()) {
            return response()->json([
                'status' => 'not validate',
                'message' => $validation->errors()
            ], 422);
        }

        $image_person = $request->file('image_person');

        $allDataPerson = PersonModel::all();

        $matchFace = Http::attach(
            'image_person', file_get_contents($image_person->getRealPath()), $image_person->getClientOriginalName()
        )->post('http://localhost:5000/api/v1/face_recognition', [
            'all_data' => $allDataPerson
        ]);

        if ($matchFace->status() == 404){   
            return response()->json([
                'status' => 'failed',
                'message' => 'wajah tidak ditemukan'
            ], 404);
        }else if($matchFace->status() == 500){
            return response()->json([
                'status' => 'failed',
                'message' => 'error encode image'
            ], 500);
        }else if ($matchFace->status() == 400){
            return response()->json([
                'status' => 'failed',
                'message' => 'wajah tidak cocok'
            ], 400);
        }else{
            $absen = new AbsenModel();
            $absen->id_person = $matchFace->json()['id_person'];
            $absen->status = 'hadir';

            $absen->save();

            return response()->json([
                'status' => 'success',
                'message' => 'success absen',
                'name' => $absen->person->name
            ]);
        }
    }

}
