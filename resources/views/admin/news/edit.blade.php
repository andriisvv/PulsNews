@extends('admin.layout')

@section('title', 'Редагування новини')

@section('content')

    <div class="page-header">
        <div>
            <a href="{{ route('admin.news.index') }}" class="back-link">
                <i class="ti ti-arrow-left"></i> Назад до списку
            </a>
            <h1 class="page-title">Редагування новини</h1>
        </div>
    </div>

   <form action="{{ route('admin.news.update', $news) }}" method="POST" class="news-form" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.news.form')
        <div class="form-actions">
            <a href="{{ route('admin.news.index') }}" class="btn btn--ghost">Скасувати</a>
            <button type="submit" class="btn btn--primary">
                <i class="ti ti-check"></i> Зберегти зміни
            </button>
        </div>
    </form>

@endsection