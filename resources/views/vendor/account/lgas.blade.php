@foreach($lgas as $lga)
<option value="{{ $lga->id }}" {{ $lga->id == Auth::user()->lga->id ? 'selected' : '' }}>
  {{ $lga->name }}
</option>
@endforeach