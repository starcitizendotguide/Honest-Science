<div class="side-menu">
    <aside class="menu is-one-third m-t-20 m-l-20">
        <p class="menu-label">General</p>
        <ul class="menu-list">
            <li><a href="{{ route('manage.dashboard') }}">Dashboard</a></li>
        </ul>

        <p class="menu-label">Content</p>
        <ul class="menu-list">
            <li><a href="{{ route('manage.content.tasks') }}" class="is-active">Tasks</a></li>
            <li><a href="#">Status</a></li>
        </ul>

        <p class="menu-label">Permission System</p>
        <ul class="menu-list">
            <li><a href="#">Users</a></li>
            <li><a href="#">Groups</a></li>
            <li><a href="#">Permissions</a></li>
        </ul>
    </aside>
</div>
