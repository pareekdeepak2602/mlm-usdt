@foreach($referrals as $referralItem)
<li class="level-{{ $level }}">
    <div class="tree-node">
        <div class="node-content">
            <div class="node-avatar">
                <i class="fas fa-user fa-lg"></i>
            </div>
            <div class="node-info">
                <strong>{{ $referralItem['user']->name }}</strong>
                <br>
                <small>{{ $referralItem['user']->email }}</small>
                <br>
                <span class="badge bg-{{ $level == 1 ? 'primary' : ($level == 2 ? 'success' : 'info') }}">
                    Level {{ $level == 1 ? 'A' : ($level == 2 ? 'B' : 'C') }}
                </span>
                <br>
                <span class="badge bg-secondary">
                    Joined: {{ $referralItem['user']->created_at->format('M d, Y') }}
                </span>
            </div>
        </div>
    </div>
    
    @if(count($referralItem['children']) > 0)
        <ul>
            @include('referrals.partials.tree-level', ['referrals' => $referralItem['children'], 'level' => $level + 1])
        </ul>
    @endif
</li>
@endforeach