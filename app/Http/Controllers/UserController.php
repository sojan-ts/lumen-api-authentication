<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:users');
    }

    public function InsertCategory(Request $request)
    {
        $model = new Category();
        $fillableFields = $model->getFillable();

        $data = $request->json()->all();
        $filteredData = array_intersect_key($data, array_flip($fillableFields));

        $model->fill($filteredData);

        if ($model->save()) {
            return response()->json(['data' => $model, 'message' => 'CREATED'], 201);
        } else {
            return response()->json(['message' => 'FAILED'], 500);
        }

    }
    public function UpdateCategory(Request $request, $id)
    {
        $model = Category::find($id);
        if (!$model) {
            return response()->json(['error' => 'Record not found'], 404);
        }
        $data = $request->json()->all();
        $fillableFields = $model->getFillable();
        foreach ($fillableFields as $field) {
            if (isset($data[$field])) {
                $model->$field = $data[$field];
            }
        }
        if ($model->save()) {
            return response()->json(['message' => 'UPDATED', 'description' => 'Text Data has been updated successfully'], 200);
        } else {
            return response()->json(['message' => 'FAILED'], 500);
        }
    }

    public function UploadImageCategory(Request $request, $id)
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $maxFileSize = 1024 * 1024; // 1MB

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $extension = strtolower($file->getClientOriginalExtension());
            $size = $file->getSize();

            if (in_array($extension, $allowedExtensions) && $size <= $maxFileSize) {
                $fileName = time() . substr(str_shuffle("1234567890"), 0, 45)
                    . substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"), 0, 80) . '_'
                    . $file->getClientOriginalName();
                $file->move(public_path('uploads'), $fileName);

                $model = Category::class;
                $OperationHelperController = new OperationHelperController();
                $response = $OperationHelperController->
                    updateFilename($id, $model, $fileName, 'uploads');
                return $response;

            } else {
                $allowedExtensionsString = implode(', ', $allowedExtensions);
                $errorMessage = 'Only ' . $allowedExtensionsString . ' files under ' . ($maxFileSize / 1024) . ' KB are allowed';
                return response()->json(['status' => 'error', 'message' => $errorMessage]);
            }
        }

        return response()->json(['status' => 'error', 'message' => 'File upload failed']);
    }


    public function PaginateCategory(Request $request)
    {
        $perPage = $request->input('per_page', 2);
        $search = $request->input('search');
        $visibility = $request->input('visibility');

        $items = Category::when($search, function ($query, $search) {
            return $query->where('title', 'like', "%{$search}%");
        })->when($visibility, function ($query, $visibility) {
            return $query->where('visibility', $visibility);
        })->paginate($perPage);

        return response()->json($items);
    }

// public function allUsers()
// {
//     return (new ListController())->allUsers();
// }

//  public function insertContent(Request $request) {
//     $model = Content::class;
//     $OperationHelperController = new OperationHelperController();
//     $fieldNames = ['title','url','description','length', 'categories'];
//     $validations = ['required|string', 'required|string',
//     'required|string','required|string','required|integer'];
//     $fieldValues = [
//         'title' => $request->input('title'),
//         'url' => $request->input('url'),
//         'visibility' => 2,
//         'description' => $request->input('description'),
//         'length' => $request->input('length'),
//         'categories' => $request->input('categories'),
//     ];
//     $fieldsToIgnore = ['visibility'];
//     $response = $OperationHelperController->
//         insertValues(
//             $request,
//             $model,
//             $fieldNames,
//             $fieldValues,
//             false,
//             null,
//             null,
//             $validations,
//             $fieldsToIgnore
//         );
//     return $response;
// }

// public function updateContent(Request $request, $id)
// {
//     $model = Content::class;
//     $OperationHelperController = new OperationHelperController();
//     $response = $OperationHelperController->
//         updateRecord($request, $id, $model);
//     return $response;
// }

}