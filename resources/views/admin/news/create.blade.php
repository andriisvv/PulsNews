@extends('admin.layout')

@section('title', 'Нова новина')

@section('content')

    <div class="page-header">
        <div>
            <a href="{{ route('admin.news.index') }}" class="back-link">
                <i class="ti ti-arrow-left"></i> Назад до списку
            </a>
            <h1 class="page-title">Нова новина</h1>
        </div>
    </div>

   <form action="{{ route('admin.news.store') }}" method="POST" class="news-form" enctype="multipart/form-data">
        @csrf
        @include('admin.news.form')
        <div class="form-actions">
            <a href="{{ route('admin.news.index') }}" class="btn btn--ghost">Скасувати</a>
            <button type="submit" class="btn btn--primary">
                <i class="ti ti-check"></i> Зберегти
            </button>
        </div>
    </form>

@endsection