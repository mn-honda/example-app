<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>購入完了</title>
    <link rel="stylesheet" href="/css/complete.css">
</head>
<body>
    {{-- ヘッダーのインポート --}}
    <x-header-component></x-header-component>
    
    <div>
        <h1 class="U-S">UsenStudios</h1>
        <h2>ご購入ありがとうございました！</h1>
        <br>
        <br>
        <h2>またのご利用お待ちしております！</h1>
    </div>


        {{-- <div> --}}
            {{-- ご注文日時: {{ date('Y-m-d', $sale->date) }} --}}
        {{-- </div> --}}
        <!-- <div>
            ご注文概要
            @if ( isset($sale->sale_details) )
                @foreach ($sale->sale_details as $sale_detail)
                    <div>
                        @foreach( $sale_detail->product->image as $image )
                            <img src='{{asset($image->filepath)}}' alt="$image->explanation">
                        @endforeach
                    </div>
                    <div>
                        <p> 商品名 </p>
                        <p> {{$sale_detail->product->name}} </p>
                    </div>
                    <div>
                        {{-- <p> カラー: {{}} </p> --}}
                        <p> サイズ: {{$sale_detail->size->size}} </p>
                        <p> 数量: {{$sale_detail->quantity}} </p>
                    </div>
                    <div>
                        <p> ￥{{$sale_detail->amount}} </p>
                    </div>
                @endforeach
            @endif
        </div>
        <div>
            <p>
                <span>小計</span>
                    @if ( isset($sale->amount) )
                        <span>{{$sale->amount}}</span>
                    @else
                        <span>0</span>
                    @endif
                <br>
                <span>送料</span>
                <span>￥800</span>
            </p>
            <p>
                <span>合計</span>
                @if ( isset($sale->amount) )
                    <span>￥{{$sale->amount + 800}}</span>
                @else
                    <span>￥800</span>
                @endif
            </p>
        </div>
 -->
    {{-- フッターのインポート --}}
    @include("/header_footer.footer")
</body>
</html>
