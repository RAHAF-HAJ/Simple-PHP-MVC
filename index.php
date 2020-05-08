<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Stock test</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap/bootstrap.min.css" rel="stylesheet">

    <!-- Animate css   -->
    <link href="css/animate.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<style>
    body {
        background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
        background-size: 400% 400%;
        animation: gradient 15s ease infinite;
    }

    @keyframes gradient {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }

    form {
        width: 85%;
        align-self: center;
        background: #ffffff59;
        padding: 25px;
        border-radius: 5px;
        color: #fff;
        font-weight: bold;
    }
</style>

<body>
<div class="container pt-5 d-flex justify-content-center text-center flex-column" style="
    height: 100vh;
">
    <form class="text-left wow bounceInUp" >
        <h3 class="p-2 text-center" style="font-family: cursive;">Product Pricing</h3>
        <div class="form-group wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.6s">
            <label for="prod-name">Product name</label>
            <input name="prod_name" type="text" class="form-control" id="prod-name" class="typeahead"/>
            <small id="prod-help" class="form-text text-muted">Enter the name of the product or part of it.</small>
        </div>
        <div class="form-group wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.7s">
            <label for="qty">QTY</label>
            <input name="qty" type="number" class="form-control" id="qty" min="1" value="1">
        </div>
        <div class="form-group wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.8s">
            <label for="uom">UOM</label>
            <select name="uom" class="form-control" id="uom"></select>
        </div>
        <div class="form-group wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.9s">
            <label for="price">Price</label>

            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                </div>
                <input name="price" type="number" class="form-control" id="price" step="any" readonly>
                <div class="input-group-append">
                    <span class="input-group-text">.00</span>
                </div>
            </div>
        </div>
        <div class="form-group wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="1s">
            <label for="total">Total</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                </div>
                <input name="total" type="number" class="form-control" id="total" step="any" readonly>
                <div class="input-group-append">
                    <span class="input-group-text">.00</span>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap/bootstrap.min.js"></script>
<script src="js/typeahead.js"></script>
<script src="js/wow.min.js"></script>

<script>
    window.prods = [];
    jQuery(document).ready(function () {
        new WOW().init();
        //Get data from DB
        $('#prod-name').typeahead({
            source: function (query, result) {
                $.ajax({
                    url: "api/product/read.php",
                    data: 's=' + query,
                    dataType: "json",
                    type: "GET",
                    success: function (data) {
                        window.prods = data;
                        result($.map(data, function (item) {
                            return item;
                        }));
                    }
                });
            }
        });
        //Set unit select options
        $('#prod-name').on('change', function(e){
            const product_name = e.target.value;
            const selected_prod = window.prods.find(function (x) {
                return x.name == product_name;
            });
            console.log(selected_prod);
            if(selected_prod && selected_prod.units.length > 0) {
                var select_html = '';
                selected_prod.units.forEach(function(unit){
                    var selected_attr = '';
                    if(unit.is_default) {
                        selected_attr = 'selected="selected"';
                    }
                    select_html += '<option ' + selected_attr + ' value="'+ unit.qty +'"> ' + unit.name + '(' + unit.qty +  ') </option>';
                });
                $('#uom').html(select_html);
                $('#price').val(selected_prod.price);
            }
        });
        //Set total price info
        $('#uom').change(function(e) {
            setTotal();
        });

        $('#qty').change(function(e) {
            setTotal();
        });

        function setTotal() {
            qty_per_uom = $('#uom').val();
            qty = parseInt($('#qty').val())
            price = parseFloat($('#price').val());
            total = qty * qty_per_uom * price;
            $('#total').val(total.toFixed(2))
        }
    })
</script>
</body>
</html>