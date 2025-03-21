@extends('layout', ['title'=> 'Home'])
@section('page-content')

<div class="col-md-6 col-sm-12 mx-auto">
    <div class="card teacher-card shadow-lg p-3 mb-5 bg-white rounded">
        <div class="card-body text-center">
            <!-- Profile Picture -->
            <div class="mb-3">
                <img src="{{ asset('uploads/teacherprofile/'.$item->profile_picture) }}" 
                     class="rounded-circle border border-3" 
                     width="120px" height="120px" alt="Profile Picture">
            </div>

            <!-- Teacher Info -->
            <h4 class="card-title text-primary">{{ $item->name }}</h4>
            <p class="text-muted"><i class="fas fa-university"></i> {{ $item->university_name }}</p>
            <p class="text-muted"><i class="fas fa-graduation-cap"></i> {{ $item->department_name }}</p>

            <!-- Average Rating -->
            <div class="mb-2">
                <p class="mb-1"><strong></strong> 
                    @php
                        $number = 0; $str = 0;
                        if($item->star_count > 0){
                            $str = ($item->total_star / $item->star_count);
                            $number = ceil($str);
                        }
                        echo(round($str, 1));
                    @endphp
                </p>
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $number)
                        <span class="fa fa-star checked text-warning"></span> 
                    @else
                        <span class="fa fa-star text-secondary"></span> 
                    @endif
                @endfor
            </div>

            <!-- Contact & Social Media -->
            <div class="mt-3">
                <p><i class="fas fa-envelope"></i> <a href="mailto:{{ $item->email }}" class="text-dark">{{ $item->email }}</a></p>
                <p><i class="fas fa-phone"></i> <a href="tel:{{ $item->phone_number }}" class="text-dark">{{ $item->phone_number }}</a></p>
                @if ($item->social_media_link)
                    <p><i class="fab fa-facebook"></i> <a href="{{ $item->social_media_link }}" target="_blank" class="text-primary">Visit Profile</a></p>
                @endif
            </div>

            <!-- Check if User Can Rate -->
            @if(auth()->check() && !$item->voters->contains(auth()->id()))
                <form action="{{ route('teachers.rate', $item->id) }}" method="POST">
                    @csrf
                    <label for="rating">Rate this teacher:</label>
                    <div id="rating">
                        @for ($i = 1; $i <= 5; $i++)
                            <label>
                                <input type="radio" name="star" value="{{ $i }}" style="display: none;">
                                <i class="fa fa-star"
                                onclick="this.closest('form').submit()"
                                style="cursor: pointer; color: gray;"
                                onmouseover="this.style.color='gold'"
                                onmouseout="this.style.color='gray'"></i>
                            </label>
                        @endfor
                    </div>
                </form>
            @else
                @if(auth()->check())
                    <p>You have already rated this teacher.</p>
                @else
                    <p><a href="{{ route('login') }}">Login</a> to rate this teacher.</p>
                @endif
            @endif
        </div>
    </div>
</div>

@endsection
