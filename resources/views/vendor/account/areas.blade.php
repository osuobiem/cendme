@foreach($areas as $area)
<option value="{{ $area->id }}" {{ $area->id == Auth::user()->area->id ? 'selected' : '' }}>
  {{ $area->name }}
</option>
@endforeach