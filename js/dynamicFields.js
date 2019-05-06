
(function ($) {

    "use strict";

    $(".dynamic").live("change", function () {
        var type = $(this).attr("name"); // rolnik || handlowiec || parent ( nadrzedna umowa )
        var id = $(this).val();
        $.ajax({
            url: 'modules/umowy/dynamicFields.php',
            method: 'GET',

            data: {
                'fieldsFor': type,
                'id': id
            },
            success: function (data) {
                var returned = JSON.parse(data);
                console.log(type);
                console.log(returned);
                if (type == "farmer") {
                    //farmerDetails
                    $("#farmercity").val(returned.farmerDetails.city);
                    $("#farmeremail").val(returned.farmerDetails.email);
                    $("#farmerpesel").val(returned.farmerDetails.pesel);
                    $("#farmerbanknumber").val("50434434543442");
                    $("#farmernrgosp").val("XYZ");
                    $("#farmeraddress").val(returned.farmerDetails.address_1);
                    $("#farmerpostalcode").val(returned.farmerDetails.postal_code);
                }
                if (type == "trader") {
                    //traderDetails
                    $("#traderemail").val(returned.traderDetails.email);
                    $("#traderphone").val(returned.traderDetails.mobile_phone);


                }
                if(type == "parent"){
                    $.each(returned.parent, function (index,element){
                        $("#"+index).val(element);
                    });

                }


            },

        });
    });


})(jQuery);
