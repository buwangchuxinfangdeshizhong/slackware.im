var gulp = require('gulp');
var gutil = require('gulp-util');
var watchify = require('watchify');
var browserify = require('browserify');
var source = require('vinyl-source-stream');
var less = require('gulp-less');
var path = require('path');
var uglify = require('gulp-uglify');
var minifyCSS = require('gulp-minify-css');
var concat = require('gulp-concat');

gulp.task('watch-js', function() {
    var bundler = watchify(browserify('./src/main.js', watchify.args));

    // Optionally, you can apply transforms
    // and other configuration options on the
    // bundler just as you would with browserify
    bundler.transform('brfs');

    bundler.on('update', rebundle);

    function rebundle() {
        return bundler.bundle()
            // log errors if they happen
            .on('error', gutil.log.bind(gutil, 'Browserify Error'))
            .pipe(source('slackware.im.js'))
            .pipe(gulp.dest('./js/'));
    }
    return rebundle();
})

gulp.task('browserify', function() {
    return browserify('./src/main.js').bundle()
        // vinyl-source-stream makes the bundle compatible with gulp
        .pipe(source('slackware.im.js')) // Desired filename
        // Output the file
        .pipe(gulp.dest('./js/'));
});

gulp.task('js',['browserify'],function(){
    return gulp.src('./js/slackware.im.js')
        .pipe(uglify())
        .pipe(gulp.dest('./js/'))
})

gulp.task('less', function () {
    return gulp.src(['./less/style.less'
             ])
        .pipe(less())
        .pipe(gulp.dest('./css/'));
});

gulp.task('css',['less'],function(){
    return gulp.src(['./bootstrap/css/bootstrap.css',
             './css/style.css'])
        .pipe(concat('slackware.im.css'))
        .pipe(minifyCSS({keepBreaks:false}))
        .pipe(gulp.dest('./css/'))
})

gulp.task('watch-less',function(){
    return gulp.watch('./less/style.less', ['less']);  // Watch all the .less files, then run the less task
})

gulp.task('default', ['watch-js','watch-less']);
gulp.task('dist',['js','css']);
