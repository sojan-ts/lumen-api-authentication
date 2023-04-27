<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class OperationHelperController extends Controller
{
    public function insertValues(Request $request, $model, $fieldNames, $fieldValues, $checkflag, $flagname, $validation_error, $validations, $ignoredFields)
    {
        $rules = [];
        foreach ($fieldNames as $key => $fieldName) {
            if (!in_array($fieldName, $ignoredFields)) {
                $rules[$fieldName] = $validations[$key];
            }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'description' => $validator->errors()], 422);
        }

        if ($checkflag) {
            $datavalue = $request->input($flagname);
            $existingflag = $model::where($flagname, $datavalue)->first();

            if ($existingflag) {
                return response()->json(['message' => $validation_error . ' Already Exist', 'description' => 'Found'], 422);
            }
        }

        try {
            $values = array();
            foreach ($fieldValues as $key => $value) {
                $values[$key] = $value;
            }

            $createdModel = $model::create($values);
            $id = $createdModel->id;

            return response()->json(['message' => 'CREATED', 'description' => 'insertion is successful', 'id' => $id], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'FAILED', 'description' => $e], 500);
        }
    }

    public function insertImageFileName(Request $request, $model, $fieldNames, $fieldValues)
    {
        try {
            $values = array_combine($fieldNames, $fieldValues);

            $createdModel = $model::create($values);
            $id = $createdModel->id;

            return response()->json(['message' => 'CREATED', 'description' => 'insertion is successful', 'id' => $id], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'FAILED', 'description' => $e], 500);
        }
    }

    public function updateRecord(Request $request, $id, $modelName)
    {
        $model = $modelName::find($id);
        if (!$model) {
            return response()->json(['error' => 'Record not found'], 404);
        }
        $fillableFields = $model->getFillable();
        foreach ($fillableFields as $field) {
            $model->$field = $request->input($field, $model->$field);
        }
        if ($model->save()) {
            return response()->json(['message' => 'UPDATED', 'description' => 'Text Data has been updated successfully'], 200);
        } else {
            return response()->json(['message' => 'FAILED'], 500);
        }
    }


}
