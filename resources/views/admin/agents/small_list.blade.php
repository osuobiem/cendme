@foreach ($agents as $agent)
    <tr>
            <td>{{ strlen($agent->firstname) < 1 && strlen($agent->lastname) < 1 ? $agent->email : $agent->firstname.' '.$agent->lastname }}</td>
      <td>{{ date('d/m/Y', strtotime($agent->created_at)) }}</td>
    </tr>
@endforeach