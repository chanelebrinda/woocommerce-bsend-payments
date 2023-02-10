
function scroll_to(clicked_link, nav_height) {
	var element_class = clicked_link.attr('href').replace('#', '.');
	var scroll_to = 0;
	if(element_class != '.top-content') {
		element_class += '-container';
		scroll_to = $(element_class).offset().top - nav_height;
	}
	if($(window).scrollTop() != scroll_to) {
		$('html, body').stop().animate({scrollTop: scroll_to}, 1000);
	}
}

jQuery(document).ready(function($) {

	$('#submit_employer').on('click', function() {

			let data = {
			'action': 'ajaxcreateEmployee',
			'first_name':$('#ibfname').val(),
			'last_name':$('#iblname').val(),
			'username':$('#ibusername').val(),
			'email':$('#ibemail').val(),
			'telephone':$('#ibtelephone').val(),
			'bio':$('#ibbio').val(),
		};
		jQuery.post(sendIdbd.ajaxurl,data,function(response){
			myModal.hide()
			if (response.code == 200) {    
				toastr.success('Employé ajouté avec succès!');
				setTimeout(function () {
					window.location.reload();
				},1000);
			}else{
				toastr.error(response.error);
			}
		});
    });
	$('.forget_employer').on('click', function() {
		$('#UpdateEmployeeModal').modal("hide");
	});

	$('.employelink').on('click', function(e) {
		e.preventDefault();

		let data = {
		  'action': 'ajaxDisplayEmployee',
	   	  'id_element': $(this).attr("value"),
	   };
		
		jQuery.post(sendIdbd.ajaxurl,data,function(response){

			$('#ib_e_fname').val(response[0].first_name);
			$('#ib_e_lname').val(response[0].last_name);
	    	$('#ib_e_username').val(response[0].username);
			$('#ib_e_email').val(response[0].email);
			$('#ib_e_telephone').val(response[0].telephone);
			$('#ib_e_bio').val(response[0].bio);
			$('#ib_e_ID').val(response[0].ID);
	   });

	   $('#UpdateEmployeeModal').modal("show");

	});

	$('#update_employer').on('click', function() {

		let data = {
		'action': 'ajaxUpdateEmployee',
		'ID':$('#ib_e_ID').val(),
		'first_name':$('#ib_e_fname').val(),
		'last_name':$('#ib_e_lname').val(),
		'username':$('#ib_e_username').val(),
		'email':$('#ib_e_email').val(),
		'telephone':$('#ib_e_telephone').val(),
		'bio':$('#ib_e_bio').val(),
		};
		jQuery.post(sendIdbd.ajaxurl,data,function(response){
			
			$('#UpdateEmployeeModal').modal("hide");
			if (response.code == 200) {    
				toastr.success('Modification rèusssite !');
				setTimeout(function () {
					window.location.reload();
				}, 1000);
			}else{
				toastr.error(response.error);
			}
		});
    });

	$('#Delete_employer').on('click', function() {

		let data = {
		'action': 'ajaxDEleteEmployee',
		'ID':$('#ib_e_ID').val(),
		};
		jQuery.post(sendIdbd.ajaxurl,data,function(response){
			$('#UpdateEmployeeModal').modal("hide");
			if (response.code == 200) {    
				toastr.success('Suppression rèusssite !');
				setTimeout(function () {
					window.location.reload();
				}, 1000);
			}else{
				toastr.error(response.error);
			}
		});
    });


});


jQuery(document).ready(function($) {
	var i=1;
	liste = [0];
	$('.ib_add_crenaux').on('click',function(){
		
		var text = '<tr class="tf_lenght" id="ib_crenaux_element'+i+'"> <td> <input id="ib_crenaux_debut" type="time"  class="form-control" name="ib_crenaux_debut[]"></td> <td> <input id="ib_crenaux_fin" type="time" class="form-control" name="ib_crenaux_fin[]"></td><th> <button type="button" class="btn btn-danger btn-sm ib_remove_crenaux" value="'+i+'">Retirer</button> </th> </tr>';
		$('#ib_book_add_delete_table').append(text);
		++i;
		liste.push(i);

		var eventR = $('.ib_remove_crenaux');	
		if(eventR){
			eventR.click(function(){
			var button_id = $(this).attr("value");		
			$("#ib_crenaux_element"+button_id+"").remove();
			liste = jQuery.grep(liste, function(value) {
				return value != button_id;
			  });
		});
	
		}else{
	
		}

	});

	 $('#ib_send_crenaux').on('click', function(e) {
	 	e.preventDefault();
        let count = $('.tf_lenght').length;

		// console.log(count);
		// for(i = 1 ; i>=count+1 ;i++){
		// 	console.log($(this).children('td').children('#ib_crenaux_debut').val());
	    // 	console.log($(this).children('td').children('#ib_crenaux_fin').val());
		// }
		
	 var crenaux = [];
     $('.tf_lenght').each(function(){
		debut = $(this).children('td').children('#ib_crenaux_debut').val();
		fin = $(this).children('td').children('#ib_crenaux_fin').val();	
		    crenaux.push(debut,fin)
	 });

		 let data = {
			'action'  : 'ajaxsaveCrenaux',
			'crenaux': JSON.stringify(crenaux),
		 	'event_id' :$('.ib_crenaux_event').val(),
		};
		
		// jQuery.post(sendIdbd.ajaxurl,data,function(response){
		// 	console.log(response);
        // let crenauxlist = response[0].crenaux.replace('[',' ').replace(']',' ').split(',');
		// 	for(i=0 ; i <= count*2 ; i++){
		// 		console.log(crenauxlist[i]);
			
	    //  	 	$('#ib-display-crenaux').append(<div> <ol class="list-group list-group-numbered">
        //              <li class="list-group-item">crenauxlist[i]</li></ol></div>);
		// 	}
		//  });
   });



	//});

});

jQuery(document).ready(function($){

	$('.pdf_generation').on('click' , function(){

		let data = {
			'action'  : 'ajaxHistoriquePdf',
		};
		// jQuery.post(sendIdbd.ajaxurl,data,function(response){
		// 	console.log(response);
		// });
		$.ajax({
			type :"POST",
			url :sendIdbd.ajaxurl,
			data : data,
			xhrFields: { responseType: 'blob' },
			success : function(response){
			 
			var blob = new Blob([response]);
			var link=document.createElement('a');
			link.href=window.URL.createObjectURL(blob);
			$v = Math.floor(Math.random() * (1 - 10000000000 + 1) + 1)
			link.download="facture"+$v+".pdf";
			link.click();
			}
		});
	});
});