<div class="card h-100 event-card event-status-{{ $status ?? 'upcoming' }}">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <h5 class="card-title mb-0">{{ $event->title }}</h5>
            <span class="badge bg-{{ 
                $status == 'running' ? 'primary' : 
                ($status == 'upcoming' ? 'success' : 'secondary') 
            }}">
                {{ ucfirst($status) }}
            </span>
        </div>
        
        <h6 class="card-subtitle mb-3 text-muted">
            <i class="bi bi-person"></i> {{ $event->teacher->name }}
        </h6>
        
        <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
        
        @if($status == 'running' && isset($progress))
        <div class="mb-3">
            <div class="d-flex justify-content-between small text-muted mb-1">
                <span>Event Progress</span>
                <span>{{ $progress }}%</span>
            </div>
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                     role="progressbar" style="width: {{ $progress }}%"></div>
            </div>
        </div>
        @endif
        
        <div class="row g-2 mb-3">
            <div class="col-6">
                <div class="small text-muted">Start Time</div>
                <div class="event-time" data-time="{{ $event->start_time }}">
                    {{ $event->start_time->setTimezone('Asia/Dhaka')->format('M d, Y h:i A') }}
                </div>
            </div>
            <div class="col-6">
                <div class="small text-muted">{{ $status == 'running' ? 'Ends' : 'Ended' }}</div>
                <div class="event-time" data-time="{{ $event->end_time }}">
                    {{ $event->end_time->setTimezone('Asia/Dhaka')->format('M d, Y h:i A') }}
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between align-items-center">
            <span class="badge bg-light text-dark">
                <i class="bi bi-clock"></i> {{ $event->duration }} mins
            </span>
            <a href="{{ route('events.show', $event->id) }}" class="btn btn-sm btn-outline-primary">
                Details <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>
</div>