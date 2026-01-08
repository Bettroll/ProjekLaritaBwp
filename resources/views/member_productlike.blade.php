@extends('member_master')

@section('konten_member')

<div class="row mb-3">
	<div class="col-12 d-flex align-items-center">
		<a href="/home" class="btn btn-sm btn-outline-secondary me-2">← Kembali</a>
		<h5 class="fw-bold mb-0">Produk Favorit Saya</h5>
	</div>
	@if($selectedLocation)
		<div class="col-12">
			<div class="alert alert-info py-2 mt-2 mb-0">
				Lokasi aktif: <strong>{{ $selectedLocation->location_name }}</strong>
			</div>
		</div>
	@endif
    
	<hr class="mt-3"/>
</div>

@if(isset($selectedLocation) && $selectedLocation)
	<div class="row">
		@forelse($products as $p)
			@php
				$pivotData = $p->locations->first()->pivot;
				$likeCount = \App\Models\ProductLike::where('product_id', $p->id)
					->where('location_id', $selectedLocation->id)
					->count();
				$liked = Auth::check() ? \App\Models\ProductLike::where('product_id', $p->id)
					->where('location_id', $selectedLocation->id)
					->where('user_id', Auth::id())
					->exists() : false;
			@endphp
			<div class="col-md-3 mb-4">
				<div class="card h-100 product-card">
					<img src="{{ asset('images/products/'.$p->image) }}" class="card-img-top p-2 rounded" style="height: 180px; object-fit: cover;">
					<div class="card-body">
						<h6 class="fw-bold mb-1">{{ $p->product_name }}</h6>
						<p class="small text-muted mb-2">{{ Str::limit($p->description, 40) }}</p>
						<div class="d-flex justify-content-between align-items-center">
							<span class="price-tag">Rp {{ number_format($pivotData->price) }}</span>
							<button type="button" class="btn btn-link p-0 text-decoration-none like-btn" 
									style="border: none; background: none;" 
									data-product-id="{{ $p->id }}" 
									data-liked="{{ $liked ? '1' : '0' }}">
								<span class="heart-icon" style="font-size: 1.2rem; color: {{ $liked ? '#dc3545' : '#6c757d' }};">
									{{ $liked ? '❤️' : '🤍' }}
								</span>
								<small class="text-muted like-count">{{ $likeCount }}</small>
							</button>
						</div>
						<div class="mt-2">
							<small class="text-secondary">Stok: {{ $pivotData->stock }}</small>
						</div>
					</div>
					<form action="/keranjang/tambah" method="POST">
						@csrf
						<input type="hidden" name="product_id" value="{{ $p->id }}">
						<button type="submit" class="btn btn-keranjang w-100">🛒 + Keranjang</button>
					</form>
				</div>
			</div>
		@empty
			<div class="col-12 text-center py-5">
				<p class="text-muted">Belum ada produk favorit di lokasi ini.</p>
				<a href="/home" class="btn btn-outline-secondary btn-sm">Cari roti dan tekan ❤️ di Beranda</a>
			</div>
		@endforelse
	</div>
@else
	<div class="text-center py-5">
		<img src="https://cdn-icons-png.flaticon.com/512/1048/1048329.png" width="100" class="mb-3 opacity-50">
		<h6 class="text-muted">Silakan pilih lokasi outlet terlebih dahulu di Beranda untuk melihat produk favorit.</h6>
		<a href="/home" class="btn btn-outline-secondary btn-sm mt-2">Pergi ke Beranda</a>
	</div>
@endif

<!-- AJAX Like Toggle Script -->
<script>
	document.addEventListener('DOMContentLoaded', function() {
		// Handle like button click
		document.querySelectorAll('.like-btn').forEach(function(btn) {
			btn.addEventListener('click', function(e) {
				e.preventDefault();
				
				const productId = this.getAttribute('data-product-id');
				const heartIcon = this.querySelector('.heart-icon');
				const likeCountEl = this.querySelector('.like-count');
				const isLiked = this.getAttribute('data-liked') === '1';
				const card = this.closest('.card');
				
				// Send AJAX request
				fetch('/like/toggle', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						'X-CSRF-TOKEN': '{{ csrf_token() }}',
						'X-Requested-With': 'XMLHttpRequest'
					},
					body: JSON.stringify({
						product_id: productId
					})
				})
				.then(response => response.json())
				.then(data => {
					if (data.success) {
						// Update heart icon
						if (data.liked) {
							heartIcon.textContent = '❤️';
							heartIcon.style.color = '#dc3545';
							btn.setAttribute('data-liked', '1');
						} else {
							heartIcon.textContent = '🤍';
							heartIcon.style.color = '#6c757d';
							btn.setAttribute('data-liked', '0');
							
							// Optional: Remove card with animation if unliked on productlike page
							if (window.location.pathname === '/productlike') {
								card.style.transition = 'opacity 0.3s';
								card.style.opacity = '0';
								setTimeout(() => {
									card.closest('.col-md-3').remove();
									
									// Check if no more products
									if (document.querySelectorAll('.product-card').length === 0) {
										location.reload();
									}
								}, 300);
							}
						}
						
						// Update like count
						likeCountEl.textContent = data.likeCount;
					}
				})
				.catch(error => {
					console.error('Error:', error);
				});
			});
		});
	});
</script>

@endsection
