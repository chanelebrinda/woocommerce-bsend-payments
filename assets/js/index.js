
jQuery(document).ready(function ($) {
    var form = document.getElementsByName("checkout");
    
    if(form)
    {
      
        form[0].addEventListener("submit", checkBsend);
        function checkBsend()
        {
            var payment_method = document.getElementsByName("payment_method");
            payment_method.forEach(isChecked);	
            function isChecked(item)
            {
                if(item.checked){
					if(item.value=="bsend"){

                        var amount = 0;
                        var values = JSON.stringify($(form).serializeArray());
                        order = JSON.parse(values)
                        order.forEach(element => {
                            if(element.name == "bsend_link"){
                               amount = element.value;
                            } 
                            if(element.name == "bsend_checkout_test"){
                                ref = element.value;
                             } 
        
                        });
        
                        price = amount;
                        order_phone = order[9].value;     
                        order_mail = order[10].value;
                        order_name = order[0].value + " "+ order[1].value;;
                        description = "Payment from a website using bsend";
                        payement_ref = ref;
                        api_key = bsend_params.private_key;
                        order_country = order[3].value;
                        order_country_code = order[3].value;

                        switch (order_country) {
                            case "CM":
                                order_country_cdial = "237";
                                break;
                            case "BJ":
                                order_country_cdial = "229";
                                break;
                            case "CI":
                                order_country_cdial = "225";
                                break;
                            case "TG":
                                order_country_cdial = "228";
                                break;
                            case "SN":
                                order_country_cdial = "221";
                                break;
                            case "ML":
                                order_country_cdial = "223";
                                break;
                            case "CD":
                            order_country_cdial = "243";
                            break;
                                
                            default:
                                order_country_cdial = "237";
                            }
        
                               let data = { 
                                'action': 'intiatebsendPayement',
                                "amount" : price,
                                "phone" : order_phone,
                                "email" : order_mail ,
                                "first_name" : order_name,
                                "description": description,
                                "payment_ref" : payement_ref,
                                "public_key" : api_key,
                                "country" : order_country,
                                "country_ccode" : order_country_code,
                                "country_cdial": order_country_cdial
                               };
                               jQuery("#place_order").attr("disabled", "disabled");

                               jQuery.post(bsend_params.ajaxurl,data,function(response){
                               
                               if(response['code'] == "400"){
                                   console.log(response['code']); 
                                   jQuery("#place_order").removeAttr("disabled");
               
                               }else if(response['code'] == "200"){ 
                                   $('#bsend_checkout_test').val(response['ref']);
                                     window.open(response['link'],
                               "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=500,width=800,height=600");
                           
                               }else{
                                   jQuery("#place_order").removeAttr("disabled");
                                 }
                               
                          });

                    }
														
				}         
                 
            }
            					
        
        }
        
    }
    

});