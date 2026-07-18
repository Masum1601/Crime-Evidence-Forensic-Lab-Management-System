@extends('layouts.app_v3')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')

{{-- Greeting --}}
<div style="margin-bottom:1.5rem">
    <div style="font-size:1.25rem;font-weight:800;color:var(--text-primary)">
        Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},
        {{ auth()->user()->full_name }} 👋
    </div>
    <div style="font-size:0.8rem;color:var(--text-muted);margin-top:3px">
        Here's what's happening in the forensic lab today — {{ now()->format('l, d F Y') }}
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Cases</div>
                    <div class="stat-value">{{ $stats['total_cases'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(59,130,246,0.12);color:#60a5fa">
                    <i class="bi bi-folder2-open"></i>
                </div>
            </div>
            <div class="stat-sub">
                <span style="color:#34d399;font-weight:600">{{ $stats['open_cases'] }} open</span>
                &nbsp;·&nbsp; {{ $stats['closed_cases'] }} closed
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Evidence</div>
                    <div class="stat-value">{{ $stats['total_evidence'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(245,158,11,0.12);color:#fbbf24">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
            <div class="stat-sub">
                <span style="color:#60a5fa;font-weight:600">{{ $stats['evidence_in_storage'] }} in storage</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Custody Transfers</div>
                    <div class="stat-value">{{ $stats['total_custody_transfers'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(16,185,129,0.12);color:#34d399">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
            </div>
            <div class="stat-sub">Auto-logged by PL/SQL trigger</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">System Users</div>
                    <div class="stat-value">{{ $stats['total_users'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(139,92,246,0.12);color:#a78bfa">
                    <i class="bi bi-people"></i>
                </div>
            </div>
            <div class="stat-sub">Across all roles</div>
        </div>
    </div>
</div>

{{-- Charts --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card p-3">
            <h5 class="fw-bold mb-3" style="font-size:0.9rem; color:var(--text-primary)"><i class="bi bi-bar-chart-line me-1 text-primary"></i> Cases by Status</h5>
            <div style="position:relative; height:240px;">
                <canvas id="casesChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3">
            <h5 class="fw-bold mb-3" style="font-size:0.9rem; color:var(--text-primary)"><i class="bi bi-pie-chart me-1 text-success"></i> Evidence by Current Status</h5>
            <div style="position:relative; height:240px;">
                <canvas id="evidenceChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const casesData = JSON.parse('{!! $casesJson !!}');
    const evidenceData = JSON.parse('{!! $evidenceJson !!}');
    
    function getChartColors() {
        const style = getComputedStyle(document.documentElement);
        return {
            text: style.getPropertyValue('--text-primary').trim() || '#e8edf5',
            muted: style.getPropertyValue('--text-muted').trim() || '#889bbd',
            border: style.getPropertyValue('--card-border').trim() || '#1a2540',
            accent: style.getPropertyValue('--accent').trim() || '#6366f1'
        };
    }
    
    let colors = getChartColors();
    
    // 1. Cases Status Bar Chart
    const casesCtx = document.getElementById('casesChart').getContext('2d');
    const casesChart = new Chart(casesCtx, {
        type: 'bar',
        data: {
            labels: ['Open', 'Closed', 'Pending'],
            datasets: [{
                label: 'Cases',
                data: [casesData.open, casesData.closed, casesData.pending],
                backgroundColor: [
                    'rgba(96, 165, 250, 0.45)', // open
                    'rgba(52, 211, 153, 0.45)', // closed
                    'rgba(251, 191, 36, 0.45)'  // pending
                ],
                borderColor: [
                    '#3b82f6',
                    '#10b981',
                    '#f59e0b'
                ],
                borderWidth: 1.5,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15, 25, 41, 0.95)',
                    titleColor: '#e8edf5',
                    bodyColor: '#e8edf5',
                    borderColor: '#1a2540',
                    borderWidth: 1
                }
            },
            scales: {
                x: {
                    grid: { color: colors.border, drawOnChartArea: true, drawTicks: false },
                    ticks: { color: colors.muted, font: { family: 'inherit', size: 11 } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: colors.border, drawOnChartArea: true, drawTicks: false },
                    ticks: { color: colors.muted, stepSize: 1, font: { family: 'inherit', size: 11 } }
                }
            }
        }
    });
    
    // 2. Evidence Status Donut Chart
    const evidenceCtx = document.getElementById('evidenceChart').getContext('2d');
    const evidenceChart = new Chart(evidenceCtx, {
        type: 'doughnut',
        data: {
            labels: ['In Storage', 'In Analysis', 'In Transit', 'Released', 'Disposed'],
            datasets: [{
                data: [
                    evidenceData.in_storage,
                    evidenceData.in_analysis,
                    evidenceData.in_transit,
                    evidenceData.released,
                    evidenceData.disposed
                ],
                backgroundColor: [
                    'rgba(59, 130, 246, 0.45)',
                    'rgba(139, 92, 246, 0.45)',
                    'rgba(245, 158, 11, 0.45)',
                    'rgba(16, 185, 129, 0.45)',
                    'rgba(239, 68, 68, 0.45)'
                ],
                borderColor: [
                    '#3b82f6',
                    '#8b5cf6',
                    '#f59e0b',
                    '#10b981',
                    '#ef4444'
                ],
                borderWidth: 1.5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        color: colors.text,
                        font: { family: 'inherit', size: 11 },
                        padding: 15
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 25, 41, 0.95)',
                    titleColor: '#e8edf5',
                    bodyColor: '#e8edf5',
                    borderColor: '#1a2540',
                    borderWidth: 1
                }
            }
        }
    });
    
    // Theme switch listener
    window.addEventListener('themechanged', function() {
        setTimeout(() => {
            const newColors = getChartColors();
            
            // Update Cases Chart
            casesChart.options.scales.x.grid.color = newColors.border;
            casesChart.options.scales.x.ticks.color = newColors.muted;
            casesChart.options.scales.y.grid.color = newColors.border;
            casesChart.options.scales.y.ticks.color = newColors.muted;
            casesChart.update();
            
            // Update Evidence Chart
            evidenceChart.options.plugins.legend.labels.color = newColors.text;
            evidenceChart.update();
        }, 50);
    });
});
</script>

{{-- Recent Lists --}}
<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--card-border);display:flex;align-items:center;justify-content:space-between">
                <span style="font-weight:700;font-size:0.85rem;display:flex;align-items:center;gap:0.4rem">
                    <i class="bi bi-clock-history" style="color:var(--accent)"></i> Recent Cases
                </span>
                <a href="{{ route('cases.index') }}" style="font-size:0.72rem;color:var(--accent);font-weight:600">View all →</a>
            </div>
            <ul class="list-group list-group-flush">
                @forelse ($recentCases as $case)
                @php $cls = match($case->case_status){'OPEN'=>'open','CLOSED'=>'closed',default=>'pending'}; @endphp
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <div style="font-weight:600;font-size:0.83rem">{{ $case->case_title }}</div>
                        <div style="font-size:0.72rem;color:var(--text-muted)">{{ $case->officer->full_name ?? 'Unassigned' }}</div>
                    </div>
                    <span class="badge-soft badge-{{ $cls }}">{{ $case->case_status }}</span>
                </li>
                @empty
                <li class="list-group-item" style="color:var(--text-muted)">No cases yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--card-border);display:flex;align-items:center;justify-content:space-between">
                <span style="font-weight:700;font-size:0.85rem;display:flex;align-items:center;gap:0.4rem">
                    <i class="bi bi-box-seam" style="color:var(--accent)"></i> Recent Evidence
                </span>
                <a href="{{ route('evidence.index') }}" style="font-size:0.72rem;color:var(--accent);font-weight:600">View all →</a>
            </div>
            <ul class="list-group list-group-flush">
                @forelse ($recentEvidence as $item)
                @php $sm=['IN_STORAGE'=>'storage','IN_ANALYSIS'=>'analysis','IN_TRANSIT'=>'transit','RELEASED'=>'released','DISPOSED'=>'disposed'];$cls=$sm[$item->current_status]??'closed'; @endphp
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <div style="font-weight:600;font-size:0.83rem">{{ $item->evidence_name }}</div>
                        <div style="font-size:0.72rem;color:var(--text-muted)">{{ $item->case->case_title ?? 'N/A' }}</div>
                    </div>
                    <span class="badge-soft badge-{{ $cls }}">{{ $item->current_status }}</span>
                </li>
                @empty
                <li class="list-group-item" style="color:var(--text-muted)">No evidence yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection