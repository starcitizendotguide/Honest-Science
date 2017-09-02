    <aside class="menu m-l-20">
        <p class="menu-label">General</p>
        <ul class="menu-list">
            <li><a href="{{ route('manage.dashboard') }}" class="{{ Request::is('manage/dashboard*') ? 'is-active' : '' }}">Dashboard</a></li>
        </ul>

        <p class="menu-label">Content</p>
        <ul class="menu-list">
            <li><a href="{{ route('manage.content.tasks') }}" class="{{ Request::is('manage/content/tasks') ? 'is-active' : '' }}">Tasks</a></li>
            <li><a href="{{ route('manage.content.statuses') }}" class="{{ Request::is('manage/content/statuses*') ? 'is-active' : '' }}">Status</a></li>
        </ul>

        <p class="menu-label">Queues</p>
        <ul class="menu-list">
            @if(\Laratrust::can('mark-as-updated-task'))
                <li><a href="{{ route('manage.content.tasks.deprecated') }}" class="@if($queue_amount > 0) has-text-danger @endif {{ Request::is('manage/content/tasks/maintenance*') ? 'is-active' : '' }}">
                    @if($queue_amount > 0) Deprecated ({{ $queue_amount }}) @else Deprecated @endif
                </a></li>
            @endif
        </ul>

        <p class="menu-label">Permission System</p>
        <ul class="menu-list">
            <li><a href="{{ route('manage.content.users') }}" class="{{ Request::is('manage/content/users*') ? 'is-active' : '' }}">Users</a></li>
            <li><a href="{{ route('manage.content.groups') }}" class="{{ Request::is('manage/content/groups*') ? 'is-active' : '' }}">Groups</a></li>
        </ul>
    </aside>
