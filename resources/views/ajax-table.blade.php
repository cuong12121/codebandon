<?php 
    $data = json_decode($response, true);

?>

<div>
    
    <table class="table" style="width:100%">
        <thead>
            <tr>
                <th>id</th>
                <th>Model</th>
                
            </tr>
        </thead>
        <tbody>

            @if(!empty($data) && !empty($data['data']))
            @foreach($data['data'] as $key =>$value)
            <tr>
                <td>{{ $key }}</td>
                <td>{{ $value }}</td>
                
            </tr>
            @endforeach
            @endif
           
        </tbody>
    </table>
</div>