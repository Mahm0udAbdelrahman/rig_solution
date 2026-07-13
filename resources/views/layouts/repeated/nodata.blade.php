<section id="knowledge-search">
		<div class="row">
				<div class="col-12">
						<div class="card bg-dark text-center white">
								<div class="card-header mt-3 mb-0">
										<h1 class="white my-0">
												You Don't Have Any {{str_replace('All', '', $page_name)}} !
										</h1>
								</div>
								<div class="card-body p-0 mb-2">
									@if($route)
										<p class="card-text my-0">
												Click On This Button Below To Create One.
										</p>
										<br />
										<a href="{{route($route.'.create')}}" class="btn btn-light clear"><i class="la la-plus"></i> Create New {{rtrim(str_replace('All', '', $page_name), 's')}}</a>
									@endif
								</div>
						</div>
				</div>
		</div>
</section>
