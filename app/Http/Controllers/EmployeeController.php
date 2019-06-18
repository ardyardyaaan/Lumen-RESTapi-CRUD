<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use App\Employee;

class EmployeeController extends Controller {

//get list data employee(GET)
    public function listEmp() {
        try {
            $emps = Employee::select([
                'id',
                'nik',
                'nama',
                'alamat',
                'umur',
                'tgl_lahir',
                'tgl_gabung',
                'status',
                'created_at',
                'updated_at',
                'deleted_at'
            ])
            ->get();

            return responses($emps, null);

        } catch (QueryException $th) {
            return \errorCustomStatus(500, $th);
        }
    }

//get satu data employee berdasarkan id(GET)
    public function emp($id) {
        try{
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
        ->firstOrFail();

        return responses($emp, null);

        } catch (QueryException $th) {
            return \errorCustomStatus(500, $th);
        }
    }

//insert satu data employee ke dalam database(POST)
    public function store(Request $request) {
        try {
            $this->validate($request, [
                'nik' => 'required',
                'nama' => 'required',
                'alamat' => 'required',
                'tgl_gabung' => 'required'
            ]);

            Employee::insert([
                'nik' => $request->nik,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'umur' => $request->umur,
                'tgl_lahir' => $request->tgl_lahir,
                'tgl_gabung' => $request->tgl_gabung,
                'status' => 1,
                'created_at' => date("Y-m-d H:i:s", time())
            ]);

            return responses(['message' => 'Data berhasil disimpan'], 201);

        } catch (QueryException $th) {
            return \errorCustomStatus(500, $th);
        }
    }

//edit satu data employee(PUT)
    public function update($id, Request $request) {
        try {
            $check = Employee::where('deleted_at', null)
                             ->where('id', $id)
                             ->firstOrFail();
            $check->updated_at = date("Y-m-d H:i:s", time());
            $input = $request->all();
            $check->fill($input)->save();

            return responses(['message' => 'Data berhasil diupdate'], null);

        } catch (QueryException $th) {
            return \errorCustomStatus(500, $th);
        }
    }

//hapus satu data employee, tidak permanen/tidak terhapus dari database(DELETE)
    public function softDelete($id) {
        try {
            $check = Employee::where('deleted_at', null)
                             ->where('id', $id)
                             ->firstOrFail();
            $check->deleted_at = date("Y-m-d H:i:s", time());
            $check->updated_at = date("Y-m-d H:i:s", time());
            $check->save();

            return responses(['message' => 'Data berhasil dihapus'], null);

        } catch (QueryException $th) {
            return \errorCustomStatus(500, $th);
        }
    }

//restore satu data employee yang telah dihapus sementara dari database
    public function restore($id) {
        try {
            $check = Employee::where('deleted_at', '!=', null)
                             ->where('id', $id)
                             ->firstOrFail();
            $check->deleted_at = null;
            $check->updated_at = date("Y-m-d H:i:s", time());
            $check->save();

            return responses(['message' => 'Data berhasil direstore'], null);

        } catch (QueryException $th) {
            return \errorCustomStatus(500, $th);
        }
    }

//hapus satu data employee secara permanen dari database
    public function destroy($id) {
        try {
            $check = Employee::where('deleted_at', '!=', null)
                             ->where('id', $id)
                             ->firstOrFail();
            $check->delete();

            return responses(['message' => 'Data berhasil dihapus'], null);

        } catch (QueryException $th) {
            return \errorCustomStatus(500, $th);
        }
    }
}