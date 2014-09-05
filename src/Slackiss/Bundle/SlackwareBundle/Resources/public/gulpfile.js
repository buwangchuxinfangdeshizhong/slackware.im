var gulp = require('gulp');
var gutil = require('gulp-util');
var watchify = require('watchify');
var browserify = require('browserify');
var source = require('vinyl-source-stream');
var less = require('gulp-less');
var path = require('path');
var uglify = require('gulp-uglify');


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
            .pipe(source('bundle.js'))
            .pipe(gulp.dest('./dist'));
    }
    return rebundle();
})

gulp.task('browserify', function() {
    return browserify('./src/main.js').bundle()
        // vinyl-source-stream makes the bundle compatible with gulp
        .pipe(source('bundle.js')) // Desired filename
        // Output the file
        .pipe(gulp.dest('./dist/'));
});

gulp.task('less', function () {
    gulp.src('./less/**/*.less')
        .pipe(less({
            paths: [ path.join(__dirname, 'less', 'includes') ]
        }))
        .pipe(gulp.dest('./css/'));
});

gulp.task('watch-less',function(){
    gulp.watch('./src/**/*.less', ['less']);  // Watch all the .less files, then run the less task
})

gulp.task('dist',['browserify','less']);
gulp.task('default', ['watch-js','watch-less']);
