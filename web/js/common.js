
$(document).ready(function () {

	$('.form-auth').submit(function(e)
	{
		//e.preventDefault();
		console.log('.form-auth');

	});

	$('.form-link-reg,.form-link-login').on('click',function(e){
		e.preventDefault();
		//console.log('.form-link-reg,.form-link-login');
	  	// нужно скрыть 
	  	var hiddenItemsPr1 = $('.form-auth p.capt.hide');
	  	var hiddenItemsBtn = $('.form-auth .btn.hide');
	  	var hiddenItemsInp = $('.form-auth div.hide');
	  	var hiddenItemsPr2 = $('.form-auth p.reg.hide');
	  	var hiddenItemsLbl = $('.form-auth label.hide');  	
	  	var hiddenArr = [hiddenItemsPr1,hiddenItemsBtn,hiddenItemsInp,hiddenItemsPr2,hiddenItemsLbl];
	  	var showItPr1 = $('.form-auth p.capt.show');  	
	  	var showItPr2 = $('.form-auth p.reg.show');
	  	var showItBtn = $('.form-auth .btn.show');
	  	var showItemsLbl = $('.form-auth label.show');
	  	var showArr = [showItPr1,showItPr2,showItBtn,showItemsLbl];
	  	//console.log(hiddenArr);
	  	//console.log(showArr);
	  	$(hiddenArr).each(function(i,v){
	  		//console.log(v);
	  		v.removeClass('hide').addClass('show');
	  	});  	
	  	$(showArr).each(function(i,v){
	  		//console.log(v);
	  		v.removeClass('show').addClass('hide');
	  	});  	
	  	//$('#exampleInputName1').attr('required','');   	
	  	var attr = $('#exampleInputName1').attr('required');
	  	console.log('required: '+attr);
		// For some browsers, `attr` is undefined; for others,
		// `attr` is false.  Check for both.
		if (typeof attr !== typeof undefined && attr !== false) {
		    // ...
		    console.log('attr is OK');
		    $('#exampleInputName1').removeAttr('required');
		    $('#exampleInputName1').parent().removeClass('show');
		    $('#exampleInputName1').parent().addClass('hide');
		 }else{
		  	// ...
		  	console.log('attr is NOT OK');	  		
		  	//$('#exampleInputName1').attr('required','');
		  }
	});


    // preloader
    $('#hellopreloader_preload').delay(450).fadeOut('slow');
    console.log('yead');

});





