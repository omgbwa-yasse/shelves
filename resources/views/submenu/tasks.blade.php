<div class="container" style="background-color: #f1f1f1;">
    <div class="row">
        <!-- Search -->
        <a class="nav-link active bg-primary text-white" data-toggle="collapse" href="#searchMenu" aria-expanded="true" aria-controls="searchMenu" style="padding: 10px;">
            <i class="bi bi-search"></i> {{ __('search') }}
        </a>
        <div class="collapse show" id="searchMenu">
            <ul class="list-unstyled pl-3">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('tasks.index') }}"><i class="bi bi-inbox"></i> {{ __('tasks') }}</a>
                </li>
                <!-- Add other search links here -->
            </ul>
        </div>

        <!-- Create -->
        <a class="nav-link active bg-primary text-white" data-toggle="collapse" href="#createMenu" aria-expanded="true" aria-controls="createMenu" style="padding: 10px;">
            {{ __('create') }}
        </a>
        <div class="collapse show" id="createMenu">
            <ul class="list-unstyled pl-3">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('tasks.create') }}"><i class="bi bi-inbox"></i> {{ __('task') }}</a>
                </li>
                <!-- Add other creation links here -->
            </ul>
        </div>

        <!-- Task Tracking -->
        <a class="nav-link active bg-primary text-white" data-toggle="collapse" href="#trackingMenu" aria-expanded="true" aria-controls="trackingMenu" style="padding: 10px;">
            {{ __('task_tracking') }}
        </a>
        <div class="collapse show" id="trackingMenu">
            <ul class="list-unstyled pl-3">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('tasks.index') }}"><i class="bi bi-inbox"></i> {{ __('ongoing_tasks') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('tasks.index') }}"><i class="bi bi-bookmark-check"></i> {{ __('completed_tasks') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('tasks.myTasks') }}"><i class="bi bi-bookmark-check"></i> {{ __('my_tasks') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('tasks.supervision') }}"><i class="bi bi-bookmark-check"></i> {{ __('supervision') }}</a>
                </li>
            </ul>
        </div>
    </div>
</div>
