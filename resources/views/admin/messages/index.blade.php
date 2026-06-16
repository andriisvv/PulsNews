@extends('admin.layout')

@section('title', 'Повідомлення')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Повідомлення</h1>
            <p class="page-subtitle">Звернення з форми зворотного звʼязку: {{ $messages->total() }}</p>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 18%;">Відправник</th>
                    <th style="width: 18%;">Email</th>
                    <th>Повідомлення</th>
                    <th>Статус</th>
                    <th>Дата</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $msg)
                    <tr>
                        <td><strong>{{ $msg->name }}</strong></td>
                        <td>{{ $msg->email }}</td>
                        <td>
                            @if($msg->subject)
                                <strong>{{ $msg->subject }}</strong><br>
                            @endif
                            {{ Str::limit($msg->message, 120) }}
                        </td>
                        <td>
                            @if($msg->is_read)
                                <span class="status status--published">Прочитано</span>
                            @else
                                <span class="status status--draft">Нове</span>
                            @endif
                        </td>
                        <td>{{ $msg->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            <div class="actions">
                                @unless($msg->is_read)
                                    <form action="{{ route('admin.messages.read', $msg) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-icon" title="Позначити прочитаним">
                                            <i class="ti ti-check"></i>
                                        </button>
                                    </form>
                                @endunless
                                <form action="{{ route('admin.messages.destroy', $msg) }}"
                                      method="POST"
                                      onsubmit="return confirm('Видалити це повідомлення?');"
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
                        <td colspan="6" class="empty-text">Повідомлень ще немає.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($messages->hasPages())
        <div class="pagination">
            @if($messages->onFirstPage())
                <span class="page-link disabled"><i class="ti ti-chevron-left"></i></span>
            @else
                <a href="{{ $messages->previousPageUrl() }}" class="page-link">
                    <i class="ti ti-chevron-left"></i>
                </a>
            @endif

            <span class="page-info">
                Сторінка {{ $messages->currentPage() }} з {{ $messages->lastPage() }}
            </span>

            @if($messages->hasMorePages())
                <a href="{{ $messages->nextPageUrl() }}" class="page-link">
                    <i class="ti ti-chevron-right"></i>
                </a>
            @else
                <span class="page-link disabled"><i class="ti ti-chevron-right"></i></span>
            @endif
        </div>
    @endif

@endsection
