@extends('layouts.master')
@section('title', 'Role Management')
@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row -->
   <h6 class="hk-sec-title">@yield('title') :: Send Message</h6>
   <p class="mb-20"></p>
    <div class="row">
                    <div class="col-xl-12">
                        <section class="hk-sec-wrapper">
                            <h5 class="hk-sec-title">Default Layout</h5>
                            <p class="mb-25">More complex forms can be built using the grid classes. Use these for form layouts that require multiple columns, varied widths, and additional alignment options.</p>
                            <div class="row">
                                <div class="col-sm">
                                    <form>
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label for="firstName">First name</label>
                                                <input class="form-control" id="firstName" placeholder="" value="" type="text">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label for="lastName">Last name</label>
                                                <input class="form-control" id="lastName" placeholder="" value="" type="text">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="username">Username</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">@</span>
                                                </div>
                                                <input class="form-control" id="username" placeholder="Username" type="text">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input class="form-control" id="email" placeholder="you@example.com" type="email">
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Password</label>
                                            <input class="form-control" id="password" placeholder="Password" type="password">
                                        </div>

                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input class="form-control" id="address" placeholder="1234 Main St" type="text">
                                        </div>

                                        <div class="form-group">
                                            <label for="input_tags">Tags</label>
                                            <select id="input_tags" class="form-control" multiple="multiple">
                                                <option selected="selected">orange</option>
                                                <option>white</option>
                                                <option selected="selected">purple</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="input_tags">Date range</label>
                                            <input class="form-control" type="text" name="daterange" value="01/01/2018 - 01/15/2018" />
                                        </div>

                                        <div class="form-group">
                                            <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
                                            <input class="form-control" id="address2" placeholder="Apartment or suite" type="text">
                                        </div>

                                        <div class="row">
                                            <div class="col-md-5 mb-10">
                                                <label for="country">Country</label>
                                                <select class="form-control custom-select d-block w-100" id="country">
                                                    <option value="">Choose...</option>
                                                    <option>United States</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-10">
                                                <label for="state">State</label>
                                                <select class="form-control custom-select d-block w-100" id="state">
                                                    <option value="">Choose...</option>
                                                    <option>California</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-10">
                                                <label for="zip">Zip</label>
                                                <input class="form-control" id="zip" placeholder="" type="text">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="custom-control custom-checkbox mb-15">
                                            <input class="custom-control-input" id="same-address" type="checkbox" checked>
                                            <label class="custom-control-label" for="same-address">Shipping address is the same as my billing address</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" id="save-info" type="checkbox">
                                            <label class="custom-control-label" for="save-info">Save this information for next time</label>
                                        </div>
                                        <hr>

                                        <h6 class="form-group">Payment</h6>

                                        <div class="d-block mt-20 mb-30">
                                            <div class="custom-control custom-radio mb-10">
                                                <input id="credit" name="paymentMethod" class="custom-control-input" checked="" type="radio">
                                                <label class="custom-control-label" for="credit">Credit card</label>
                                            </div>
                                            <div class="custom-control custom-radio mb-10">
                                                <input id="debit" name="paymentMethod" class="custom-control-input" type="radio">
                                                <label class="custom-control-label" for="debit">Debit card</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input id="paypal" name="paymentMethod" class="custom-control-input" type="radio">
                                                <label class="custom-control-label" for="paypal">PayPal</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label for="cc-name">Name on card</label>
                                                <input class="form-control" id="cc-name" placeholder="" type="text">
                                                <small class="form-text text-muted">Full name as displayed on card</small>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label for="cc-number">Credit card number</label>
                                                <input class="form-control" id="cc-number" placeholder="" data-mask="9999-9999-9999-9999" type="text">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 form-group">
                                                <label for="cc-expiration">Expiration</label>
                                                <input class="form-control" id="cc-expiration" placeholder="" data-mask="99-99" type="text">
                                                <div class="invalid-feedback">
                                                    Expiration date required
                                                </div>
                                            </div>
                                            <div class="col-md-3 form-group">
                                                <label for="cc-cvv">CVV</label>
                                                <input class="form-control" id="cc-cvv" data-mask="999" placeholder="" type="text">
                                            </div>
                                        </div>
                                        <hr>
                                        <button class="btn btn-primary" type="submit">Continue to checkout</button>
                                    </form>
                                </div>
                            </div>
                        </section>
                        <section class="hk-sec-wrapper">
                            <h5 class="hk-sec-title">Horizontal Layout</h5>
                            <p class="mb-25">Create horizontal forms with the grid by adding the <code>.row</code> class to form groups and using the <code>.col-*-*</code> classes to specify the width of your labels and controls.</p>
                            <div class="row">
                                <div class="col-sm">
                                    <form>
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
                                            </div>
                                        </div>
                                        <fieldset class="form-group mb-15">
                                            <div class="row">
                                                <label class="col-form-label col-sm-2 pt-0">Radios</label>
                                                <div class="col-sm-10">
                                                    <div class="custom-control custom-radio mb-5">
                                                        <input id="option_1" name="optionHorizontal" class="custom-control-input" checked="" type="radio">
                                                        <label class="custom-control-label" for="option_1">Option 1</label>
                                                    </div>
                                                    <div class="custom-control custom-radio mb-5">
                                                        <input id="option_2" name="optionHorizontal" class="custom-control-input" type="radio">
                                                        <label class="custom-control-label" for="option_2">Option 2</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input id="option_3" name="optionHorizontal" class="custom-control-input" type="radio">
                                                        <label class="custom-control-label" for="option_3">Option 3</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-2 pt-0">Checkbox</label>
                                            <div class="col-sm-10">
                                                <div class="custom-control custom-checkbox mb-15">
                                                    <input class="custom-control-input" id="chkbox_horizontal" type="checkbox" checked>
                                                    <label class="custom-control-label" for="chkbox_horizontal">Checkbox</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-0">
                                            <div class="col-sm-10">
                                                <button type="submit" class="btn btn-primary">Sign in</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </section>
                        <section class="hk-sec-wrapper">
                            <h5 class="hk-sec-title">Inline Layout</h5>
                            <p class="mb-25">Use the <code>.form-inline</code> class to display a series of labels, form controls, and buttons on a single horizontal row. </p>
                            <div class="row">
                                <div class="col-sm">
                                    <form class="form-inline">
                                        <div class="form-row align-items-center">
                                            <div class="col-auto">
                                                <label class="sr-only" for="inlineFormInput">Name</label>
                                                <input type="text" class="form-control mb-2" id="inlineFormInput" placeholder="Jane Doe">
                                            </div>
                                            <div class="col-auto">
                                                <label class="sr-only" for="inlineFormInputGroup">Username</label>
                                                <div class="input-group mb-2">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">@</div>
                                                    </div>
                                                    <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="Username">
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="form-check mb-2">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" id="chkbox_inline" type="checkbox" checked>
                                                        <label class="custom-control-label" for="chkbox_inline">Remember me</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button type="submit" class="btn btn-primary mb-2">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </section>
                        <section class="hk-sec-wrapper">
                            <h5 class="hk-sec-title">Form with icon</h5>
                            <p class="mb-25">Place an icon inside add-on on either side of an input. You may also place one on right side of an input.</p>
                            <div class="row">
                                <div class="col-sm">
                                    <form>
                                        <div class="form-group">
                                            <label class="control-label mb-10" for="exampleInputuname_1">User Name</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="icon-user"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="exampleInputuname_1" placeholder="Username">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label mb-10" for="exampleInputEmail_1">Email address</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="icon-envelope-open"></i></span>
                                                </div>
                                                <input type="email" class="form-control" id="exampleInputEmail_1" placeholder="Enter email">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label mb-10" for="exampleInputpwd_1">Password</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="icon-lock"></i></span>
                                                </div>
                                                <input type="password" class="form-control" id="exampleInputpwd_1" placeholder="Enter email">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label mb-10" for="exampleInputpwd_2">Confirm Password</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="icon-lock"></i></span>
                                                </div>
                                                <input type="password" class="form-control" id="exampleInputpwd_2" placeholder="Enter email">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label mb-10">Gender</label>
                                            <div>
                                                <div class="custom-control custom-radio mb-5">
                                                    <input id="radio_1" name="radio1" class="custom-control-input" checked="" type="radio">
                                                    <label class="custom-control-label" for="radio_1">M</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input id="radio_2" name="radio1" class="custom-control-input" checked="" type="radio">
                                                    <label class="custom-control-label" for="radio_1">F</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" id="checkbox_1" type="checkbox" checked>
                                                <label class="custom-control-label" for="checkbox_1">Remember me</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary mr-10">Submit</button>
                                        <button type="submit" class="btn btn-light">Cancel</button>
                                    </form>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
    <!-- /Row -->
</div>
@endsection
