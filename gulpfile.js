var gulp = require('gulp'),
    sass = require('gulp-sass'),
    watch = require('gulp-watch'),
    plumber = require('gulp-plumber'),
    sourcemaps = require('gulp-sourcemaps'),
    rename = require('gulp-rename');

gulp.task('sass', function() {
    return gulp.src('resources/assets/sass/**/*.scss')
        .pipe(sourcemaps.init())
        .pipe(plumber())
        .pipe(sass({
            includePaths: ['./node_modules/foundation-sites/scss']
        }).on('error', sass.logError))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('public/assets/css/'));
});

gulp.task('watch', function() {
    gulp.watch('resources/assets/sass/**/*.scss', ['sass']);
});

//watch('resources/assets/sass/**/*.scss', function() {
//    gulp.start('sass');
//});
//
//gulp.task('watch', function() {
//    return gulp.src('resources/assets/sass/**/*.scss')
//        .pipe(watch('resources/assets/sass/**/*.scss'));
//});

gulp.task('init-foundation-copy', function() {
    return gulp.src('./node_modules/foundation-sites/scss/foundation/_settings.scss',
            { "base": "./node_modules/foundation-sites/scss"} )
        .pipe(gulp.dest('resources/assets/sass'));
});

gulp.task('init-foundation-move', function() {
    return gulp.src('./node_modules/foundation-sites/scss/foundation.scss')
        .pipe(rename('./resources/assets/sass/foundation/_components.scss'))
        .pipe(gulp.dest('./'));
});

gulp.task('init-foundation', ['init-foundation-copy', 'init-foundation-move']);

gulp.task('default', ['sass', 'watch']);