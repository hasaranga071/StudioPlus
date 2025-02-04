@extends('layouts.app')

@section('content')
<div class='s-page-title'>Place New Order</div>
<div style='background-color: lightgrey; width: 90%; border: 2px solid green;padding-left:1%; margin-top: 1%; margin-right: 5%;margin-left: 5%;'>
<!-- Multiple Radios -->
<form class="form-horizontal" action="{{ route('customers.store') }}" method="POST">


    @csrf
        <fieldset>

        <!-- Form Name -->
        <div style="display:flex">
            <div class="section_logo"><img  width="30px" height="30px" src="{{ asset('images/customer.png') }}"/></div>
            <div class="section_title">Customer Information</div>
        </div>

        <div class="form-group">
            <div class="col-md-4" style='display: flex'>
                <div class="radio">
                    <label for="radios-0">
                        <input type="radio" name="client-radio" id="radios-0" value="1" checked="checked">
                        New
                    </label>
                    <label for="radios-1">
                        <input type="radio" name="client-radio" id="radios-1" value="2">
                        Existing
                    </label>
                </div>
                <!-- <div class="radio">
                    <label for="radios-1">
                        <input type="radio" name="client-radio" id="radios-1" value="2">
                        Existing
                    </label>
                </div> -->
            </div>
        </div>
        <script>
        $(document).ready(function () {
            $('input[name="client-radio"]').click(function () {
                const selectedValue = $(this).val();
                if($(this).val()==1)
                {
                    document.getElementById("newuser").style.display = "inline";
                    document.getElementById("olduser").style.display = "none";
                }
                else
                {
                    document.getElementById("olduser").style.display = "inline";
                    document.getElementById("newuser").style.display = "none";
                }
            });

            $("#otype").change(function () {
                var selectedval = $("#otype option:selected").val();
                if(selectedval==1){
                    document.getElementById("Sittings").style.display = "inline";
                }
                else{
                    document.getElementById("Sittings").style.display = "none";
                }
            });

        });
        </script>
        <div id='newuser' style="display:inline">
            <div class="form-group" style="display:flex;gap: 50px">
                <div class="col-md-4">
                    <label class="col-md-4 control-label" for="name">Name (*)</label>
                    <input id="name" name="username" type="text" placeholder="" class="form-control input-md" required="">
                    @error('username')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="col-md-4 control-label" for="phone">Phone (*)</label>
                    <input id="phone" name="phonenumber" type="text" placeholder="" class="form-control input-md" required="">
                    @error('phonenumber')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-group" style="display:flex;gap: 50px">
                <div class="col-md-4">
                    <label class="col-md-4 control-label" for="town">Town</label>
                    <select id="town" name="address" class="form-control">
                        <option value="1">Arangala</option>
                        <option value="2">Hokandara</option>
                        <option value="3">Malabe</option>
                    </select>
                </div>
                <div class="col-md-4">
                <label class="col-md-4 control-label" for="email">Email</label>
                <input id="email" name="email" type="text" placeholder="" class="form-control input-md">
            </div>
            <div class="col-md-4" style="padding-top: 30px;">
                <button id="register" name="register" class="btn btn-primary">Register</button>
                <div class="col-md-12" style="display: flex; justify-content: center; margin-top: 20px;">

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                </div>
            </div>


            </div>
        </div>
    </fieldset>
</form>
<form class="form-horizontal" action="{{ route('customers.store') }}" method="POST">
        @csrf
            <fieldset>

        <div id='olduser' style="display:none">
            <div class="form-group" style="display:flex;gap: 50px">
                <div class="col-md-4">
                        <label class="col-md-4 control-label" for="name">Name</label>
                        <input id="name" name="username" type="text" placeholder="" class="form-control input-md" required="">

                    </div>
                    <div class="col-md-4">
                        <label class="col-md-4 control-label" for="phone">Phone</label>
                        <input id="phone" name="phonenumber" type="text" placeholder="" class="form-control input-md" required="">
                    </div>
                    <div class="col-md-4" style="padding-top: 30px;">
                        <button id="register" name="search" class="btn btn-primary">Search</button>
                    </div>
            </div>
            <div id="olduser_search_result" style="width: 70%; height: 20px; border: 2px dotted green; padding: 50px; margin-left: 5px; margin-top: 5px;">

            </div>
        </div>


        </fieldset>
    </form>


    <form id="orderdetails" class="form-horizontal" style="height: 600px;">
        <fieldset>
            <div style="display:flex">
                <div class="section_logo"><img  width="30px" height="30px" src="{{ asset('images/order.png') }}"/></div>
                <div class="section_title">Order Information</div>
            </div>
            <div class="form-group" style="display:flex;gap: 600px;padding-top: 15px;">
                <div class="col-md-4">
                    <label class="col-md-4 control-label" for="town">Order Type (*)</label>
                    <select id="otype" name="otype" class="form-control">
                        <option value="1">Studio Sittings</option>
                        <option value="2">Extra Copy</option>
                        <option value="3">Media</option>
                        <option value="4">Frames</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="col-md-4 control-label" for="phone">Order No.</label><br>
                    <span class="label label-primary">SS_20241006_005</span>
                </div>
            </div>
            </br>
            <div class="row">
                <div class="column1" style="background-color:#bbb;">
                    <div class="col-md-4" id="Sittings">
                        <label class="col-md-4 control-label" for="town">Item (*)</label>
                        <select id="item" name="town" class="form-control" style="width: 57%;">
                            <option value="1">Passport</option>
                            <option value="2">NIC</option>
                            <option value="3">Stamp</option>
                        </select>
                    </div>
                    <div class="form-group" style="display:flex;gap: 50px">
                        <div class="col-md-4">
                            <label class="col-md-4 control-label">H-Copies</label>
                            <input id="hcopy" name="hcopy" type="text" placeholder="" class="form-control input-md" required="">
                        </div>
                        <div class="col-md-4">
                            <label class="col-md-4 control-label" >S-Copies</label>
                            <input id="scopy" name="scopy" type="text" placeholder="" class="form-control input-md" required="">
                        </div>
                    </div>
                    <div class="form-group" style="display:flex;gap: 50px">
                        <div class="col-md-4">
                            <label class="col-md-4 control-label">Delivery date</label>
                            <input class="form-control input-md" type="date" id="deldate" name="deldate">
                        </div>
                        <div class="col-md-4">
                            <label class="col-md-4 control-label">Urgent</label></br>
                            <input class="nput-md" type="checkbox" id="urgent" name="urgent" style="zoom: 350%;">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="col-md-4 control-label">Comments</label></br>
                        <textarea id="comments" name="comments" rows="4" cols="70"></textarea>
                    </div>
                    <div class="col-md-4" style="padding-top: 30px;">
                        <button id="additem" name="additem" class="btn btn-primary">Add</button>
                    </div>
                </div>
                <div class="column2" style="background-color:#aaa;">
                    <h2>Order Summary</h2>

                </div>
            </div>
        </fieldset>
    </form>
<div>
@endsection
