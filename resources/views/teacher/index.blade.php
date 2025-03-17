@extends('layout', ['title'=> 'Home'])
@section('page-content')

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
            <div href="{{route('teacher.create')}}" class="col-md-4 col-sm-6">
                <div class="card teacher-card">
                    <div class="card-body">
                        <h5 class="card-title">Name: {{ $item->name }}</h5>
                        <p class="card-text"><strong>University: </strong> {{ $item->university_name }}</p>
                        <p class="card-text"><strong>Department:</strong> {{ $item->department_name }}</p>
                        <img src="{{ asset('uploads/teacherprofile/'.$item->profile_picture)}}" width="100px" height="70px" alt="Image">
                        <!-- Display Average Rating -->
                        <p>Average Rating:
                            @php
                                $number = 0; $str = 0;
                                if($item->star_count > 0){
                                    $str = ($item->total_star / $item->star_count);
                                    $number = ceil($str); // Calculate the rounded average
                                }
                                echo(round($str, 1));
                            @endphp
                            <br>
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $number)
                                    <span class="fa fa-star checked" style="color: gold;"></span> <!-- Filled star -->
                                @else
                                    <span class="fa fa-star" style="color: gray;"></span> <!-- Empty star -->
                                @endif
                            @endfor
                        </p>

                        <a class="btn btn-primary" href="{{route('teacher.profile', $item->id)}}" >View Profile</a><br>

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