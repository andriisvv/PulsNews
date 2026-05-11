@php $item = $news ?? null; @endphp

{{-- Помилки валідації --}}
@if($errors->any())
    <div class="alert alert--error" style="margin-bottom: 20px;">
        <i class="ti ti-alert-circle"></i>
        <div>
            <strong>Помилки у формі:</strong>
            <ul style="margin: 4px 0 0 18px; padding: 0;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="form-grid">

    {{-- ЛІВА КОЛОНКА — основні поля --}}
    <div class="form-main">

        <div class="form-group">
            <label for="title">Заголовок *</label>
            <input type="text" id="title" name="title" required
                   value="{{ old('title', $item->title ?? '') }}"
                   placeholder="Введіть заголовок новини">
            @error('title') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="excerpt">Короткий опис</label>
            <textarea id="excerpt" name="excerpt" rows="3"
                      placeholder="Стислий опис, який буде показано на головній">{{ old('excerpt', $item->excerpt ?? '') }}</textarea>
            @error('excerpt') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="content">Повний текст *</label>
            <textarea id="content" name="content" rows="14" required
                      placeholder="Текст новини...">{{ old('content', $item->content ?? '') }}</textarea>
            @error('content') <span class="form-error">{{ $message }}</span> @enderror
        </div>

    </div>

    {{-- ПРАВА КОЛОНКА — метадані --}}
    <aside class="form-side">

        <div class="form-group">
            <label for="category">Категорія *</label>
            <input type="text" id="category" name="category" required
                   value="{{ old('category', $item->category ?? '') }}"
                   placeholder="Світ, Tech, Бізнес..."
                   list="categories">
            <datalist id="categories">
                <option value="Світ">
                <option value="Технології">
                <option value="Економіка">
                <option value="Культура">
                <option value="Спорт">
                <option value="Здоровʼя">
            </datalist>
            @error('category') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="author">Автор</label>
            <input type="text" id="author" name="author"
                   value="{{ old('author', $item->author ?? '') }}"
                   placeholder="Імʼя автора">
            @error('author') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="image_file">Завантажити з компʼютера</label>
            <input type="file" id="image_file" name="image_file" accept="image/jpeg,image/png,image/webp">
            <small class="form-hint">JPG, PNG, WEBP. Макс. 5 МБ.</small>
            @error('image_file') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div style="text-align: center; color: var(--text-tertiary); font-size: 12px; padding: 4px 0;">
            — або —
        </div>

        <div class="form-group">
            <label for="image_url">URL зображення</label>
            <input type="url" id="image_url" name="image_url"
                   value="{{ old('image_url', $item->image_url ?? '') }}"
                   placeholder="https://...">
            <small class="form-hint">Підказка: можна взяти з <a href="https://picsum.photos" target="_blank">picsum.photos</a> (наприклад https://picsum.photos/800/500)</small>
            @error('image_url') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        @if($item && $item->image_url)
            <div class="form-group">
                <small class="form-hint">Поточне зображення:</small>
                <img src="{{ $item->image_url }}" alt="" style="width: 100%; border-radius: 8px; margin-top: 4px;">
            </div>
        @endif

        <div class="form-divider"></div>

        <label class="form-check">
            <input type="checkbox" name="is_published" value="1"
                {{ old('is_published', $item->is_published ?? true) ? 'checked' : '' }}>
            <span>Опублікувати</span>
        </label>

        <label class="form-check">
            <input type="checkbox" name="is_featured" value="1"
                {{ old('is_featured', $item->is_featured ?? false) ? 'checked' : '' }}>
            <span>Зробити головною (Featured)</span>
        </label>

    </aside>

</div>