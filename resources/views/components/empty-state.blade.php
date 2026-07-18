@props([
    'icon' => 'bi-folder',
    'title' => 'No items found',
    'message' => 'There are no records in the database yet.',
    'actionUrl' => null,
    'actionText' => 'Create New'
])

<div class="text-center py-5 px-4 card card-stat d-flex flex-column align-items-center justify-content-center" style="border-style: dashed; background: rgba(99, 102, 241, 0.02); min-height: 280px; border-width: 2px; border-color: var(--card-border);">
    <div style="width: 60px; height: 60px; border-radius: 50%; background: rgba(99, 102, 241, 0.08); display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
        <i class="bi {{ $icon }}" style="font-size: 2.1rem; color: var(--accent);"></i>
    </div>
    <h4 style="font-size: 1.15rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">{{ $title }}</h4>
    <p style="font-size: 0.85rem; color: var(--text-muted); max-width: 400px; margin-bottom: 1.5rem; line-height: 1.5;">{{ $message }}</p>
    
    @if ($actionUrl)
        <a href="{{ $actionUrl }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-2" style="padding: 0.5rem 1.25rem; font-weight: 600; border-radius: 10px;">
            <i class="bi bi-plus-lg"></i> {{ $actionText }}
        </a>
    @endif
</div>
