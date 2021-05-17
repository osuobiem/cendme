<div>
    <p>Click on the link below to reset your <strong>Cendme</strong> password. This link will expire {{ date('d/m/Y, g:i A', time()+86400) }}</p>
    <p><a href="{{ url('reset-password/'.$token) }}">{{ url('reset-password/'.$token) }}</a></p>
</div>