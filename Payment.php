//Checkout page
@extends('website.layout.master')
@section('content')
<section class="page-header">
    <div class="page-header-bg" style="background-image: url(assets/images/backgrounds/page-header-bg.jpg)">
    </div>
    <div class="container">
        <div class="page-header__inner">
            <h2>Checkout</h2>
            <ul class="thm-breadcrumb list-unstyled">
                <li><a href="https://test.pearl-developer.com/reuk/public">Home</a></li>
                <li><span>/</span></li>
                <li class="active">Checkout</li>
            </ul>
        </div>
    </div>
</section>

<section class="checkout-section checkout-page">
    <div class="container">

        <div class="row">
            <div class="col-md-6 col-sm-12 col-xs-12 checkout-column">
                <div class="billing-info">
                    <div class="title">
                        <h4>Billing Details</h4>
                    </div>
                    <form action="{{route('saveOrder')}}" method="post" class="billing-form" id="save-order-form">
                    @csrf

                        <div class="row">
                        	<input type="text" value="{{$quantity}}" name="quantity" hidden>
					        <input type="text" value="{{$store_id}}" name="store_id" hidden>
                            

                            <div class="field-input col-md-6 col-sm-6 col-xs-12">
                                <label>First Name*</label>
                                <input type="text" name="name" required="" value="{{$user->name ?? '' }}">

                            </div>
                            
                        
                            <div class="field-input col-md-6 col-sm-6 col-xs-6">
                                <label>Email Address*</label>
                                <input type="text" name="email" required=""  value="{{$user->email ?? '' }}">
                            </div>
                            <div class="field-input col-md-6 col-sm-6 col-xs-12">
                                <label>Phone Number*</label>
                                <input type="text" name="phone_number" required=""  value="{{$user->phone ?? '' }}"> 
                            </div>

                            <div class="field-input col-md-12 col-sm-12 col-xs-12">
                                <label>Address*</label>
                                <input type="text" name="address" required=""  value="{{$user->address ?? '' }}">
                            </div>

                            
                        </div>
                    
                </div>
                <div class="additional-information">
                    <div class="title">
                        <h4>Additional Information</h4>
                    </div>
                    <div class="note-box">
                        <label>Order Notes</label>
                        <textarea placeholder="">Notes about your order, e.g. special notes for your delivery</textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12 checkout-column">
                <div class="order-info">
                    <div class="title">
                        <h4>Your Order</h4>
                    </div>
                <div class="order-item">                
                    <div class="single-item">   

                    <div class="d-flex justify-content-between">
                        <div class="item-name">Cloth Name</div>  
                        
                      <div class="item-name">Price</div>    
                    </div>                                     
                      
                              
                      <table class="cart-table w-100">
                            
                           @foreach ($carts as $cart)                                        
                         <tr>
                                         <td>
                    <input type="text" name="cloth_ids[]" value="{{ $cart->cloth_id }}" hidden>
                </td>
                                     <td>
                    <input type="text" name="quantities[]" value="{{ $cart->quantity }}" hidden>
                </td>

                             <td>{{$cart->cloth->cloth_name}}</td>
                            
                              <td class="text-end">₹{{$cart->price}} * ({{$cart->quantity}})</td>
                             
                           @endforeach            
                        </tr>                                    
                            </tbody>
                        </table>        
                        </div>
                        <div class="single-item">
                            
                            <div class="item-name">Total Quantity</div>
                            <div class="price py-0">{{$quantity}}</div>
                        </div>
                        <div class="sub-total">
                            Sub Total
                            <div class="price">₹{{$price}}</div>
                        </div>
                        <div class="order-total">
                            Order Total
                            <div class="price color">₹{{$price}}</div>
                        </div>
                    </div>
                    
                </div>
                <div class="payment-info">
                    <div class="title">
                        <h4>Payment Proccess</h4>
                    </div>
                    <div class="payment-option">
                        
                        <div class="option-block">
        <div class="radio-block">
            
            <div class="checkbox">
                <label>
                    <input name="payment_method" type="radio" value="cod" required>
                    <span>Cash on Delivery (COD)</span>
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input name="payment_method" type="radio" value="razorpay" required>
                    <span>Razorpay</span>
                </label>
            </div>
        </div>
    </div>
    <!-- Hidden input field to track whether COD or Razorpay is selected -->
    <input type="hidden" name="is_cod" id="is_cod" value="0">
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
    <input type="hidden" name="transaction_via" id="transaction_via" value="">
    <input type="hidden" name="amount" id="amount" value="{{ $price }}">
    <input type="hidden" name="order_id" value="{{ rand(11111,99999) . time() }}">
    <div class="button-box">
        <button class="btn-one" id="proceedToPay" type="submit">Place your order</button>
    </div>
</form>
</div>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
    var amount = {{ $price }} * 100; // Convert price to paisa (Razorpay requires amount in paisa)
    var razorpay_options = {
        key: "rzp_test_XUYF8II8JgW1nX",
        amount: amount,
        name: 'Reuk',
        description: "Product/Service Description",
        netbanking: true,
        currency: "INR",
        prefill: {
            name: 'Your Name',
            email: 'your@example.com',
            contact: '1234567890',
        },
        handler: function(transaction) {
            console.log(transaction);
            $('#razorpay_payment_id').val(transaction.razorpay_payment_id);
            $('#transaction_via').val('razorpay');
            $('#save-order-form').submit();
        },
        "modal": {
            "ondismiss": function() {
                location.reload();
            }
        }
    };

    $(document).ready(function() {
        $("#proceedToPay").click(function(e) {
            e.preventDefault();
            var paymentMethod = $("input[name='payment_method']:checked").val();
            if(paymentMethod == null){
                alert('Select any one payment mode');
                return;
            }
            //alert(paymentMethod);
            if (paymentMethod === 'cod') {
                $('#is_cod').val('1');
                $('#transaction_via').val('cod');
                $('#save-order-form').submit();
            } else if (paymentMethod === 'razorpay') {
                var rzp = new Razorpay(razorpay_options);
                rzp.open();
            } else {
                // Handle other payment methods if needed
            }
        });
    });
</script>
@endsection

//RazorPay Controller
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\User;
use App\Models\RequestForStore;
use App\Models\FranchiseStore;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Hash;

class RazorPayController extends Controller
{
    public function get_curl_handle_razorpay($razorpayPaymentId, $amount, $currencyCode)
    {
        $url = 'https://api.razorpay.com/v1/payments/' . $razorpayPaymentId . '/capture';
        $key_id = 'rzp_live_neJKgZYHc5pcXq';
        $key_secret = 'cZsTh4LH5wEY4baecfes2U4D';
        $arr = ['amount' => $amount, 'currency' => $currencyCode];

        $arr1 = json_encode($arr);
        $fields_string = $arr1;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, $key_id . ':' . $key_secret);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        return $ch;
    }

    public function saveOrder(Request $request)
    {
        if ($request->payment_method == 'cod') {
            $this->storeOrderTable($request);
        }else{
            
            $razorpayPaymentId = $request->razorpay_payment_id;
            $amount = $request->amount;
            $currencyCode = "INR";
            try {
                $ch = $this->get_curl_handle_razorpay($razorpayPaymentId, $amount, $currencyCode);
                //execute post
                $result = curl_exec($ch);
                $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($result === false) {
                    $success = false;
                    $error = 'Curl error: ' . curl_error($ch);
                } else {
                    $response_array = json_decode($result, true);
                    // echo '<pre>';
                    // echo 'asdf';
                    // print_r($response_array);
                    // die;
                    // dd($response_array);
                    $this->storeOrderTable($request);
                    
                    if ($http_status === 200 and isset($response_array['error']) === false) {
                        $success = true;
                    } else {
                        $success = false;
                        if (!empty($response_array['error']['code'])) {
                            $error = $response_array['error']['code'] . ':' . $response_array['error']['description'];
                        } else {
                            $error = 'RAZORPAY_ERROR:Invalid Response <br/>' . $result;
                        }
                    }
                }
                //close connection
                curl_close($ch);
            } catch (Exception $e) {
                $success = false;
                $error = 'OPENCART_ERROR:Request to Razorpay Failed';
            }

            
        }
    }

    public function storeOrderTable($request)
    {
        $clothIdsQuantities = array_combine($request->input('cloth_ids'), $request->input('quantities'));
        // dd($clothIdsQuantities);
        foreach ($clothIdsQuantities as $clothId => $quantity) {
            $order = new Order();
            $order->cloth_id = $clothId; // Assign each cloth ID separately
            $order->store_id = $request->store_id;
            $order->name = $request->name;
            $order->email = $request->email;
            $order->phone_number = $request->phone_number;
            $order->payment_method = $request->payment_method;
            $order->quantity = $quantity;
            if (Auth::check()) {
                $order->user_id = Auth::user()->id;
            }
            $order->save();
        }
        return 'success';
    }
}

