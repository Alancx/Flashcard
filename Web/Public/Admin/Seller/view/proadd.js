$(document).ready(function(){
        $("#ProName_c").blur(function(){
            if ($("#ProName_c").val()) {
                // alert('OK');
                $("#ProName").attr('class','form-group has-success');
            } else{
                // alert('NO');
                $("#ProName").attr('class','form-group has-error');
            };
        })

        $("#ProTitle_c").blur(function(){
            if ($("#ProTitle_c").val()) {
                $("#ProTitle").attr('class','form-group has-success');
            } else{
                $("#ProTitle").attr('class','form-group has-warning');
            };
        })
        $("#ProSubtitle_c").blur(function(){
            if ($("#ProSubtitle_c").val()) {
                $("#ProSubtitle").attr('class','form-group has-success');
            } else{
                $("#ProSubtitle").attr('class','form-group has-warning');
            }
        })
        $("#VirtualCount_c").blur(function(){
            if ($("#VirtualCount_c").val()) {
                $("#VirtualCount").attr('class','form-group has-success');
            } else{
                $("#VirtualCount").attr('class','form-group has-warning');
            };
        })
        $("#OldPrice_c").blur(function(){
            if ($("#OldPrice_c").val()) {
                $("#OldPrice").attr('class','form-group has-success');
            } else{
                $("#OldPrice").attr('class','form-group has-warning');
            };
        })
        $("#Price_c").blur(function(){
            if ($("#Price_c").val()) {
                $("#Price").attr('class','form-group has-success');
            } else{
                $("#Price").attr('class','form-group has-warning');
            };
        })
        $("#Freight_c").blur(function(){
            if ($("#Freight_c").val()) {
                $("#Freight").attr('class','form-group has-success');
            } else{
                $("#Freight").attr('class','form-group has-warning');
            };
        })
        $("#FreightRemarks_c").blur(function(){
            if ($("#FreightRemarks_c").val()) {
                $("#FreightRemarks").attr('class','form-group has-success');
            } else{
                $("#FreightRemarks").attr('class','form-group has-warning');
            };
        })
        $("#Remarks_c").blur(function(){
            if ($("#Remarks_c").val()) {
                $("#Remarks").attr('class','form-group has-success');
            } else{
                $("#Remarks").attr('class','form-group has-warning');
            };
        })
        $("#KeyWord_c").blur(function(){
            if ($("#KeyWord_c").val()) {
                $("#KeyWord").attr('class','form-group has-success');
            } else{
                $("#KeyWord").attr('class','form-group has-warning');
            };
        })
    })


