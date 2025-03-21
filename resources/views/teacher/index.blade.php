@extends('layout', ['title'=> 'Home'])
@section('page-content')

{{-- Nevigation Bar Start --}}
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="{{route('teacher.index')}}">Home <span class="sr-only">(current)</span></a>
        </li>
      </ul>

        @guest
            <a class="btn " href="">Enroll as a teacher or rate? Login first! <span class="sr-only">(current)</span></a>
            <a href="{{route('login')}}" class="btn primary">Login</a>
            <a href="{{route('register')}}" class="btn primary">Register</a>
        @else
            @php
                $isUserPresent = $teachers->pluck('user_teacher_id')->contains(auth()->id());
                $teacherID = $teachers->firstWhere('user_teacher_id', auth()->id())?->id;
            @endphp
            @if ($isUserPresent)
                {{-- <a class="btn success" href="{{route('schedules.create', $teacherID)}}">Set Event</a> --}}
                <a class="btn success" href="{{route('teacher.edit', $teacherID)}}">Update Profile - {{ Auth::user()->name }} <span class="sr-only">(current)</span></a>
            @else
                <a class="btn success" href="{{route('teacher.create')}}">Enroll as a Teacher - {{ Auth::user()->name }} <span class="sr-only">(current)</span></a>
            @endif
            <!-- Authentication -->
            {{-- <div>{{ Auth::user()->name }}</div> --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <x-dropdown-link :href="route('logout')"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-dropdown-link>
            </form>
        @endguest

    </div>

</nav>
{{-- Nevigayion Bar End --}}

<!-- Filter Form -->
<form method="GET" action="{{ route('teacher.index') }}" class="mb-4">
    <div class="row">
        <!-- University Filter -->
        <div class="col-md-5">
            <label for="university_name">University</label>
            <select name="university_name" id="university_name" class="form-control">
                <option value="">-- Select University --</option>
                @foreach ($universities as $uni)
                    <option value="{{ $uni }}" {{ $uni == $university ? 'selected' : '' }}>{{ $uni }}</option>
                @endforeach
            </select>
        </div>

        <!-- Department Filter -->
        <div class="col-md-5">
            <label for="department_name">Department</label>
            <select name="department_name" id="department_name" class="form-control">
                <option value="">-- Select Department --</option>
                @foreach ($departments as $dept)
                    <option value="{{ $dept }}" {{ $dept == $department ? 'selected' : '' }}>{{ $dept }}</option>
                @endforeach
            </select>
        </div>

        <!-- Filter Button -->
        <div class="col-md-2">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-primary btn-block">Filter</button>
        </div>
    </div>
</form>
{{-- End Filter form --}}

<div class="container mt-5">
    <h1 class="text-center mb-4">Teachers List</h1>
    <div class="row g-4">
        @foreach($teachers as $item)
    <div class="col-md-4 col-sm-6 mb-4">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
            <!-- Profile Picture -->
            <div class="flex justify-center p-4">
                <img src="{{ asset('uploads/teacherprofile/'.$item->profile_picture) }}" alt="Profile Picture" class="w-32 h-32 rounded-full object-cover border-4 border-blue-500">
            </div>
            <!-- Name -->
            <h5 class="text-xl font-bold text-center text-gray-800 px-4">{{ $item->name }}</h5>
            <div class="text-center">
                <!-- University -->
                <p class="text-muted"><i class="fas fa-university"></i> {{ $item->university_name }}</p>
                <!-- Department -->
                <p class="text-muted"><i class="fas fa-graduation-cap"></i> {{ $item->department_name }}</p>  
            </div>
            <!-- Average Rating -->
            <div class="text-center">
                @php
                    $number = 0; $str = 0;
                    if($item->star_count > 0){
                        $str = ($item->total_star / $item->star_count);
                        $number = ceil($str); // Calculate the rounded average
                    }
                    echo number_format($str, 1); // Always shows 1 digit after the decimal point
                @endphp
                
                <div class="flex justify-center">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= $number)
                            <span class="text-yellow-400">★</span> <!-- Filled star -->
                        @else
                            <span class="text-gray-300">★</span> <!-- Empty star -->
                        @endif
                    @endfor
                </div>
            </div>

            <!-- View Profile Button -->
            <div class="text-center p-4">
                <a href="{{ route('teacher.profile', $item->id) }}" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    View Profile
                </a>
            </div>
        </div>
    </div>
@endforeach
    </div>

    {{-- <div class="d-flex justify-content-center mt-4">
        {{ $teachers->appends(['sort_field' => $sortField, 'sort_direction' => $sortDirection])}}
    </div> --}}
</div>

@endsection