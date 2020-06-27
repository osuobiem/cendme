@foreach ($categories as $category)
    <option value="{{ base64_encode($category->id) }}">{{ $category->name }}</option>
@endforeach