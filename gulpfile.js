var gulp = require('gulp'),
    sass = require('gulp-sass'),
    neat = require('node-neat'),
    normalize = require('node-normalize-scss'),
    watch = require('gulp-watch'),
    plumber = require('gulp-plumber'),
    sourcemaps = require('gulp-sourcemaps'),
    rename = require('gulp-rename');

var paths = {
    sass: neat.with(normalize.includePaths)
};

gulp.task('sass', function() {
    return gulp.src('resources/assets/sass/**/*.scss')
        .pipe(sourcemaps.init())
        .pipe(plumber())
        .pipe(sass({
            includePaths: paths.sass
        }).on('error', sass.logError))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('public/assets/css/'));
});

gulp.task('watch', function() {
    gulp.watch('resources/assets/sass/**/*.scss', ['sass']);
});

gulp.task('default', ['sass', 'watch']);