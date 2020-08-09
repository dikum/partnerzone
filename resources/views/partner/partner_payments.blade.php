<a id="show-payments" href="#show-payments-modal" style="display: none;">Payment Modal</a>

<div id="show-payments-modal" class="modal-container" >
    <!--"THIS IS IMPORTANT! to close the modal, the class name has to match the name given on the ID-->
    <div  id="btn-close-modal" class="close-show-payments-modal btn-close-modal"> 
        X
    </div>

    <div class="modal-title"></div>
    
    <div style="padding:0px; margin:0px;" class="container" id="partner-content">


       

        <table class='table table-hover table table-striped table-bordered table-sm' id='partnerPaymentTable'>
                <tbody>
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