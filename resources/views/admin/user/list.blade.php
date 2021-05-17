@foreach ($users as $user)
<tr>
  <td>{{ $user->firstname }}</td>
  <td>{{ $user->lastname }}</td>
  <td>{{ $user->email }}</td>
  <td>{{ $user->phone }}</td>
  <td>{{ $user->gender }}</td>
  <td>{{ date('d/m/Y', strtotime($user->created_at)) }}</td>
  <td>
    <button class="btn btn-sm btn-outline-success" data-target="#view{{ $user->id }}" data-toggle="modal" title="View User"><i class="fas fa-eye"></i></button>
    <button class="btn btn-sm btn-outline-danger" title="Delete User" onclick="deleteWarn({{ $user->id }})"><i class="fas fa-trash"></i></button>
  </td>
</tr>
@endforeach