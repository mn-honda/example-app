
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/product_detail.css">
    <title>Document</title>
</head>
<body>
    {{-- ヘッダーのインポート --}}
    <x-header-component></x-header-component>

    <form class="detailForm" action="/cart/add" method="post">
        @foreach($product_detail as $product)
            @foreach($product->image as $image)
                <img src="{{$image->filepath}}" class="productImg" alt="" width="300" height="500">
            @endforeach
            <div class="textArea">
                <div class="leftText">
                    <p>{{$product->name}}  
                    <p>￥@php echo number_format($product->price) @endphp</p>
        
                    <p class="productdetail">商品詳細：<br>{{$product->detail}}</p>
                    サイズ：<select name="size">
                        @php dump($product->sizes); 
                    @foreach($product->sizes as $size)
                        <option value="{{$size->id}}">{{$size->size}}</option>
                    @endforeach
                    </select><br>
                    個数：
                    <select name="quantity">
                    @for($i = 1;$i <= 10;$i++)
                        <option value="{{$i}}">{{$i}}</option>
                    @endfor
                    </select><br>
    
                    <p class="Annotation">
                        ※消費税が含まれています
                        <br>
                        ※送料は別途発生します
                    </p>
                </div>

                
                @endforeach
                <input class="button" type="submit" value="カートに入れる">
                <input type="hidden" value="{{$product->id}}" name="product_id">
                @csrf

            </div>
    </form>
    {{-- フッターのインポート --}}
    @include("/header_footer.footer")
</body>
</html>