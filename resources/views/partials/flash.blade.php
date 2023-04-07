<div class="row">
	<div class="col-lg-12">
		@if (session()->has('success'))
			<div class="alert alert-success alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				{{session()->pull('success')}}
			</div>
		@endif

		@if (session()->has('warning'))
			<div class="alert alert-warning alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				{{session()->pull('warning')}}
			</div>
		@endif

		@if (session()->has('info'))
			<div class="alert alert-info alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				{{session()->pull('info')}}
			</div>
		@endif

		@if (session()->has('error'))
			<div class="alert alert-danger alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				{{session()->pull('error')}}
			</div>
		@endif
		
	</div>
</div>
