<?php

test('$_GET', function () {
    $_GET['VALUE1'] = 'the value #1';
    expect(input('value1'))->toEqual('the value #1');
});

test('$_POST', function () {
    $_GET['VALUE1'] = 'the value #1';
    expect(input('value1'))->toEqual('the value #1');
});
