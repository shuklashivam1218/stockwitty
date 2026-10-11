{{-- Author, reading time and dates of a saved article. --}}
<div class="admin-card">
    <div class="cms-side-title">{{ $title }}</div>
    <div class="cms-info-row">
        <span class="cms-info-label">Author</span>
        <span class="cms-info-value">{{ $article->author->name ?? 'Unknown' }}</span>
    </div>
    <div class="cms-info-row">
        <span class="cms-info-label">Reading time</span>
        <span class="cms-info-value">{{ $article->readLabel() }}</span>
    </div>
    <div class="cms-info-row">
        <span class="cms-info-label">First drafted</span>
        <span class="cms-info-value">{{ $article->created_at?->format('d M Y, h:i A') }}</span>
    </div>
    <div class="cms-info-row">
        <span class="cms-info-label">Last updated</span>
        <span class="cms-info-value">{{ $article->updated_at?->format('d M Y, h:i A') }}</span>
    </div>
    @if($article->published_at)
    <div class="cms-info-row">
        <span class="cms-info-label">Published on</span>
        <span class="cms-info-value">{{ $article->published_at->format('d M Y, h:i A') }}</span>
    </div>
    @endif
</div>
