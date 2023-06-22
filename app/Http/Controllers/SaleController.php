<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Credit;
use App\Models\Sale;
use App\Models\SaleDetail;

class SaleController extends Controller
{

    public function confirm() {
        $user_id = auth()->user()->id;
        $user = User::findOrFail($user_id);
        return view('user.sale.confirm', compact('user'));
    }

    public function registration_credit() {
        $user_id = auth()->user()->id;
        $user = User::findOrFail($user_id);
        return view('user.sale.registration_credit', compact('user'));
    }

    public function registration_credit_into_DB(Request $request) {
        // バリデート

        $user_id = auth()->user()->id;

        $credit = Credit::where('user_id', '=', $user_id)->first();
        if ( $credit == null ) {
            $credit = new Credit();
            $credit->user_id = $user_id;
        }

        // 暗号がしているからtext
        $credit->card_number = $request->card_number;
        // 暗号がしているからtext
        $credit->security_code = $request->security_code;
        // timestamp
        // $credit->expiration = strtotime('YY-MM-DD');
        $expiration = "{$request->expiration_yy}-{$request->expiration_mm}-00";
        $credit->expiration = strtotime($expiration);
        $credit()->save();

        return redirect('/confirm');

    }

    public function complete() {
        $user_id = auth()->user()->id;
        // カートの中の商品を購入履歴に追加
        $this->move_cart_to_sale($user_id);

        // -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
        // 購入時の処理は時間があれば記述したい
        // -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

        return redirect('/products');

    }

    private function move_cart_to_sale($user_id) {
        $user = User::find($user_id);

        $cart = $user->cart;
        $cart_details = $cart->cart_details;

        $sale = new Sale();
        $sale->date = strtotime('now');
        $sale->user_id = $user_id;
        $total_amount = 0;

        foreach ( $cart_details as $cart_detail ) {
            $sale_detail = new SaleDetail();
            $sale_detail->user_id = $user_id;
            $sale_detail->sale_id = $sale->id;
            $sale_detail->product_id = $cart_detail->product_id;
            $sale_detail->size_id = $cart_detail->size_id;
            $sale_detail->quantity = $cart_detail->quantity;
            $sale_detail->amount = $cart_detail->amount;
            $sale_detail->save();
            $total_amount += $cart_detail->amount;
        }
        $length = count($cart_details);
        for ( $i = 0; $i < $length; $i++ ) {
            $cart_details[$i]->delete();
        }
        $sale->amount = $total_amount;
        $sale->save();
    }

}