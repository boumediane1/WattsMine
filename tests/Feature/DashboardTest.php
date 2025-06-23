<?php

test('guests are redirected to the login page', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function () {
});

test('calculates real time total active power from solar arrays', function () {
});

test('calculates real time total consumption', function () {
});

test('retrieves real time utility grid active power', function () {
});

test('should correctly aggregate solar production and consumption', function () {
});
