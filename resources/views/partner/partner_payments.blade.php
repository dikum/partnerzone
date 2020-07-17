<a id="show-payments" href="#show-payments-modal" style="display: none;">Payment Modal</a>

<div id="show-payments-modal">
    <!--"THIS IS IMPORTANT! to close the modal, the class name has to match the name given on the ID-->
    <div  id="btn-close-modal" class="close-show-partner-modal"> 
        X
    </div>
    
    <div class="container" id="partner-content">
       @php print_r($payments); @endphp
    </div>


    

</div>

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

</script>