@foreach ($agents as $agent)
<tr>
  <td>{{ $agent->firstname }}</td>
  <td>{{ $agent->lastname }}</td>
  <td>{{ $agent->email }}</td>
  <td>{{ $agent->phone }}</td>
  <td class="{{ $agent->verified ? 'text-success' : 'text-danger' }}">{{ $agent->verified ? 'Yes' : 'No' }}</td>
  <td>{{ date('d/m/Y', strtotime($agent->created_at)) }}</td>
  <td>
    <button class="btn btn-sm btn-outline-success" data-target="#view{{ $agent->id }}" data-toggle="modal" title="View Shopper"><i class="fas fa-eye"></i></button>
    <button class="btn btn-sm btn-outline-danger" title="Delete Shopper" onclick="deleteWarn({{ $agent->id }})"><i class="fas fa-trash"></i></button>
  </td>
</tr>
@endforeach