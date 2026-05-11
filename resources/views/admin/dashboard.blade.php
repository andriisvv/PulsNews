@extends('admin.layout')

@section('title', 'Дашборд')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Дашборд</h1>
            <p class="page-subtitle">Огляд активності та статистика новин</p>
        </div>
        <a href="{{ route('admin.news.create') }}" class="btn btn--primary">
            <i class="ti ti-plus"></i> Нова новина
        </a>
    </div>

    {{-- Статистика --}}
    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-label">Усього новин</span>
            <span class="stat-value">{{ $stats['total'] }}</span>
        </div>
        <div class="stat-card stat-card--green">
            <span class="stat-label">Опубліковано</span>
            <span class="stat-value">{{ $stats['published'] }}</span>
        </div>
        <div class="stat-card stat-card--amber">
            <span class="stat-label">Чернетки</span>
            <span class="stat-value">{{ $stats['draft'] }}</span>
        </div>
        <div class="stat-card stat-card--purple">
            <span class="stat-label">Featured</span>
            <span class="stat-value">{{ $stats['featured'] }}</span>
        </div>
        <div class="stat-card stat-card--blue">
            <span class="stat-label">Створено вручну</span>
            <span class="stat-value">{{ $stats['manual'] }}</span>
        </div>
        <div class="stat-card stat-card--pink">
            <span class="stat-label">З API</span>
            <span class="stat-value">{{ $stats['api'] }}</span>
        </div>
    </div>

    {{-- Останні новини --}}
    <section class="content-section">
        <h2 class="section-title">Останні створені</h2>

        @if($latest->count())
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Заголовок</th>
                            <th>Категорія</th>
                            <th>Статус</th>
                            <th>Дата</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($latest as $item)
                            <tr>
                                <td>{{ Str::limit($item->title, 60) }}</td>
                                <td><span class="chip">{{ $item->category }}</span></td>
                                <td>
                                    @if($item->is_published)
                                        <span class="status status--published">Опубліковано</span>
                                    @else
                                        <span class="status status--draft">Чернетка</span>
                                    @endif
                                </td>
                                <td>{{ $item->created_at->format('d.m.Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.news.edit', $item) }}" class="btn-icon">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="empty-text">Поки що немає новин.</p>
        @endif
    </section>

@endsection