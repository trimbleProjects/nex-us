<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>

        <div class='card'>
            <div class='container'>

                @foreach($nexus as $key)
                @foreach($states as $state => $val)
                    <div>
                        <h3>{{$state}}</h3>
                        <div>{{$state}} Total Sales: ${{$key->{$state . '_total_sales'} }}</div>
                        <div>{{$state}} Number of Sales: {{$key->{ "{$state}_num_of_sales" } }}</div>
                    </div>
                
                @endforeach
                @endforeach
            </div>
        </div>
    </body>
</html>