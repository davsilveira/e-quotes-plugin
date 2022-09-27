'use strict';

const gulp = require('gulp');
const babel = require('gulp-babel');
const concat = require('gulp-concat');
const sass = require('gulp-sass')(require('sass'));
const sourcemaps = require('gulp-sourcemaps');

function buildStyles() {
    return gulp.src('./assets/sass/main.scss')
        .pipe(sourcemaps.init())
        .pipe(sass.sync().on('error', sass.logError))
        .pipe(sourcemaps.write('./maps'))
        .pipe(gulp.dest('./assets/css'));
};

function buildScripts() {
    return gulp.src(['./assets/js/**/*.js', '!./assets/js/min/*.js'])
        .pipe(sourcemaps.init())
        .pipe(babel({
            presets: ['@babel/preset-env']
        }))
        .pipe(concat('app.js'))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('./assets/js/'))
}

exports.buildStyles = buildStyles;
exports.buildScripts = buildScripts;
exports.watch = function () {
    gulp.watch('./assets/sass/**/*.scss', buildStyles);
};
