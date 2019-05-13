
(function ($) {

    "use strict";

    $("select").live("change", function () {
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
              //  console.log(type);
               // console.log(returned);
                if (type == "farmer") {
                    //farmerDetails
                    var adress = "";
                    var adresswork = "";
                    if(returned.farmerDetails.agreementadress){
                        adresswork = returned.farmerDetails.agreementadress;
                    }else{
                        adresswork = returned.farmerDetails.address_1 + ", " + returned.farmerDetails.city + " " + returned.farmerDetails.postal_code;
                    }
                    adress = returned.farmerDetails.address_1 + ", " + returned.farmerDetails.city + " " + returned.farmerDetails.postal_code;
                    $("#farmercity").val(returned.farmerDetails.city);
                    $("#farmeremail").val(returned.farmerDetails.email);
                    $("#farmerpesel").val(returned.farmerDetails.pesel);
                    $("#farmerbanknumber").val("");
                    $("#farmernrgosp").val(returned.farmerDetails.nrgosp);
                    $("#farmeraddress").val(adress);
                    $("#farmeraddresswork").val(adresswork);
                    $("#farmerpostalcode").val(returned.farmerDetails.postal_code);
                    var tr = $("#pelnomocnik2").parent().parent();
                    var input = $(tr).children("span");
                    input = input[1];
                    $(input).css("display","none");
                    $("#pelnomocnik2").append("<option value='"+returned.farmerDetails.pelnomocnik2id+"' selected='selected'> "+returned.farmerDetails.pelnomocnik2name+" </option>");
                    $("#pelnomocnik2").val(returned.farmerDetails.pelnomocnik2id).change();
                    $("#pelnomocnik2").parent().attr("style", "");
                    $("#pelnomocnik2pesel").val(returned.farmerDetails.pelnomocnik2pesel);

                    var tr = $("#trader").parent().parent();
                    var input = $(tr).children("span");
                    input = input[1];
                    $(input).css("display","none");
                    $("#trader").append("<option value='"+returned.farmerDetails.traderid+"' selected='selected'> "+returned.farmerDetails.tradername+" </option>");
                    $("#trader").val(returned.farmerDetails.traderid).change();
                    $("#trader").parent().attr("style", "");
                    $("#traderemail").val(returned.farmerDetails.traderemail);
                    $("#traderphone").val(returned.farmerDetails.traderphone);
                }
                if (type == "trader") {
                    //traderDetails
                    $("#traderemail").val(returned.traderDetails.email);
                    $("#traderphone").val(returned.traderDetails.mobile_phone);
                }
                if(type == "pelnomocnik2"){
                    $("#pelnomocnik2pesel").val(returned.traderDetails.pesel);
                }


                if(type == "parent"){
                    $.each(returned.parent, function (index,element){
                        if(index == 'farmer'){
                            var tr = $("#" + index).parent().parent();
                            var input = $(tr).children("span");
                            input = input[1];
                            $(input).css("display","none");
                            $("#" + index).append("<option value='"+element+"' selected='selected'> "+returned.parent.farmername+" </option>");
                            $("#" + index).val(element).change();
                            $("#"+index).parent().attr("style", "");
                        }
                        if(index == 'trader'){
                            var tr = $("#" + index).parent().parent();
                            var input = $(tr).children("span");
                            input = input[1];
                            $(input).css("display","none");
                            $("#" + index).append("<option value='"+element+"' selected='selected'> "+returned.parent.tradername+" </option>");
                            $("#" + index).val(element).change();
                            $("#"+index).parent().attr("style", "");
                        }
                        if(index == 'pelnomocnik1'){
                            var tr = $("#" + index).parent().parent();
                            var input = $(tr).children("span");
                            input = input[1];
                            $(input).css("display","none");
                            $("#" + index).append("<option value='"+element+"' selected='selected'> "+returned.parent.pelnomocnik1name+" </option>");
                            $("#" + index).val(element).change();
                            $("#"+index).parent().attr("style", "");
                        }
                        if(index == 'pelnomocnik2'){
                            var tr = $("#" + index).parent().parent();
                            var input = $(tr).children("span");
                            input = input[1];
                            $(input).css("display","none");
                            $("#" + index).append("<option value='"+element+"' selected='selected'> "+returned.parent.pelnomocnik2name+" </option>");
                            $("#" + index).val(element).change();
                            $("#"+index).parent().attr("style", "");
                        }
                        if(index == 'datestart'){


                        }
                        else {
                            $("#" + index).val(element);
                        }
                    });

                }


            },

        });
    });


})(jQuery);
