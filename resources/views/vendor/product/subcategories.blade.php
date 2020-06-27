@foreach ($subcategories as $subcategory)
    <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
@endforeach