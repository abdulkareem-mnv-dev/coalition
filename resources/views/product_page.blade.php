@extends('master')

@section('title','Products')

@section('content')

<div class="container my-5">
    <div class="row my-2">
        <h2>Enter a product</h2>
    </div>
    <form id="product-form">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name_ar">Product Name</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name_ar">Quantity in stock</label>
                    <input type="number" id="quantity" name="quantity" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name_ar">Price</label>
                    <div class="input-group mb-3">
                        <input type="number" id="price" name="price" class="form-control" required>
                        <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon2">USD</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-md-4 offset-md-4">
                <button id="add_product" type="button" class="btn btn-success form-control">Add</button>
            </div>
        </div>
    </form>
    <hr class="my-5">
    <div class="row my-2">
        <h2>The products</h2>
    </div>
    <div class="row">
        <table id="products-table" class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Quantity in stock</th>
                    <th scope="col">Price per item</th>
                    <th scope="col">Total value number</th>
                    <th scope="col">Datetime submitted</th>
                </tr>
            </thead>
            <tbody>
                @if($products->isEmpty())
                    <tr>
                        <td colspan="5" align="center">No products</td>
                    </tr>
                @else
                    <?php $product_price_sum = 0;?>
                    @foreach($products as $product)
                        <?php $product_price_sum += $product->quantity * $product->price;?>
                        <tr>
                            <td>{{$product->name}}</td>
                            <td>{{$product->quantity}}</td>
                            <td>{{number_format($product->price,2)}} USD</td>
                            <td>{{number_format(($product->quantity * $product->price),2)}} USD</td>
                            <td>{{$product->created_at}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3"><b>Sum of Total value number</b></td>
                        <td>
                            <input type="hidden" id="product_price_sum" value="{{$product_price_sum}}">
                            <b class="product_price_text">{{number_format($product_price_sum,2)}} USD</b>
                        </td>
                        <td></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>


@stop

@section('js')
<script type="text/javascript">
    $(function(){
        $('#add_product').click(function(){
            var data = $('#product-form').serialize();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: 'add-product',
                data: data
            }).done(function (res) {
                var $tbody = $('#products-table tbody');
                var product_price_sum = Number($tbody.find('#product_price_sum').val());
                var new_product =  '<tr>';
                    new_product += '    <td>'+res.product.name+'</td>';
                    new_product += '    <td>'+res.product.quantity+'</td>';
                    new_product += '    <td>'+number_format(res.product.price,2)+' USD</td>';
                    new_product += '    <td>'+number_format(res.product.quantity * res.product.price,2)+' USD</td>';
                    new_product += '    <td>'+res.product.created_at+'</td>';
                    new_product += '</tr>';
                $tbody.find('.product_price_text').text(number_format(product_price_sum,2)+' USD');
                $tbody.prepend(new_product);
                $('#product-form input').val('');
            });
        });
    });

    function number_format (number, decimals, dec_point, thousands_sep) {
        // Strip all characters but numerical ones.
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
</script>
@stop