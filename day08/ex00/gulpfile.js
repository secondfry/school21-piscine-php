const gulp = require('gulp');

// Sourcemaps
const sourcemaps = require('gulp-sourcemaps');

// Compile SASS
const sass = require('gulp-sass');

// Import node please
const moduleImporter = require('sass-module-importer');

// Remove unneeded CSS
const purgecss = require('gulp-purgecss');

// Perform PostCSS magic
const postcss = require('gulp-postcss');

// Pack all @media
const css_mqpacker = require('css-mqpacker');

// Extract @media
const mqextract = require('postcss-mq-extract');

// Add extracted CSS to gulp pipeline
const addsrc = require('gulp-add-src');

// Minimize everything
const cssnano = require('cssnano');

// Rename to .min.css
const rename = require('gulp-rename');

gulp.task('sass', () => {
  return gulp.src('./src/sass/app.scss')
    .pipe(sourcemaps.init())
    .pipe(sass({ importer: moduleImporter() }).on('error', sass.logError))
    .pipe(purgecss({
      content: [
        './src/**/*.js',
        './src/**/*.vue',
        './dest/index-view.html',
        './dest/**/*.js',
      ]
    }))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('./dest/css/'));
});

gulp.task('css', () => {
  return gulp.src([
    './dest/css/app.css',
    './dest/css/vue.css'
  ], {
    allowEmpty: true
  })
    .pipe(sourcemaps.init())
    .pipe(postcss([
      css_mqpacker(),
      mqextract({
        dest: './dest/css/',
        match: '(min-width: 768px|min-width: 992px|min-width: 1200px)',
        postfix: '-desktop',
      }),
    ]))
    .pipe(postcss([
      cssnano({
        preset: 'default',
      }),
    ]))
    .pipe(rename({
      extname: ".min.css"
    }))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('./dest/css/'));
});

gulp.task('minify-desktop', () => {
  return gulp.src('./dest/css/*-desktop.css')
    .pipe(sourcemaps.init())
    .pipe(postcss([
      cssnano({
        preset: 'default',
      }),
    ]))
    .pipe(rename({
      extname: ".min.css"
    }))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('./dest/css/'));
});

gulp.task('sass:watch', () => {
  gulp.watch([
    './dest/index.html',
    './dest/css/vue.css',
    './src/js/**/*.js',
    './src/js/**/*.vue',
    './src/sass/**/*.scss',
  ], gulp.series('sass', 'css', 'minify-desktop'));
});

gulp.task('default', gulp.series('sass', 'css', 'minify-desktop'));
gulp.task('watch', gulp.series('sass', 'css', 'minify-desktop', 'sass:watch'));
