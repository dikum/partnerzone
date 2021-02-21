<a id="show-payments" href="#show-payments-modal" style="display: none;">Payment Modal</a>
<!-- var currencies used in enter_payment.js -->
<script>var currencies = <?php echo json_encode($currencies); ?>; </script>
<div id="show-payments-modal" class="modal-container" >
    <!--"THIS IS IMPORTANT! to close the modal, the class name has to match the name given on the ID-->
    <div  id="btn-close-modal" class="close-show-payments-modal btn-close-modal"> 
        X
    </div>

    <div class="custom-modal-title">
        {{$partner['fullname']}}
        <div>
            <span data-toggle="modal" data-target="#enterPaymentModal" id="enterNewPaymentBtn">
                <image id="show-enter-payment" class='add-object' src="{{'assets/images/add.png'}}" />
            </span>

        </div>
    </div>

    
    
    <div style="padding:0px; margin:0px;" class="container" id="partner-content">
       
        <table class='table table-hover table table-striped table-bordered table-sm' id='partnerPaymentTable'>
            <thead class="thead-dark">     
                <tr>
                    <th>Pay Date</th>
                    <th>Entered Date</th>
                    <th>Amount</th>
                    <th>Bank</th>
                    <th>Depositor</th>
                    <th>Entered By</th>
                    <th>Channel</th>
                    <th>Description</th>
                    <th>Partner ID</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php $row_number = 1; @endphp
                @foreach($payments as $payment)

                    <tr>
                        <td>{{$payment['datePaid']}}</td>
                        <td>{{$payment['createdDate']}}</td>
                        <td>{{getCurrencyCodeFromCollection($currencies, $payment['currencyIdentifier'])}} {{$payment['amountPaid']}}</td>
                        <td>{{getBankNameFromCollection($banks, $payment['bankIdentifier'])}}</td>
                        <td>{{$payment['depositor']}}</td>
                        <td>{{$payment['userIdentifier']}}</td>
                        <td>{{$payment['paymentChannel']}}</td>
                        <td>{{$payment['paymentDescription']}}</td>
                        <td>{{$payment['friendlyPartnerIdentifier']}}</td>
                        <td>{{$payment['emailAddress']}}</td>
                        <td>{{$payment['phoneNumber']}}</td>
                        <td>

                            @if(getBankNameFromCollection($banks, $payment['bankIdentifier'])  == 'strongroom')<a href='#' onclick="modifyPayment('<?php echo $payment['paymentIdentifier'];?>')" > <i class='fa fa-edit fa-2x' style='color:#52B788; margin-right: 10px;'></i></a> @endif

                            @if(isLoggedInUserAdmin())<a href='#' onclick="deletePayment('<?php echo $payment['paymentIdentifier']; ?>','<?php echo $row_number ?>')" > <i class='fa fa-trash fa-2x' style='color:#9D0208'></i></a>@endif

                        </td>
                    </tr>
                    @php $row_number++; @endphp
                @endforeach

            </tbody>
        </table>

    </div>
    
   <!-- Modal -->
    <div class="modal fade" id="enterPaymentModal" tabindex="-1" role="dialog" aria-labelledby="enterPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div style="text-align:center;" id='enter-payment-message'></div>
                <div class="modal-header">
                    <h5 class="modal-title" id="enterPaymentModalLabel">Enter Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form name="enter_payment_form" id="enter_payment_form" method="post">
                    {{ csrf_field() }}

                        <div style="border-bottom: 1px solid #D3D3D3;" class="row pad-row">
                            <div class="col-6">
                                <div class="form-group form-check form-check-inline col-6">
                                    <input id="strong_room_payment" class="form-check-input" type="radio" name="paymentType" value="strong_room_payment">
                                    <label class="form-check-label" for="strong_room_payment">STRONG ROOM</label>
                                </div>

                                <div class="form-group form-check form-check-inline col-6">
                                    <input class="form-check-input" type="radio" name="paymentType" id="bank_statement_payment" value="bank_statement_payment">
                                    <label class="form-check-label" for="bank_statement_payment">BANK STATEMENT</label>
                                </div>
                            </div>
                        </div>

                        <div class="row pad-row">
                            <div class="form-group col-6">
                                <label for='bankStatementIdentifier'>Bank Statement ID</label>
                                <input placeholder="Enter Bank Statement ID" type="text" name="bankStatementIdentifier" id="bankStatementIdentifier" class="form-control">
                            </div>

    
                                
                                <div class="form-group col-3">
                                    <input type="checkbox" name="sendSms" id="sendSms">
                                    <label for="sendSms">SMS</label>
                                </div>

                                <div class="form-group col-3">
                                    <input type="checkbox" name="sendEmail" id="sendEmail">
                                    <label for="sendEmail">Email</label>
                                </div>


                        </div>


                        <div class="greyed">
                            
                            @php $today = now()->format('Y-m-d'); @endphp
                            <div class="row pad-row">

                                <div class="form-group col-6">
                                    <label for='datePaid'>Payment Date</label>
                                    <input class="form-control" type="date" name="datePaid" id="datePaid" value="{{$today}}">
                                </div>

                                <div class="form-group col-6">
                                    <label for='currencyIdentifier'>Currency</label>
                                    <span id="currencyContainer"><input class="form-control" type="text" name="currencyIdentifier" id="currencyIdentifier"></span>
                                </div>
                            </div>

                            <div class="row pad-row">

                                <div class="form-group col-6">
                                    <label for='amountPaid'>Amount</label>
                                    <input type="number" name="amountPaid" id="amountPaid" class="form-control">
                                </div>

                                <div class="form-group col-6">
                                    <label for='bankIdentifier'>Bank</label>
                                    <input class="form-control" type="text" name="bankIdentifier" id="bankIdentifier">
                                </div>
                            </div>

                            <div class="row pad-row">
                                <div class="form-group col-6">
                                    <label for='paymentDepositor'>Depositor</label>
                                    <input class="form-control" type="text" name="paymentDepositor" id="paymentDepositor">
                                </div>

                                <div class="form-group col-6">
                                    <label for='paymentDescription'>Description</label>
                                    <textarea name="paymentDescription" id="paymentDescription" class="form-control"></textarea>
                                </div>
                            </div>

                            <input type="text" name="userIdentifier" id="userIdentifier" value="{{$partner['userIdentifier']}}" hidden>

                        </div>



                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button id='enter_payment_btn' name="enter_payment_btn" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>



<script type="text/javascript" src="./assets/js/custom/partner/delete_partner_payment.js"></script>

<script>

    $("#show-payments").animatedModal({
        animatedIn:'lightSpeedIn',
        animatedOut:'bounceOutDown',
        color:'#3498db',
        // Callbacks
        beforeOpen: function() {
            console.log("About opening");
        },           
        afterOpen: function() {
            console.log("Opened successfully");
        }, 
        beforeClose: function() {
            console.log("Before Close");
        }, 
        afterClose: function() {
            console.log("Closed");
        }
    });

    $('#btn-close-modal').on('click', function(){
        $('#partner-payments-section').html('');
    });

</script>