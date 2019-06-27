<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Employee;

class EmployeeController extends Controller {

//get list data employee(GET)
public function index() {
    try {

        $key = 'data-employee';
        $checkCache = getCache($key);

        if ($checkCache != null) {
            return responses($checkCache, null);
        }
            $data = Employee::select([
                'id',
                'nik',
                'nama',
                'alamat',
                'umur',
                'tgl_lahir',
                'tgl_gabung',
                'status'
            ])
            ->where('deleted_at', null)
            ->get();
            // $data = json_decode($data);
            // print_r($data); die;
            setCache($key, $data);
            return responses($data, null);

        } catch (QueryException $th) {
            return errorQuery($th);
        }
    }

//get satu data employee berdasarkan id(GET)
    public function show($id) {
        try{
            $data = Employee::select([
            'id',
            'nik',
            'nama',
            'alamat',
            'umur',
            'tgl_lahir',
            'tgl_gabung',
            'status'
        ])
        ->where('id', $id)
        ->where('deleted_at', null)
        ->firstOrFail();

        $key = 'data-employee';
        $keyId = 'data-employee-'.$id;
        deleteCache($key);
        deleteCache($keyId);
        return responses($data, 201);

        } catch (QueryException $th) {
            return errorQuery($th);
        }
    }

//get satu data employee berdasarkan id(POST)
public function getEmpId(Request $request) {
    $id = $request->id;
    // print_r($id); die;
    try {
        $emp = Employee::select([
            'id',
            'nik',
            'nama',
            'alamat',
            'umur',
            'tgl_lahir',
            'tgl_gabung',
            'status'
        ])
        ->where('id', $id)
        ->where('deleted_at', null)
        ->get();
        // print_r($dept); die;
        $emp = json_decode($emp);
        
        if ($emp == null) {
            return errorCustomStatus(404, null);
        } else {
            return responses($emp, null);
        }

    } catch (QueryException $th) {
        return errorQuery($th);
    }
}

//insert satu data employee ke dalam database(POST)
    public function store(Request $request) {
        try {
            $this->validate($request, [
                'nik' => 'required|unique:employee',
                'nama' => 'required',
                'alamat' => 'required',
                'tgl_gabung' => 'required'
            ]);
            
            $data = new Employee;

            $data->nik = $request->nik;
            $data->nama = $request->nama;
            $data->alamat = $request->alamat;
            $data->umur = $request->umur;
            $data->tgl_lahir = $request->tgl_lahir;
            $data->tgl_gabung = $request->tgl_gabung;
            $data->status = 1;
            $data->save();

            $data->message = "Data berhasil disimpan";

            $key = 'data-employee';
            deleteCache($key);
            return responses($data, null);

        } catch (QueryException $th) {
            return errorQuery($th);
        }
    }

//edit satu data employee(PUT)
    public function update($id, Request $request) {
        try {
            $check = Employee::where('deleted_at', null)
                             ->where('id', $id)
                             ->firstOrFail();
            $input = $request->all();
            $check->fill($input)->save();
            $check->message = 'Data Berhasil diupdate';

            $key = 'data-employee';
            $keyId = 'data-employee-'.$id;
            deleteCache($key);
            deleteCache($keyId);
            return responses($check, 200);
        } catch (QueryException $th) {
            return errorQuery($th);
        }
    }

//restore satu data employee yang telah dihapus sementara dari database
    public function restore(Request $request) {
        try {
            $id = $request->id;
            
            $check = Employee::where('id', $id)
                             ->where('deleted_at', '!=', null)
                             ->firstOrFail();
            $check->deleted_at = null;
            $check->updated_at = date("Y-m-d H:i:s", time());
            $input = $request->all();
            $check->fill($input)->save();
            $check->message = "Data berhasil direstore";

            $key = 'data-employee';
            $keyId = 'data-employee-'.$id;
            deleteCache($key);
            deleteCache($keyId);
            return responses($check, 200);

        } catch (QueryException $th) {
            return errorQuery($th);
        }
    }

//hapus satu data employeee
    public function delete(Request $request) {
        try {
            $id = $request->id;

            $check = Employee::where('id', $id)
                             ->where('deleted_at', null)
                             ->firstOrFail();
            $check->updated_at = date("Y-m-d H:i:s",time());
            $check->deleted_at = date("Y-m-d H:i:s",time());
            $input = $request->all();
            $check->fill($input)->save();
            $check->message = "Data berhasil dihapus";

            $key = 'data-employee';
            $keyId = 'data-employee-'.$id;
            deleteCache($key);
            deleteCache($keyId);
            return responses($check, 200);

        } catch (QueryException $th) {
            return errorQuery($th);
        }
    }
}