<?php

namespace App\Http\Controllers\MasterController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class MasterController extends Controller
{
    public $type = "Data";
    public array $columns = ['*'];
    public function __construct(public Model $model, public string $request_class)
    {
        
    }

     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = $this->model->select($this->columns)->get();
            return $this->successResponse("$this->type fetched successfully", $data);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $req = app($this->request_class);
        $data = $req->validated();
        try {
            $result = $this->model->create($data);
            return $this->successResponse("$this->type created successfully", $result);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $req = app($this->request_class);
        $data = $req->validated();
        // dd($data);
        try {
            $result = $this->model->findOrFail($id);
            unset($result['id']);
            $result->update($data);
            return $this->successResponse("$this->type updated successfully", $result);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $result = $this->model->findOrFail($id);
            $result->delete();
            return $this->successResponse("$this->type deleted successfully");
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
