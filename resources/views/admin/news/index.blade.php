@extends('admin.layout')

@section('title', 'Усі новини')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Усі новини</h1>
            <p class="page-subtitle">Всього записів: {{ $news->total() }}</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <form action="{{ route('admin.news.fetch') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn btn--ghost">
                    <i class="ti ti-refresh"></i> Оновити з джерел
                </button>
            </form>
            <a href="{{ route('admin.news.create') }}" class="btn btn--primary">
                <i class="ti ti-plus"></i> Нова новина
            </a>
        </div>
    </div>

    {{-- Фільтри --}}
    <form action="{{ route('admin.news.index') }}" method="GET" class="filters">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Пошук за заголовком..." class="input">

        <select name="category" class="input">
            <option value="">Усі категорії</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>
                    {{ $cat }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn--ghost">
            <i class="ti ti-search"></i> Шукати
        </button>
        @if(request('search') || request('category'))
            <a href="{{ route('admin.news.index') }}" class="btn btn--ghost">
                <i class="ti ti-x"></i> Скинути
            </a>
        @endif
    </form>

    {{-- Таблиця --}}
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 40%;">Заголовок</th>
                    <th>Категорія</th>
                    <th>Джерело</th>
                    <th>Статус</th>
                    <th>Дата</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($news as $item)
                    <tr>
                        <td>
                            <div class="title-cell">
                                <strong>{{ Str::limit($item->title, 70) }}</strong>
                                @if($item->is_featured)
                                    <span class="badge-mini badge-mini--featured">FEATURED</span>
                                @endif
                            </div>
                        </td>
                        <td><span class="chip">{{ $item->category }}</span></td>
                        <td>
                            @if($item->source === 'manual')
                                <span class="source-tag">Вручну</span>
                            @else
                                <span class="source-tag source-tag--api">{{ Str::limit($item->source, 15) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($item->is_published)
                                <span class="status status--published">Опубліковано</span>
                            @else
                                <span class="status status--draft">Чернетка</span>
                            @endif
                        </td>
                        <td>{{ $item->created_at->format('d.m.Y') }}</td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('news.show', $item->slug) }}" target="_blank"
                                   class="btn-icon" title="Переглянути">
                                    <i class="ti ti-eye"></i>
                                </a>
                                <a href="{{ route('admin.news.edit', $item) }}" class="btn-icon" title="Редагувати">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <form action="{{ route('admin.news.destroy', $item) }}"
                                      method="POST"
                                      onsubmit="return confirm('Видалити цю новину?');"
                                      style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon btn-icon--danger" title="Видалити">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-text">Новин не знайдено.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

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

@endsection