<select class="form-select form-select-sm" name="article_id" required>
    <option value="">Не выбрано</option>
    @foreach($articles as $article)
        <option value="{{ $article['id'] }}">{{ $article['article'] }}</option>
    @endforeach
</select>
