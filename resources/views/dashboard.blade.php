@extends('layout.app')

@section('content')

<div class="pcoded-content">

<div class="pcoded-inner-content">
<div class="main-body">
<div class="page-wrapper">
<div class="page-body">
  <div class="row">

  <div style="margin: 0 auto; text-align: center;">
    <div class='time' id="time"></div>
    <div class="date" id="date"></div>

    <div class="welcome">
      Good Morning {{$user}}
    </div>
  </div>
</div>
</div>
</div>
</div>
</div>
</div>

<div id="styleSelector">
</div>

</div>
</div>
</div>
</div>

@endsection

