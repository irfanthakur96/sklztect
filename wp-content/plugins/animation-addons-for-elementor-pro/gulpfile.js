/**
 * Gulp Config.
 * @version 1.0.0
 */

//const app = require( './package.json' );
const gulp = require('gulp');
const babel = require('gulp-babel');
const prettify = require('gulp-js-prettify');
const terser = require('gulp-terser');
const rename = require('gulp-rename');
const {sass} = require("@mr-hope/gulp-sass");
const minifyCSS = require('gulp-clean-css');
const autoprefixer = require('gulp-autoprefixer');
const sourcemaps = require('gulp-sourcemaps');
const mode = require('gulp-mode')();


// Tasks
gulp.task('compile:js', () => {
    return gulp.src([
        'src/js/**/*.js',
        '!src/js/**/*.min.js',
        '!src/js/utils/*.min.js',
    ])
        .pipe(mode.development(sourcemaps.init({largeFile: true})))  
        .pipe(babel({ presets: [['@babel/preset-env', {modules: false}]] }))
        .pipe(mode.production(terser()))
        .pipe(mode.development(prettify({"indent_with_tabs": true,})))
        .pipe(mode.development(sourcemaps.write('/.')))
        .pipe(gulp.dest('assets/js'));
});

// Tasks
gulp.task('minify:js', () => {
    return gulp.src([
        'assets/js/**/*.js',
        '!assets/js/**/*.min.js',
    ])
        .pipe(mode.development(sourcemaps.init({largeFile: true})))
        .pipe(terser())
        .pipe(rename({suffix: '.min'}))
        .pipe(mode.development(sourcemaps.write('/.')))
        .pipe(gulp.dest('assets/js'));
});

gulp.task('compile:scss', () => {
    return gulp.src([
        'src/scss/**/*.scss',
    ])
        .pipe(mode.development(sourcemaps.init({largeFile: true})))
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer())
        .pipe(mode.production(minifyCSS()))
        .pipe(mode.development(sourcemaps.write('/.')))
        .pipe(gulp.dest('assets/css'));
});

gulp.task('minify:css', function () {
    return gulp.src([
        'assets/css/**/*.css',
        '!assets/css/**/*.min.css'
    ])
        .pipe(mode.development(sourcemaps.init({largeFile: true})))
        .pipe(minifyCSS())
        .pipe(rename({suffix: '.min'}))
        .pipe(mode.development(sourcemaps.write('/.')))
        .pipe(gulp.dest('assets/css'));
});

// Combined tasks.
gulp.task('buildJs', gulp.series('compile:js'));
gulp.task('buildCss', gulp.series('compile:scss'));

gulp.task('build', gulp.series('buildCss', 'buildJs'));

gulp.task('watch', () => new Promise((resolve, reject) => {
    try {
        gulp.watch('src/js/**/*.js', {ignoreInitial: true}, gulp.series('buildJs'));
        gulp.watch('src/scss/**/*.scss', {ignoreInitial: true}, gulp.series('buildCss'));
        resolve();
    } catch (e) {
        reject(e);
    }
}));
