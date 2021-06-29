
$(document).on("click",".datepicker",function(){
	$(this).datepicker({
    	format:"dd-mm-yyyy",
    	todayHighlight:true,
    	autoclose: true,
    });
	$(this).datepicker("show");
});

$(".check-form").validate();

$.validator.addMethod('groupno', function(value, element) {
    return (/^([0-9,]+)$/.test(value) || value == '')
}, "Allows only numbers and comma");

$.validator.addMethod('eod', function(value, element) {
	var extension = value.substr( (value.lastIndexOf('.') +1) ).toLowerCase();
    return this.optional(element) || (extension == 'eod') 
}, "Please select a valid EOD file");

$.validator.addMethod('password_pat', function(value, element) {
	return /^(?=.*\d)(?=.*[A-Z])(?=.*[~!@#$%&_^*]).{8,}$/.test(value);
}, "Password must be atleast 8 charaters long. It must contain atleast one Uppercase letter (A-Z), one special charaters ( ! @ # $ % _ ^ * & ~ ) ,and one number(0-9)");

$(document).on("click",".details",function(){
	$("#detailsModal").modal("show");
	var title = $(this).attr("data-title");
	var action = $(this).attr("action");

	$("#detailsModal .modal-title").html(title);
	$("#detailsModal .modal-body").html("Loading....");

	var formAction = base_url+"/"+action;
	console.log(action);

	$.ajax({
	    type: "GET",
	    url : formAction,
	    success : function(data){
	    	
	    	if(!data.success) bootbox.alert(data.message);
	    	else {
	    		$("#detailsModal .modal-body").html(data.message);
	    	}
	    }
	},"json");
});

$(document).on("click", ".delete-div", function() {
	var btn = $(this);

	bootbox.confirm("Are you sure?", function(result) {
      if(result) {
      	
			var initial_html = btn.html();
			btn.html(initial_html+' <i class="fa fa-spin fa-spinner"></i>');
			var deleteDiv = btn.attr('div-id');
			
			var formAction = base_url+'/'+btn.attr('action');

			$.ajax({
			    type: "DELETE",
			    data: {
			    	_token : CSRF_TOKEN
			    },
			    url : formAction,
			    success : function(data){
			    	data = JSON.parse(data);
			    	if(!data.success) bootbox.alert(data.message);
			    	else {
			    		
			    		$("#"+deleteDiv).hide('500', function(){
			    			$("#"+deleteDiv).remove();
				    	});
				    	
			    	}
			    	btn.html(initial_html);

			    }
			},"json");
      	}
    });
});