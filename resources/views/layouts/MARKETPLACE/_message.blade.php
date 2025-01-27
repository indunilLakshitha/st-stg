<div class="clear-both"></div>

@if(!empty(session('success')))
  <div class="alert alert-success alert-dismissible" role="alert">
      {{ session('success') }}
  </div>
@endif

@if(!empty(session('error')))
  <div class="alert alert-danger alert-dismissible" role="alert">
      {{ session('error') }}
  </div>
@endif

@if(!empty(session('payment-error')))
  <div class="alert alert-error alert-dismissible" role="alert">
      {{ session('payment-error') }}
  </div>
@endif

@if(!empty(session('warning')))
  <div class="alert alert-warning alert-dismissible" role="alert">
      {{ session('warning') }}
  </div>
@endif