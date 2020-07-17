@foreach ($users as $user)
    <tr>
      <td>{{ strlen($user->firstname) < 1 && strlen($user->lastname) < 1 ? $user->email : $user->firstname.' '.$user->lastname }}</td>
      <td>{{ date('d/m/Y', strtotime($user->created_at)) }}</td>
    </tr>
@endforeach