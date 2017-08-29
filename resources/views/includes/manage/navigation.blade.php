    <aside class="menu m-l-20">
        <p class="menu-label">General</p>
        <ul class="menu-list">
            <li><a href="{{ route('manage.dashboard') }}" class="{{ Request::is('manage/dashboard*') ? 'is-active' : '' }}">Dashboard</a></li>
        </ul>

        <p class="menu-label">Content</p>
        <ul class="menu-list">
            <li><a href="{{ route('manage.content.tasks') }}" class="{{ Request::is('manage/content/tasks*') ? 'is-active' : '' }}">Tasks</a></li>
            <li><a href="{{ route('manage.content.statuses') }}" class="{{ Request::is('manage/content/statuses*') ? 'is-active' : '' }}">Status</a></li>
        </ul>

        <p class="menu-label">Permission System</p>
        <ul class="menu-list">
            <li><a href="#">Users</a></li>
            <li><a href="#">Groups</a></li>
            <li><a href="#">Permissions</a></li>
        </ul>
    </aside>
