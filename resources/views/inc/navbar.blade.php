<nav class="navbar navbar-inverse">
    <div class="container">
        <div id="navbar">
            <ul class="nav navbar-nav">
                <li class="{{ Request::is('/') ? 'active' : '' }}"><a href="/">Home</a></li>
                <li class="{{ Request::is('/todo/create') ? 'active' : '' }}"><a href="/todo/create">Add</a></li>
            </ul>
        </div>
    </div>
</nav>
