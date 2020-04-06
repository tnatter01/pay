var totaal = 0;
var voornaam;
var achternaam;
var email;
var x;
var manier;
var anoniem;
function maakBetaallink(){

        $.ajax
        ({
            type: "POST",
            url: "pay.php",
            data: {
                "prijs" : totaal,
                "Voornaam" : voornaam,
                "Achternaam" : achternaam,
                "Email" : email,
                "Manier" : manier
            },
            dataType: "JSON",
            success: function(response)
            {
                console.log(response);
                transactionId = response.transactionId[0];
                $(".loader").hide();
                window.open(response.paymentURL);
                $("#betalen").show();
            },
            error: function (jqXhr, textStatus, errorMessage) { // error callback
                console.log('Error: ' + errorMessage);
            }
        });

}

function berekenTotaalprijs(){
    if($('#budget-10').is(':checked')) {
        totaal = 10.00;
        $("#ander").hide();
    } else if ($('#budget-20').is(':checked')){
        totaal = 20.00;
        $("#ander").hide();
    }
    else if ($('#budget-50').is(':checked')){
        totaal = 50.00;
        $("#ander").hide();
    }
    else if ($('#budget-100').is(':checked')){
        totaal = 100.00;
        $("#ander").hide();
    }
    else{
        $("#ander").show();
        totaal = $("#ander").val();
    }
}

function krijgManier(){
    if($('#Maandelijks').is(':checked')) {
        manier = "maandelijks";
    } else if ($('#Kwartaal').is(':checked')){
        manier = "kwartaal";
    }
    else if ($('#Jaarlijks').is(':checked')){
        manier = "jaarlijks";
    }
    else if ($('#Eenmalig').is(':checked')){
        manier = "eenmalig";
        $(".alert").alert('close');
    }
}

function krijgGegevens(){
    if(!anoniem){
        voornaam = $('#Voornaam').val();
        achternaam = $('#Achternaam').val();
        email = $('#Email').val();
    }
}



//Begin jQuery
$(document).ready( function () {



    // $("#betalen").hide();
    $("#ander").hide();
    $(".loader").hide();


    $("form").on('submit', function(e){
        e.preventDefault();
    });

    $("form").on('change', function(e){
        e.preventDefault();
        krijgGegevens();
        krijgManier();
        berekenTotaalprijs();
    });



    $("#betalen").on('click', function(e){
        e.preventDefault();
        $(".loader").show();

        maakBetaallink();
    });



    $(document).on('click', '#Anoniem', function(){
        if($('#Anoniem').is(':checked')){
            x = $(".gegevens").children().detach();
            anoniem = true;
        }else{
            $(".gegevens").append(x);
            anoniem = false;
        };
    });


} );



