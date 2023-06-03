<?php

namespace App\Http\Controllers;

use App\Models\bill_detail;
use App\Models\comments;
use App\Models\products;
use App\Models\Slide;
use App\Models\type_products;
use Illuminate\Http\Request;

class PageController extends Controller
{
   

    public function getIndex()
    {
        $slide = Slide::all();
        $new_product = products::where('new', 1)->paginate(8);
        //san pham khuyen mai
        $sanpham_khuyenmai = products::where('promotion_price', '<>', 0)->paginate(8);
        return view('page.trangchu', compact('slide', 'new_product', 'sanpham_khuyenmai'));
    }


    public function getDetail(Request $request)
    {
        $sanpham = products::where('id', $request->id)->first();
        $splienquan = products::where('id', '<>', $sanpham->id, 'and', 'id_type', '=', $sanpham->id_type)->paginate(3);
        $comments =    comments::where('id_product', $request->id)->get();
        return view('page.chitiet_sanpham', compact('sanpham', 'splienquan', 'comments'));
    }


    public function getLoaiSp($type)
    {
        $type_product = type_products::all(); //Show ra tên loại sản phẩm
        $sp_theoloai = products::where('id_type', $type)->get();
        $sp_khac = products::where('id_type', '<>', $type)->paginate(3);
        return view('page.loai_sanpham', compact('sp_theoloai', 'type_product', 'sp_khac'));
    }

    public function getIndexAdmin()
    {
        $product = products::all();
        return view('pageadmin.admin')->with(['products' => $product, 'sumSold' => count( bill_detail::all())]);
    }

    public function getAdminAdd()
    {
        return view('pageadmin.formAdd');
    }



    public function postAdminAdd(Request $request)
    {
        $product = new products();
        if ($request->hasFile('inputImage')) {
            $file = $request->file('inputImage');
            $fileName = $file->getClientOriginalName('inputImage');
            $file->move('source/image/product', $fileName);
        }
        $file_name = null;
        if ($request->file('inputImage') != null) {
            $file_name = $request->file('inputImage')->getClientOriginalName();
        }

        $product->name = $request->inputName;
        $product->image = $file_name;
        $product->description = $request->inputDescription;
        $product->unit_price = $request->inputPrice;
        $product->promotion_price = $request->inputPromotionPrice;
        $product->unit = $request->inputUnit;
        $product->new = $request->inputNew;
        $product->id_type = $request->inputType;
        $product->save();
        return redirect('/admin');
    }
    public function getAdminEdit($id)
    {
        $product = products::find($id);
        return view('pageadmin.formEdit')->with('product', $product);
    }
    public function postAdminEdit(Request $request)
    {
        $id = $request->editId;
        $product = products::find($id);
        if ($request->hasFile('editImage')) {
            $file = $request->file('editImage');
            $fileName = $file->getClientOriginalName();
            $file->move('source/image/product', $fileName);
        }

        if ($request->file('editImage') != null) {
            $product->image = $fileName;
        }

        $product->name = $request->editName;
        $product->description = $request->editDescription;
        $product->unit_price = $request->editPrice;
        $product->promotion_price = $request->editPromotionPrice;
        $product->unit = $request->editUnit;
        $product->new = $request->editNew;
        $product->id_type = $request->editType;
        $product->save();
        return redirect('/admin');

       
    }

    public function postAdminDelete($id)
    {
      $products = products::find($id);
      $products->delete();
      return $this->getIndexAdmin();  
    }
}