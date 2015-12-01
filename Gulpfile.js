/**
 * Created by Вячеслав on 03.11.2015.
 */
var
    gulp = require('gulp'),
    concat = require('gulp-concat'),
    filter = require('gulp-filter'),
    order = require('gulp-order'),
    uglify = require('gulp-uglify'),
    bower = require('gulp-bower'),
    mainBowerFiles = require('main-bower-files')
    ;

gulp.task('client:bower', function() {
    return bower({ cmd: 'update', cwd: './client'});
});

gulp.task('main:bower', function() {
    return bower({ cmd: 'update', cwd: './main'});
});

gulp.task('client:scripts:vendor', ['client:bower'], function () {
    var vendors = mainBowerFiles({
        paths: 'client/',
        overrides: {
            tinycon: {
                main: [
                    './tinycon.js'
                ]
            },
            slimScroll: {
                main: [
                    './jquery.slimscroll.js'
                ]
            }
        } });

    return gulp.src(vendors)
        .pipe(filter('**.js'))
        .pipe(order(vendors))
        .pipe(concat('vendor.js'))
        .pipe(uglify())
        .pipe(gulp.dest('client/site/js/vendor/'))
        ;
});

gulp.task('main:scripts:vendor', ['main:bower'], function () {
    var vendors = mainBowerFiles({
        paths: 'main/',
        overrides: {
            tinycon: {
                main: [
                    './tinycon.js'
                ]
            },
            slimScroll: {
                main: [
                    './jquery.slimscroll.js'
                ]
            }
        }
    });

    return gulp.src(vendors)
        .pipe(filter('**.js'))
        .pipe(order(vendors))
        .pipe(concat('vendor.js'))
        .pipe(uglify())
        .pipe(gulp.dest('main/site/js/vendor/'))
        ;
});

gulp.task('default', ['client:scripts:vendor', 'main:scripts:vendor']);