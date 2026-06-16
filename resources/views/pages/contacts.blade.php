@extends('layouts.app')

@section('title', 'Контакти — Pulse')

@section('content')

    <section class="page-section">
        <a href="{{ route('home') }}" class="page-back">
            <i class="ti ti-arrow-left"></i> На головну
        </a>

        <span class="badge badge--primary">КОНТАКТИ</span>
        <h1 class="page-title">Звʼяжіться з нами</h1>

        <div class="page-content">
            <p>
                Маєте запитання, пропозицію щодо співпраці або хочете повідомити про
                новину? Заповніть форму нижче, і ми відповімо вам найближчим часом.
            </p>

            <div class="contact-layout">

                {{-- Контактна інформація --}}
                <div class="contact-info">
                    <div class="contact-info-item">
                        <i class="ti ti-mail"></i>
                        <div>
                            <span class="contact-info-label">Email</span>
                            <span class="contact-info-value">info@pulsnews.com</span>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <i class="ti ti-phone"></i>
                        <div>
                            <span class="contact-info-label">Телефон</span>
                            <span class="contact-info-value">+38 (0XX) XXX-XX-XX</span>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <i class="ti ti-map-pin"></i>
                        <div>
                            <span class="contact-info-label">Адреса</span>
                            <span class="contact-info-value">Україна, м. Київ</span>
                        </div>
                    </div>
                </div>

                {{-- Форма зворотного звʼязку --}}
                <form action="{{ route('contacts.send') }}" method="POST" class="contact-form">
                    @csrf

                    @if(session('success'))
                        <div class="alert alert--success">
                            <i class="ti ti-circle-check"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert--error">
                            <i class="ti ti-alert-circle"></i>
                            Будь ласка, перевірте правильність заповнення форми.
                        </div>
                    @endif

                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Імʼя <span>*</span></label>
                            <input type="text" id="name" name="name"
                                   value="{{ old('name') }}" required
                                   class="form-input" placeholder="Ваше імʼя">
                        </div>
                        <div class="form-group">
                            <label for="email">Email <span>*</span></label>
                            <input type="email" id="email" name="email"
                                   value="{{ old('email') }}" required
                                   class="form-input" placeholder="email@example.com">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="subject">Тема</label>
                        <input type="text" id="subject" name="subject"
                               value="{{ old('subject') }}"
                               class="form-input" placeholder="Тема звернення">
                    </div>

                    <div class="form-group">
                        <label for="message">Повідомлення <span>*</span></label>
                        <textarea id="message" name="message" rows="6" required
                                  class="form-input" placeholder="Текст вашого повідомлення...">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn--primary">
                        Надіслати <i class="ti ti-send"></i>
                    </button>
                </form>

            </div>
        </div>
    </section>

@endsection
