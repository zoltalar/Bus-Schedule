<template>
    <div id="root">
        <nav class="navbar navbar-light bg-light mb-3">
            <a class="navbar-brand">Bus Scheduler</a>
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <timer></timer>
                </li>
            </ul>
        </nav>
        <div class="row">
            <div class="col-sm-3">
                <div class="list-group">
                    <stop-list></stop-list>
                </div>
            </div>
            <div class="col-sm-9">
                <stop></stop>
            </div>
        </div>
    </div>
</template>