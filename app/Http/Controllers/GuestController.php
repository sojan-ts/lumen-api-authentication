<?php

namespace App\Http\Controllers;

class GuestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:guests');
    }

    public function allUsers()
    {
        return (new ListController())->allUsers();
    }
    
     public function insertContent(Request $request) {
        $model = Content::class;
        $OperationHelperController = new OperationHelperController();
        $fieldNames = ['title','url','description','length', 'categories'];
        $validations = ['required|string', 'required|string',
        'required|string','required|string','required|integer'];
        $fieldValues = [
            'title' => $request->input('title'),
            'url' => $request->input('url'),
            'visibility' => 2,
            'description' => $request->input('description'),
            'length' => $request->input('length'),
            'categories' => $request->input('categories'),
        ];
        $fieldsToIgnore = ['visibility'];
        $response = $OperationHelperController->
            insertValues(
                $request,
                $model,
                $fieldNames,
                $fieldValues,
                false,
                null,
                null,
                $validations,
                $fieldsToIgnore
            );
        return $response;
    }

    public function updateContent(Request $request, $id)
    {
        $model = Content::class;
        $OperationHelperController = new OperationHelperController();
        $response = $OperationHelperController->
            updateRecord($request, $id, $model);
        return $response;
    }

}
