@extends('layouts.app')

@section('title', 'Про нас — Pulse')

@section('content')

    <section class="page-section">
        <a href="{{ route('home') }}" class="page-back">
            <i class="ti ti-arrow-left"></i> На головну
        </a>

        <span class="badge badge--primary">ПРО НАС</span>
        <h1 class="page-title">Pulse — ваш пульс актуальних подій</h1>

        <div class="page-content">
            <p>
                <strong>Pulse</strong> — це сучасний новинний вебпортал, створений для тих,
                хто цінує свій час і хоче отримувати перевірену інформацію швидко та у
                зручному форматі. Ми збираємо найважливіші події зі світу, економіки,
                технологій, культури, спорту та сфери здоровʼя в єдину стрічку.
            </p>
            <p>
                Наша місія — допомагати читачам орієнтуватися в інформаційному потоці.
                Кожен матеріал проходить редакційний відбір, категоризується за тематикою
                та супроводжується візуальним контентом, що робить читання комфортним.
            </p>

            <h2 class="page-subtitle">Наші принципи</h2>
            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-icon"><i class="ti ti-bolt"></i></div>
                    <h3>Оперативність</h3>
                    <p>Свіжі новини зʼявляються у стрічці одразу після публікації редакцією.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="ti ti-checks"></i></div>
                    <h3>Достовірність</h3>
                    <p>Кожен матеріал має зазначене джерело та автора для прозорості.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="ti ti-category"></i></div>
                    <h3>Структурованість</h3>
                    <p>Зручна фільтрація за категоріями та повнотекстовий пошук.</p>
                </div>
            </div>

            <p>
                Pulse постійно розвивається: ми працюємо над автоматичним підключенням
                зовнішніх новинних джерел, щоб стрічка оновлювалася ще оперативніше.
            </p>
        </div>
    </section>

@endsection
