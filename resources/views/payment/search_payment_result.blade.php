
<table class='table table-hover table table-striped table-bordered table-sm' id='paymentTable'>
    <thead class="thead-dark">
        <tr>
            <th>Partner ID</th>
            <th>Pay Date</th>
            <th>Entered Date</th>
            <th>Amount</th>
            <th>Bank</th>
            <th class="hide-column">Depositor</th>
            <th>Entered By</th>
            <th>Channel</th>
            <th class="hide-column">Description</th>
            <th class="hide-column">Email</th>
            <th class="hide-column">Phone</th>
            <th>Action</th>
        </tr>
    </thead>
        <tbody>
            @php $row_number = 1; @endphp
            @foreach($payments as $payment)
        
           <tr class="payment-row">
                <td>{{$payment['friendlyPartnerIdentifier']}}</td>
                <td>{{$payment['datePaid']}}</td>
                <td>{{$payment['createdDate']}}</td>
                <td>{{getCurrencyCodeFromCollection($currencies, $payment['currencyIdentifier'])}} {{$payment['amountPaid']}}</td>
                <td>{{getBankNameFromCollection($banks, $payment['bankIdentifier'])}}</td>
                <td class="hide-column payment-depositor">{{$payment['depositor']}}</td>
                <td>{{$payment['userIdentifier']}}</td>
                <td>{{$payment['paymentChannel']}}</td>
                <td class="hide-column payment-description">{{$payment['paymentDescription']}}</td>
                <td class="hide-column payment-email">{{$payment['emailAddress']}}</td>
                <td class="hide-column payment-phone">{{$payment['phoneNumber']}}</td>
                <td>

                    @if(getBankNameFromCollection($banks, $payment['bankIdentifier'])  == 'strongroom')<a href='#' onclick="modifyPayment('<?php echo $payment['paymentIdentifier'];?>')" > <i class='fa fa-edit fa-2x' style='color:#52B788; margin-right: 10px;'></i></a> @endif

                    @if(isLoggedInUserAdmin())<a href='#' onclick="deletePayment('<?php echo $payment['paymentIdentifier']; ?>','<?php echo $row_number ?>')" > <i class='fa fa-trash fa-2x' style='color:#9D0208'></i></a>@endif

                </td>
            </tr>
            @php $row_number++; @endphp
        @endforeach

    </tbody>
</table>