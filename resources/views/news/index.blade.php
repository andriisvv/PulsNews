@extends('layouts.app')

@section('title', 'Pulse — Головна')

@section('content')

    {{-- ====== FEATURED HERO ====== --}}
    @if($featured)
        <section class="featured">
            <div class="featured-content">
                <span class="badge badge--primary">FEATURED</span>
                <h1 class="featured-title">{{ $featured->title }}</h1>
                <p class="featured-excerpt">{{ $featured->excerpt }}</p>
                <div class="featured-meta">
                    <span class="meta-item">
                        <i class="ti ti-clock"></i>
                        {{ $featured->published_at->diffForHumans() }}
                    </span>
                    @if($featured->author)
                        <span class="meta-dot"></span>
                        <span class="meta-item">{{ $featured->author }}</span>
                    @endif
                </div>
                <a href="{{ route('news.show', $featured->slug) }}" class="btn btn--primary">
                    Читати далі <i class="ti ti-arrow-right"></i>
                </a>
            </div>
            <div class="featured-image">
                @if($featured->image_url)
                    <img src="{{ $featured->image_url }}" alt="{{ $featured->title }}">
                @else
                    <i class="ti ti-photo"></i>
                @endif
            </div>
        </section>
    @endif

    {{-- ====== NEWS GRID ====== --}}
    @if($news->count() > 0)
        <section class="cards-grid">
            @foreach($news as $item)
                @php
                    $colors = ['green', 'amber', 'blue', 'pink', 'teal', 'purple'];
                    $color = $colors[crc32($item->category) % count($colors)];
                @endphp

                <a href="{{ route('news.show', $item->slug) }}" class="card">
                    <div class="card-image card-image--{{ $color }}">
                        @if($item->image_url)
                            <img src="{{ $item->image_url }}" alt="{{ $item->title }}">
                        @else
                            <i class="ti ti-photo"></i>
                        @endif
                    </div>
                    <div class="card-body">
                        <span class="card-category card-category--{{ $color }}">
                            {{ strtoupper($item->category) }}
                        </span>
                        <h3 class="card-title">{{ $item->title }}</h3>
                        <div class="card-meta">
                            <span><i class="ti ti-clock"></i> {{ $item->published_at->diffForHumans() }}</span>
                            @if($item->author)
                                <span>{{ $item->author }}</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </section>

        {{-- Власна пагінація --}}
        @if($news->hasPages())
            <div class="pagination">
                @if($news->onFirstPage())
                    <span class="page-link disabled"><i class="ti ti-chevron-left"></i></span>
                @else
                    <a href="{{ $news->appends(request()->query())->previousPageUrl() }}" class="page-link">
                        <i class="ti ti-chevron-left"></i>
                    </a>
                @endif

                <span class="page-info">
                    Сторінка {{ $news->currentPage() }} з {{ $news->lastPage() }}
                </span>

                @if($news->hasMorePages())
                    <a href="{{ $news->appends(request()->query())->nextPageUrl() }}" class="page-link">
                        <i class="ti ti-chevron-right"></i>
                    </a>
                @else
                    <span class="page-link disabled"><i class="ti ti-chevron-right"></i></span>
                @endif
            </div>
        @endif
    @else
        <div class="empty-state">
            <i class="ti ti-news-off"></i>
            <p>Новин не знайдено</p>
        </div>
    @endif

@endsection