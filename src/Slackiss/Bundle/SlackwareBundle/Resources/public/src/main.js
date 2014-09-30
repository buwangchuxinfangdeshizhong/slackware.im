window.$ = window.jQuery = require('jquery');
require('../bootstrap/js/bootstrap.js');

var watchNotice = require('./post/notice.js');
watchNotice($);

var postAppend = require('./post/append.js');
postAppend($,$('#append-button'),$('#post-append'),$('#post-append-form'));

var postEdit   = require('./post/edit.js');
postEdit($,$('#post-edit-button'),$('.post-content'),$('.post-content-form'));
