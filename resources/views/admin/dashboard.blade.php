@extends('layouts.admin')

@section('content')
<section class="admin-stats">
    @foreach ($stats as $card)
        <article class="admin-stat-card tone-{{ $card['tone'] }}">
            <p>{{ $card['label'] }}</p>
            <strong>{{ $card['value'] }}</strong>
            <span>{{ $card['change'] }}</span>
        </article>
    @endforeach
</section>

<section class="admin-content-grid">
    <div class="admin-panel">
        <div class="panel-head">
            <h2>Recent Inquiries</h2>
            <a href="{{ route('admin.inquiries') }}">View all</a>
        </div>

        <div class="inquiry-list">
            @foreach ($inquiries as $inquiry)
                <article class="inquiry-item">
                    <div class="inquiry-avatar">{{ $inquiry['initials'] }}</div>
                    <div>
                        <strong>{{ $inquiry['name'] }}</strong>
                        <p>{{ $inquiry['service'] }}</p>
                    </div>
                    <span>{{ $inquiry['date'] }}</span>
                    <em class="status-pill status-{{ $inquiry['tone'] }}">{{ $inquiry['status'] }}</em>
                </article>
            @endforeach
        </div>
    </div>

    <div class="admin-panel">
        <div class="panel-head">
            <h2>Quick Actions</h2>
        </div>

        <div class="action-list">
            @foreach ($actions as $action)
                <a href="{{ route(match ($action['abbr']) {
                    'PR' => 'admin.portfolio',
                    'BL' => 'admin.blog',
                    'TM' => 'admin.team',
                    'SV' => 'admin.services',
                    'HS' => 'admin.hero-slides',
                    default => 'admin.settings',
                }) }}" class="quick-action tone-{{ $action['tone'] }}">
                    <span>{{ $action['abbr'] }}</span>
                    <strong>{{ $action['title'] }}</strong>
                </a>
            @endforeach
        </div>
    </div>

    <div class="admin-panel admin-panel-wide">
        <div class="panel-head">
            <h2>Inquiries - Last 6 Months</h2>
        </div>

        <div class="chart-bars">
            @foreach ($chart as $bar)
                <div class="chart-bar-wrap">
                    <div class="chart-bar chart-{{ $bar['tone'] }}" style="height: {{ $bar['height'] }}px;"></div>
                    <span>{{ $bar['month'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
