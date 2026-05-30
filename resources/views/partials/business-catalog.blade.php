<div class="catalog-grid">
    @forelse ($businesses as $business)
        @php
            $firstService = $business->services->first();
            $coverImage = $firstService?->galleryImageUrls()[0] ?? 'https://images.unsplash.com/photo-1521590832167-7bcbfaa6381f?auto=format&fit=crop&w=1200&q=80';
            $rating = number_format((float) ($business->reviews_avg_rating ?? 0), 1);
            $typeKey = \Illuminate\Support\Str::slug((string) $business->type);
        @endphp
        <article
            class="business-market-card"
            x-show="activeType === 'all' || activeType === '{{ $typeKey }}'"
            x-transition
        >
            <a class="business-market-card__media" href="{{ route('public.businesses.show', $business) }}">
                <img src="{{ $coverImage }}" alt="Portada de {{ $business->name }}" loading="lazy">
            </a>
            <div class="business-market-card__body">
                <div class="row-between">
                    <div>
                        <span class="type-pill">{{ $business->type }}</span>
                        <h3 class="font-display mt-2 text-2xl font-semibold">{{ $business->name }}</h3>
                    </div>
                    <div class="business-market-card__rating">
                        <x-heroicon-s-star class="h-4 w-4 text-amber-400" />
                        <span>{{ $rating }}</span>
                        <small>({{ $business->reviews_count }})</small>
                    </div>
                </div>

                <p class="muted mt-2 text-sm">
                    {{ $business->address ?: 'Direccion visible dentro de la pagina publica del negocio.' }}
                </p>

                <div class="list mt-4 gap-2">
                    @forelse ($business->services->take(4) as $service)
                        <div class="business-market-card__service">
                            <strong>{{ $service->name }}</strong>
                            <span>{{ $service->duration_minutes }} min · ${{ number_format((float) $service->price, 0) }}</span>
                        </div>
                    @empty
                        <div class="business-market-card__service">
                            <strong>Sin servicios activos</strong>
                            <span>Este negocio todavia no publico tratamientos.</span>
                        </div>
                    @endforelse
                </div>

                <div class="actions mt-4">
                    <a class="btn btn-primary text-sm" href="{{ route('public.businesses.show', $business) }}">Ver negocio</a>
                    <a class="btn btn-secondary text-sm" href="{{ route('public.businesses.show', $business) }}#reviews">Ver reseñas</a>
                </div>
            </div>
        </article>
    @empty
        <div class="empty-state col-span-full">
            Todavia no hay negocios registrados para mostrar.
        </div>
    @endforelse
</div>
