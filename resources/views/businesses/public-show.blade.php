@extends('layouts.app')

@section('title', $business->name)
@section('theme_style', '--primary-color: '.$business->brandColor().';')
@section('hide_header')
@section('hide_flash')
@section('main_class', '')
@section('container_class', '')

@section('content')
    <div class="landing-shell">
        <section class="landing-hero">
            <div
                class="landing-hero__bg"
                style="background-image: url('https://images.unsplash.com/photo-1540555700478-4be289fbbe4f?auto=format&fit=crop&w=1600&q=80');"
                role="img"
                aria-label="Ambiente de spa"
            ></div>
            <div class="landing-hero__overlay"></div>
            <div class="landing-hero__content">
                <span class="eyebrow border border-white/20 bg-white/10 text-[var(--landing-cream)]">
                    {{ $business->type }}
                </span>
                <h1 class="landing-hero__title">{{ $business->name }}</h1>
                <p class="mt-4 max-w-lg text-sm leading-relaxed text-stone-300 md:text-base">
                    Reserva tu experiencia con un estilo premium. Servicios activos, horarios y contacto en un solo lugar.
                </p>
                <div class="flex flex-wrap gap-3">
                    <a
                        href="{{ auth()->check() ? route('appointments.create', ['business' => $business->slug, 'from' => 'public']) : route('login') }}"
                        class="landing-pill-btn no-underline"
                    >
                        <x-heroicon-o-calendar-days class="h-5 w-5" />
                        Reservar cita
                    </a>
                    <a
                        href="{{ route('public.businesses.index') }}"
                        class="inline-flex items-center gap-2 rounded-full border border-white/25 px-6 py-3 text-sm font-semibold text-white no-underline transition hover:bg-white/10"
                    >
                        <x-heroicon-o-arrow-left class="h-5 w-5" />
                        Catalogo
                    </a>
                </div>
            </div>
        </section>

        <div class="page-width space-y-8 px-5 py-12">
            <section class="grid gap-4 md:grid-cols-3">
                <article class="landing-card-light">
                    <h2 class="font-display text-2xl font-semibold">Contacto</h2>
                    <ul class="mt-4 space-y-3 text-sm">
                        <li class="flex items-start gap-3">
                            <x-heroicon-o-envelope class="mt-0.5 h-5 w-5 shrink-0 text-[var(--primary-color)]" />
                            <span>{{ $business->email ?: 'Sin registrar' }}</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <x-heroicon-o-phone class="mt-0.5 h-5 w-5 shrink-0 text-[var(--primary-color)]" />
                            <span>{{ $business->phone ?: 'Sin registrar' }}</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <x-heroicon-o-map-pin class="mt-0.5 h-5 w-5 shrink-0 text-[var(--primary-color)]" />
                            <span>{{ $business->address ?: 'Sin registrar' }}</span>
                        </li>
                    </ul>
                </article>

                <article class="landing-card-light md:col-span-2">
                    <h2 class="font-display text-2xl font-semibold">Por que elegirnos</h2>
                    <p class="muted mt-2 text-sm">
                        Atencion personalizada, ambiente relajante y servicios pensados para tu bienestar.
                    </p>
                    <div class="muted mt-3 flex flex-wrap items-center gap-3 text-sm">
                        <span class="inline-flex items-center gap-1 font-semibold">
                            <x-heroicon-s-star class="h-4 w-4 text-amber-500" />
                            {{ number_format((float) ($business->reviews_avg_rating ?? 0), 1) }}
                        </span>
                        <span>{{ $business->reviews_count }} reseñas verificadas</span>
                    </div>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div class="flex gap-3">
                            <span class="landing-feature-icon !border-[var(--primary-color)]/30 !text-[var(--primary-color)]">
                                <x-heroicon-o-sparkles class="h-6 w-6" />
                            </span>
                            <div>
                                <h3 class="font-semibold">Experiencia premium</h3>
                                <p class="muted mt-1 text-sm">Tratamientos seleccionados y espacios disenados para desconectar.</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <span class="landing-feature-icon !border-[var(--primary-color)]/30 !text-[var(--primary-color)]">
                                <x-heroicon-o-clock class="h-6 w-6" />
                            </span>
                            <div>
                                <h3 class="font-semibold">Horarios claros</h3>
                                <p class="muted mt-1 text-sm">Consulta disponibilidad antes de iniciar sesion y reservar.</p>
                            </div>
                        </div>
                    </div>
                </article>
            </section>

            <section x-data="{ openService: null }">
                <div class="mb-6 flex items-end justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold tracking-[0.2em] text-[var(--landing-gold)] uppercase">Servicios</p>
                        <h2 class="font-display text-3xl font-semibold text-white">Tratamientos activos</h2>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    @forelse ($business->services as $service)
                        <article class="landing-card-dark">
                            <button
                                type="button"
                                class="flex w-full items-center justify-between gap-3 text-left"
                                @click="openService = openService === {{ $service->id }} ? null : {{ $service->id }}"
                            >
                                <div>
                                    <p class="text-xs tracking-widest text-stone-400 uppercase">{{ $service->duration_minutes }} min</p>
                                    <h3 class="font-display text-xl font-semibold text-white">{{ $service->name }}</h3>
                                </div>
                                <x-heroicon-o-chevron-down
                                    class="h-5 w-5 shrink-0 text-[var(--landing-gold)] transition"
                                    ::class="openService === {{ $service->id }} ? 'rotate-180' : ''"
                                />
                            </button>
                            <div
                                class="mt-3 text-sm text-stone-300"
                                x-show="openService === {{ $service->id }}"
                                x-transition
                                x-cloak
                            >
                                <p>{{ $service->description ?: 'Servicio activo disponible para reserva dentro de la plataforma.' }}</p>
                                <p class="mt-3 font-semibold text-[var(--landing-cream)]">${{ number_format((float) $service->price, 2) }}</p>

                                @if ($service->galleryImageUrls() !== [])
                                    <div class="mt-4 grid grid-cols-2 gap-2">
                                        @foreach (array_slice($service->galleryImageUrls(), 0, 4) as $imageUrl)
                                            <img
                                                src="{{ $imageUrl }}"
                                                alt="Resultado del servicio {{ $service->name }}"
                                                class="h-28 w-full rounded-lg border border-white/10 object-cover"
                                                loading="lazy"
                                            >
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </article>
                    @empty
                        <div class="landing-card-dark md:col-span-2">
                            <p class="text-stone-400">Este negocio todavia no tiene servicios activos publicados.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <section>
                <h2 class="font-display mb-4 text-2xl font-semibold text-white">Horarios base</h2>
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    @forelse ($business->hours as $hour)
                        <div class="landing-card-dark text-center">
                            <p class="text-xs tracking-widest text-stone-400 uppercase">
                                {{ $dayLabels[$hour->day_of_week] ?? 'Dia '.$hour->day_of_week }}
                            </p>
                            <p class="mt-2 font-semibold text-[var(--landing-cream)]">{{ $hour->opens_at }} - {{ $hour->closes_at }}</p>
                        </div>
                    @empty
                        <div class="landing-card-dark sm:col-span-2 lg:col-span-4">
                            <p class="text-stone-400">No hay horarios activos publicados para este negocio.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <section
                id="reviews"
                x-data="{ openLightbox: false, lightboxSrc: '' }"
            >
                <div class="mb-4 flex items-end justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold tracking-[0.2em] text-[var(--landing-gold)] uppercase">Reseñas</p>
                        <h2 class="font-display text-3xl font-semibold text-white">Experiencias de clientes</h2>
                    </div>
                    <div class="rounded-full border border-white/15 px-4 py-2 text-sm text-stone-300">
                        {{ number_format((float) ($business->reviews_avg_rating ?? 0), 1) }} / 5 · {{ $business->reviews_count }} reseñas
                    </div>
                </div>

                @auth
                    @if ($canReview)
                        <form
                            method="POST"
                            action="{{ route('public.businesses.reviews.store', $business) }}"
                            enctype="multipart/form-data"
                            class="landing-card-dark mb-4 grid gap-3"
                        >
                            @csrf
                            <div class="grid gap-3 sm:grid-cols-3">
                                <label class="text-sm font-semibold text-stone-200 sm:col-span-1">
                                    Calificación
                                    <select name="rating" class="mt-2 w-full rounded-lg border border-white/20 bg-black/20 px-3 py-2 text-stone-100">
                                        @for ($i = 5; $i >= 1; $i--)
                                            <option value="{{ $i }}" @selected((int) old('rating', 5) === $i)>{{ $i }} estrella{{ $i > 1 ? 's' : '' }}</option>
                                        @endfor
                                    </select>
                                </label>
                                <label class="text-sm font-semibold text-stone-200 sm:col-span-2">
                                    Título (opcional)
                                    <input
                                        name="title"
                                        type="text"
                                        value="{{ old('title') }}"
                                        maxlength="120"
                                        class="mt-2 w-full rounded-lg border border-white/20 bg-black/20 px-3 py-2 text-stone-100"
                                    >
                                </label>
                            </div>
                            <label class="text-sm font-semibold text-stone-200">
                                Comentario
                                <textarea
                                    name="comment"
                                    rows="4"
                                    required
                                    class="mt-2 w-full rounded-lg border border-white/20 bg-black/20 px-3 py-2 text-stone-100"
                                >{{ old('comment') }}</textarea>
                            </label>
                            <label class="text-sm font-semibold text-stone-200">
                                Imágenes (opcional)
                                <input
                                    name="images[]"
                                    type="file"
                                    accept=".jpg,.jpeg,.png,.webp,image/*"
                                    multiple
                                    class="mt-2 w-full rounded-lg border border-white/20 bg-black/20 px-3 py-2 text-stone-100"
                                >
                            </label>
                            <button type="submit" class="landing-pill-btn mt-0 w-fit">Publicar reseña</button>
                        </form>
                    @else
                        <div class="landing-card-dark mb-4 text-sm text-stone-300">
                            Completa al menos una cita en este negocio para poder dejar una reseña.
                        </div>
                    @endif
                @else
                    <div class="landing-card-dark mb-4 text-sm text-stone-300">
                        Inicia sesión para publicar tu reseña después de completar una cita.
                    </div>
                @endauth

                <div class="review-carousel-controls">
                    <button
                        type="button"
                        class="review-carousel-btn"
                        @click="$refs.reviewTrack.scrollBy({ left: -360, behavior: 'smooth' })"
                        aria-label="Reseñas anteriores"
                    >
                        <x-heroicon-o-chevron-left class="h-5 w-5" />
                    </button>
                    <button
                        type="button"
                        class="review-carousel-btn"
                        @click="$refs.reviewTrack.scrollBy({ left: 360, behavior: 'smooth' })"
                        aria-label="Siguientes reseñas"
                    >
                        <x-heroicon-o-chevron-right class="h-5 w-5" />
                    </button>
                </div>

                <div class="review-carousel-track" x-ref="reviewTrack">
                    @forelse ($business->reviews as $review)
                        <article class="landing-card-dark review-carousel-card">
                            <div class="mb-2 flex items-center justify-between gap-3">
                                <div>
                                    <p class="m-0 font-semibold text-white">{{ $review->user->name }}</p>
                                    <p class="m-0 text-xs text-stone-400">{{ $review->created_at?->format('Y-m-d') }}</p>
                                </div>
                                <span class="inline-flex items-center gap-1 rounded-full border border-white/20 px-3 py-1 text-xs font-semibold text-[var(--landing-cream)]">
                                    <x-heroicon-s-star class="h-4 w-4 text-amber-400" />
                                    {{ $review->rating }}/5
                                </span>
                            </div>
                            @if ($review->title)
                                <h3 class="mb-1 text-base font-semibold text-white">{{ $review->title }}</h3>
                            @endif
                            <p class="m-0 text-sm leading-relaxed text-stone-300">{{ $review->comment }}</p>

                            @if ($review->imageUrls() !== [])
                                <div class="mt-3 grid grid-cols-2 gap-2">
                                    @foreach ($review->imageUrls() as $imageUrl)
                                        <button
                                            type="button"
                                            class="review-image-button"
                                            @click="lightboxSrc = @js($imageUrl); openLightbox = true"
                                        >
                                            <img
                                                src="{{ $imageUrl }}"
                                                alt="Imagen de reseña de {{ $review->user->name }}"
                                                class="h-24 w-full rounded-lg border border-white/10 object-cover"
                                                loading="lazy"
                                            >
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </article>
                    @empty
                        <div class="landing-card-dark review-carousel-card">
                            <p class="m-0 text-stone-400">Aún no hay reseñas publicadas para este negocio.</p>
                        </div>
                    @endforelse
                </div>

                <div
                    class="review-lightbox"
                    x-show="openLightbox"
                    x-transition
                    x-cloak
                    @click.self="openLightbox = false"
                    @keydown.escape.window="openLightbox = false"
                >
                    <button type="button" class="review-lightbox-close" @click="openLightbox = false" aria-label="Cerrar imagen">
                        <x-heroicon-o-x-mark class="h-6 w-6" />
                    </button>
                    <img :src="lightboxSrc" alt="Imagen ampliada de reseña" class="review-lightbox-image">
                </div>
            </section>

            <footer class="flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-8 pb-10 text-sm text-stone-500 sm:flex-row">
                <p>&copy; {{ date('Y') }} {{ $business->name }}</p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="text-[var(--landing-cream)] no-underline hover:underline">Reservar</a>
                    <x-heroicon-o-heart class="h-5 w-5 text-[var(--landing-gold)]" />
                </div>
            </footer>
        </div>
    </div>

@endsection
