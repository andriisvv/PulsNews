@extends('layouts.app')

@section('title', $article->title . ' — Pulse')

@section('content')

    <article class="article">

        <a href="{{ route('home') }}" class="article-back">
            <i class="ti ti-arrow-left"></i> Назад
        </a>

        <div class="article-header">
            <span class="badge badge--category">{{ strtoupper($article->category) }}</span>
            <h1 class="article-title">{{ $article->title }}</h1>
            <p class="article-excerpt">{{ $article->excerpt }}</p>

            <div class="article-meta">
                @if($article->author)
                    <span><i class="ti ti-user"></i> {{ $article->author }}</span>
                @endif
                <span><i class="ti ti-calendar"></i> {{ $article->published_at->format('d.m.Y') }}</span>
                @if($article->source !== 'manual')
                    <span><i class="ti ti-external-link"></i> {{ $article->source }}</span>
                @endif
            </div>
        </div>

        @if($article->image_url)
            <div class="article-image">
                <img src="{{ $article->image_url }}" alt="{{ $article->title }}">
            </div>
        @endif

        <div class="article-content">
            {!! nl2br(e($article->content)) !!}
        </div>

        @if($article->external_url)
            <a href="{{ $article->external_url }}" target="_blank" class="btn btn--ghost">
                Читати оригінал <i class="ti ti-external-link"></i>
            </a>
        @endif

    </article>

    {{-- Похожі новини --}}
    @if($related->count() > 0)
        <section class="related">
            <h2 class="section-title">Схожі новини</h2>
            <div class="cards-grid">
                @foreach($related as $item)
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
                            <span class="card-category card-category--{{ $color }}">{{ strtoupper($item->category) }}</span>
                            <h3 class="card-title">{{ $item->title }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

@endsection