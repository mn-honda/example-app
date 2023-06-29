<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Image;
use App\Models\Purchase;
use App\Models\Stock;
use App\Models\Size;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Delivery;
use App\Models\Inquiry;

use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function product(){
        if(auth()->user()->is_admin != "1"){
            return redirect("/index");//管理者出ない場合商品一覧画面に戻す
        }
        return view("admin/product_register");
    }
    public function product_register(Request $request)
    {
        $this->validate($request, [
            'name' => ['required'],
            'category' => ['required'],
            'gender' => ['required'],
            'price' => ['required'],
            'image' => 'required|max:1024|mimes:jpg,jpeg,png,gif',
        ]);

        if($request->gender=="メンズ"){
            switch($request->category){
                case 1:
                    $file_path1 = $request->image->store("/images/M'outer", "public");
                    break;
                case 2:
                    $file_path1 = $request->image->store("/images/M'sweat", "public");
                    break;
                case 3:
                    $file_path1 = $request->image->store("/images/M'knit", "public");
                    break;
                case 4:
                    $file_path1 = $request->image->store("/images/M'Tshirt", "public");
                    break;
                case 5:
                    $file_path1 = $request->image->store("/images/M'jeans", "public");
                    break;
                case 6:
                    $file_path1 = $request->image->store("/images/M'shorts", "public");
                    break;
                case 7:
                    $file_path1 = $request->image->store("/images/M'trouser", "public");
                    break;
            }
        }else{
            switch($request->category){
                case 1:
                    $file_path1 = $request->image->store("/images/W'outer", "public");
                    break;
                case 2:
                    $file_path1 = $request->image->store("/images/W'sweat", "public");
                    break;
                case 3:
                    $file_path1 = $request->image->store("/images/W'knit", "public");
                    break;
                case 4:
                    $file_path1 = $request->image->store("/images/W'Tshirt", "public");
                    break;
                case 5:
                    $file_path1 = $request->image->store("/images/W'jeans", "public");
                    break;
                case 6:
                    $file_path1 = $request->image->store("/images/W'shorts", "public");
                    break;
                case 7:
                    $file_path1 = $request->image->store("/images/W'trouser", "public");
                    break;
            }
        }

        $new_products = new Product();
        $new_images1 = new Image();
        $new_stocks = new Stock();

        $new_products->name = $request->name;
        $new_products->category_id = $request->category;
        $new_products->gender = $request->gender;
        $new_products->price = $request->price;
        $new_products->release_date = $request->release_date;
        $new_products->detail = $request->detail;

        $new_products->save();

        $new_images1->filepath = "/" . $file_path1;
        $new_images1->explanation = $request->gender;
        $new_images1->product_id = $new_products->id;

        $new_images1->save();

        $new_stocks->product_id = $new_products->id;
        $new_stocks->stock = "0";

        $new_stocks->save();

      return view('admin/product_register', compact('request'));
    }

    public function purchase_product()
    {
        if(auth()->user()->is_admin != "1"){
            return redirect("/index");
        }
        $products = Product::all();

        return view('admin/purchase_register', compact('products'));
    }

    public function purchase_register(Request $request)
    {
        $this->validate($request, [
            'product' => ['required'],
            'quantity' => ['required'],
            'date' => ['required']
        ]);
        $new_purchases = new Purchase();

        $new_purchases->product_id = $request->product;
        $new_purchases->quantity = $request->quantity;
        $new_purchases->date = $request->date;

        $new_purchases->save();

        $stocks = Stock::whereProduct_id($request->product)->first();

        $stocks->stock = $request->input('quantity')+$stocks->stock;

        $stocks->save();

        return redirect('admin/purchase_register');
    }

    public function purchase_list()
    {
        if(auth()->user()->is_admin != "1"){
            return redirect("/index");
        }
        $purchases = Purchase::all();

        return view('admin/purchase_list', compact('purchases'));
    }

    public function edit_purchase($id)
    {
        if(auth()->user()->is_admin != "1"){
            return redirect("/index");
        }
        $edit_purchase = Purchase::find($id);

        $products = Product::all();

        return view('admin/edit_purchase', compact('edit_purchase', 'products'));

    }

    public function update_purchase(Request $request)
    {
        $this->validate($request, [
            'product' => ['required'],
            'quantity' => ['required'],
            'date' =>['required'],
        ]);

        $upload_purchase = Purchase::find($request->id);

        $upload_purchase->product_id = $request->product;
        $diff_quantity = $upload_purchase->quantity - $request->quantity;
        $upload_purchase->quantity = $request->quantity;
        $upload_purchase->date = $request->date;

        $upload_purchase->save();

        $stocks = Stock::whereProduct_id($request->product)->first();

        $stocks->stock = $stocks->stock - $diff_quantity;

        $stocks->save();

        return redirect('admin/purchase_list');
    }

    public function delete_purchase(Request $request)
    {
        $delete_purchase = Purchase::find($request->id);
        $stocks = Stock::whereProduct_id($request->product_id)->first();
        $stocks->stock = $stocks->stock - $delete_purchase->quantity;
        $stocks->save();

        $delete_purchase->delete();

        return redirect('admin/purchase_list');
    }

    public function stock_list()
    {
        if(auth()->user()->is_admin != "1"){
            return redirect("/index");
        }
        $stocks = Stock::all();

        return view('admin/stock_list', compact('stocks'));
    }

    public function sale_list()
    {
        if(auth()->user()->is_admin != "1"){
            return redirect("/index");
        }
        $saledetails = SaleDetail::all();
        $sizes = Size::all();
        $sales = Sale::all();

        return view('admin/sale_list', compact('saledetails', 'sizes', 'sales'));
    }

    public function sale_deliveried(Request $request)
    {
        $sale_deliveried = Delivery::whereSale_id($request->id)->first();
        if($request->deliveried){
            $sale_deliveried->is_delivered = "1";
        }else if($request->arrived){
            $sale_deliveried->is_delivered = "2";
        }
        $sale_deliveried->save();

        return redirect('admin/sale_list');
    }

    public function contact_list()
    {
        $contacts = Inquiry::all();

        return view('admin/contact_list', compact('contacts'));
    }

}
