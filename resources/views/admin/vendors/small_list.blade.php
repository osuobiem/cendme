@foreach ($vendors as $vendor)
    <tr>
      <td>{{ $vendor->business_name }}</td>
      <td>{{ date('d/m/Y', strtotime($vendor->created_at)) }}</td>
    </tr>
@endforeach