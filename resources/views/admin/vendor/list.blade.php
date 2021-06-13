@foreach ($vendors as $vendor)
<tr>
  <td>{{ $vendor->business_name }}</td>
  <td>{{ $vendor->email }}</td>
  <td>{{ $vendor->phone }}</td>
  <td>{{ date('d/m/Y', strtotime($vendor->created_at)) }}</td>
  <td>
    <button class="btn btn-sm btn-outline-primary" data-target="#supermarket{{ $vendor->id }}" data-toggle="modal" title="View Assigned Shoppers"><i class="fas fa-user"></i></button>
    <button class="btn btn-sm btn-outline-success" data-target="#view{{ $vendor->id }}" data-toggle="modal" title="View Vendor"><i class="fas fa-eye"></i></button>
    <button class="btn btn-sm btn-outline-danger" title="Delete Vendor" onclick="deleteWarn({{ $vendor->id }})"><i class="fas fa-trash"></i></button>
  </td>
</tr>
@endforeach