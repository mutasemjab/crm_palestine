<footer class="main-footer">
  @php
        $setting = App\Models\Setting::first();
    @endphp
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Anything you want
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2023 <a href="">{{$setting->name}}</a>.</strong> All rights reserved.
  </footer>
