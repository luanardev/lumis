$(document).ready(function() {
	
	$(".bulkselect").click(function(){
		$("input:checkbox").prop("checked", true);		
	});
	
	$(".bulkdeselect").click(function(){
		$("input:checkbox").prop("checked", false);		
	});

	$(".bulkcheck").change(function () {
		$(".checkbox").prop("checked", $(this).prop("checked"));
	});

	$(".checkbox").click(function() {
		if (!$(this).prop("checked")) {
			$(".bulkcheck").prop("checked", false);
		}
	});
	
	$(".btn-action").click(function(e) {
        e.preventDefault();
        var url = $(this).attr("data-url");
        var role = $(this).attr("data-role");
		
        if (typeof role !== "undefined" && role !== null) {
            if (role === "modal") {
                $(".modal-body").load(url, function() {
                    $("#modal").modal({ show: true });
                });
            } else if (role === "link") {
                window.location.href = url;
            } 
        }else{
			window.location.href = url;
		}
    });
	
	$(".bulkaction").click(function(e){
		e.preventDefault();
		
		var url = $(this).attr("data-url");	
        var role = $(this).attr("data-role");
		
		var checkedId = $(".checkbox:checked").val();
		
		if (typeof checkedId == "undefined" && checkedId == null) {
			swal("Oops", "No record selected", "info");
			return false;
		}
		url = url.replace(":id", checkedId);
		
		if (typeof role !== "undefined" && role !== null) {
			if (role === "modal") {
				$(".modal-body").load(url, function() {
					$("#modal").modal({ show: true });
				});
			} else if (role === "link") {
				window.location.href = url;
			} 
		}else{
			window.location.href = url;
		}				
	
	});

	$(".bulkdelete").click(function(e) {

		var url = $(this).attr("data-url");
		var items = []; 

		$(".checkbox:checked").each(function() {  
			items.push($(this).val());
		});  

		if(items.length <=0)  {  
			swal("Oops", "No record selected", "info");
			return false;				 
		}  

		swal({
			title: "Are you sure?",
			text: "Once deleted, you will not be able to recover the record!",
			icon: "warning",
			buttons: true,
			dangerMode: true,
			
		}).then((willDelete)=>{
			if(willDelete){
				bulkDelete(url, items);
			}
		});	 
		  
	});
	
});

function bulkDelete(url, items){

	var joinItems = items.join(",");

	$.ajax({
		url: url,
		type: 'DELETE',
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		data: 'items='+joinItems,
		success: function (data) {
			if (data['success']) {
				$('.checkbox:checked').each(function() {  
					$(this).parents('tr').remove();
				});
				swal('Success!', data['success'], 'success');
			} else if (data['error']) {
				swal('Ooops', data['error'], 'error');
			} else {
				swal('Whoops Something went wrong!!');
			}
		},
		error: function (data) {
			swal(data.responseText);
		}
	});

	$.each(items, function( index, value ) {
		$('table tr').filter("[data-row-id='" + value + "']").remove();
	});
}
