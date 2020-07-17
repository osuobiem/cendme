@foreach ($vendors as $vendor)
<tr>
  <td>{{ $vendor->business_name }}</td>
  <td>{{ $vendor->email }}</td>
  <td>{{ $vendor->phone }}</td>
  <td>{{ date('d/m/Y', strtotime($vendor->created_at)) }}</td>
  <td>
    <button class="btn btn-sm btn-outline-success" data-target="#view{{ $vendor->id }}" data-toggle="modal" title="View Vendor"><i class="fas fa-eye"></i></button>
    <button class="btn btn-sm btn-outline-primary" title="Update Vendor" data-toggle="modal" data-target="#update{{ $vendor->id }}-modal"><i class="fas fa-edit"></i></button>
    <button class="btn btn-sm btn-outline-danger" title="Delete Vendor" onclick="deleteWarn({{ $vendor->id }})"><i class="fas fa-trash"></i></button>
  </td>
</tr>
@endforeach