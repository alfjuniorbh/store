<!-- Hot New Arrivals -->
	<div class="new_arrivals">
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="tabbed_container">
						<div class="tabs clearfix tabs-right">
							<div class="new_arrivals_title">Novos Produtos</div>
							<ul class="clearfix">
								<li class="active">Em destaque</li>
							</ul>
							<div class="tabs_line"><span></span></div>
						</div>

						<!-- Product Panel -->
						<div class="product_panel panels active">
							<div class="arrivals_list">

								<div class="container">
									<div class="row">
										<!-- Slider Item -->
										@foreach($products as $product)
										<div class="arrivals_slider_item col col-xs-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
											<div class="border_active"></div>
											<div class="product_item is_new d-flex flex-column align-items-center justify-content-center text-center">
												<div class="product_image d-flex flex-column align-items-center justify-content-center"><img width="120" src="{{asset('catalog/sprintem')}}/{{\App\Models\ProductImage::getCoverImage($product->id)}}" alt="{{$product->name}}"></div>
												<div class="product_content">
													<div class="product_price d-none">R$</div>
													<div class="product_name"><div>{{str_limit($product->name, 50)}}</div></div>
													<div class="product_extrass">
														<a href="{{url('produto')}}/{{$product->slug}}" class="product_cart_button">Veja Mais</a>
													</div>
												</div>
												<div class="product_fav"><i class="fas fa-heart"></i></div>
											</div>
										</div>
										@endforeach
									</div>
								</div>

							</div>
						</div>

					</div>
				</div>
			</div>
		</div>		
	</div>