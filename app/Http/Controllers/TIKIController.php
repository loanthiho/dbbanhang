<?php

namespace App\Http\Controllers;

use App\Models\tiki;
use Illuminate\Http\Request;

class TIKIController extends Controller
{
    public function getTiki()
    {
        $tiki = tiki::all();
        return response()->json($tiki);
    }
    public function getOneTiki($id)
    {
        $tiki = tiki::find($id);
        return response()->json($tiki);
    }
    public function addTiki(Request $request)
    {
        $tiki = new tiki();
        $tiki->name = $request->input('name');
        $tiki->image = $request->input('image');
        $tiki->price = intval($request->input('price'));
        $tiki->description = $request->input('description');
        $tiki->rate = intval($request->input('rate'));
        $tiki->save();
        return $tiki;
    }
    public function deleteProduct($id)
    {
        $tiki = tiki::find($id);
        $tiki->delete();
        return ['status' => 'ok', 'msg' => 'Delete successed'];
    }
    public function editTiki(Request $request, $id)
    {
        $tiki = tiki::find($id);
        $tiki->name = $request->input('name');
        $tiki->image = $request->input('image');
        $tiki->description = $request->input('description');
        $tiki->rate = intval($request->input('rate'));
        $tiki->price = intval($request->input('price'));
        $tiki->save();
        return response()->json(['status' => 'ok', 'msg' => 'Edit successed']);
    }

    public function uploadImage(Request $request)
    {
        // process image							
        if ($request->hasFile('uploadImage')) {
            $file = $request->file('uploadImage');
            $fileName = $file->getClientOriginalName();

            $file->move('source/image/product', $fileName);

            return response()->json(["message" => "ok"]);
        } else
            return response()->json(["message" => "false"]);
    }

}