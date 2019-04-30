@extends('layouts.app')

@section('content')

        <div id="app">
            <navbar></navbar> 
           <div class="container"> 
            <articles></articles>  
         </div>      
        </div>
     @auth
  <script>
    window.user = @json(auth()->user())
  </script>
@endauth
@endsection