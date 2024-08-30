<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\PersonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PersonController extends Controller
{
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'image_person' => 'required|image'
        ]);
    
        if ($validation->fails()) {
            return response()->json([
                'status' => 'not validate',
                'message' => $validation->errors()
            ], 422);
        }
    
        $image_person = $request->file('image_person');
        $dataPerson = PersonModel::all();
    
        $response = Http::attach(
            'image_person', file_get_contents($image_person->getRealPath()), $image_person->getClientOriginalName()
        )->post('http://localhost:5000/api/v1/face_register', [
            'all_data' => $dataPerson
        ]);
    
        if ($response->status() !== 200) {
            return response()->json([
                'status' => 'error',
                'message' => 'error sending image'
            ], 500);
        } else {
            $encode = $response->json()['image_person'];
    
            $person = new PersonModel();
            $person->name = $request->input('name');
            $person->image_person = json_encode($encode);
            $person->save();
    
            return response()->json([
                'status' => 'success',
                'message' => 'success register person'
            ]);
        }
    }
    

    public function getAllData()
    {
        $person = PersonModel::all();

        return response()->json([
            'status' => 'success',
            'data' => $person
        ]);
    }
}
