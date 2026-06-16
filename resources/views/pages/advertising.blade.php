@extends('layouts.app')

@section('title', 'Реклама — Pulse')

@section('content')

    <section class="page-section">
        <a href="{{ route('home') }}" class="page-back">
            <i class="ti ti-arrow-left"></i> На головну
        </a>

        <span class="badge badge--primary">РЕКЛАМА</span>
        <h1 class="page-title">Розмістіть рекламу на Pulse</h1>

        <div class="page-content">
            <p>
                Pulse — це аудиторія читачів, які щодня стежать за актуальними подіями.
                Ми пропонуємо рекламодавцям ефективні формати співпраці для просування
                товарів, послуг і брендів серед зацікавленої аудиторії.
            </p>

            <h2 class="page-subtitle">Формати розміщення</h2>
            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-icon"><i class="ti ti-layout-board"></i></div>
                    <h3>Банерна реклама</h3>
                    <p>Розміщення банерів у стрічці новин та на сторінках статей.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="ti ti-article"></i></div>
                    <h3>Нативні публікації</h3>
                    <p>Рекламні матеріали у форматі статей з позначкою «Партнерський матеріал».</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="ti ti-star"></i></div>
                    <h3>Featured-розміщення</h3>
                    <p>Пріоритетне розміщення у головному блоці на першій сторінці.</p>
                </div>
            </div>

            <h2 class="page-subtitle">Умови співпраці</h2>
            <p>
                Вартість і умови розміщення розраховуються індивідуально залежно від
                формату, тривалості кампанії та обраних категорій. Для отримання
                комерційної пропозиції та медіакіту звʼяжіться з нами через
                <a href="{{ route('contacts') }}">сторінку контактів</a>.
            </p>
        </div>
    </section>

@endsection
