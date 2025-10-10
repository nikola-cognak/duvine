
//
// npm packages
//
var gulp         = (require('gulp')),
    fs           = require('fs'),
    rename       = require('gulp-rename'),
    watch        = require('gulp-watch'),

    // inject
    inject       = require('gulp-inject-string'),

    // svg
    svgmin       = require('gulp-svgmin'),
    svgstore     = require('gulp-svgstore'),

    // concat/uglify
    concat       = require('gulp-concat'),
    uglify       = require('gulp-uglify'),
    
    // sass
    sass         = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    sourcemaps   = require('gulp-sourcemaps');





//
// Config
//


var config = {};

config.sass = {
    errLogToConsole : true
};


config.sourcemaps = {
    includeContent : false,
    sourceRoot     : 'src'
};

config.autoprefixer = {};


config.svgmin = {
    plugins:[
        {
            mergePaths: false
        },{
            convertShapeToPath: false
        },{
            convertPathData: false
        }
    ]
};


config.svgstore = {
    inlineSvg: true
};




//
// File system
//

var themeDir = 'wp-content/themes/sherman/';

var files = {};

files.sass = {
    src   : themeDir + 'styles/sass/**/*.scss',
    build : themeDir + 'styles/build/'
};


files.js = {
    src   : [
        themeDir + 'js/src/globals.js',
        themeDir + 'js/plugins/*.js',
        themeDir + 'js/modules/*.js',
        themeDir + 'js/src/main.js'
    ],
    build : themeDir + 'js/build/'
};



files.svg = {
    src   : themeDir + 'svg/*.svg',
    build : themeDir + 'svg/build/'
};









/* --------------------------------------------
 * --gulp
 * -------------------------------------------- */

/**
 * default - doesn't do anything
 * @TODO set up what the default task should do
 */
//gulp.task( 'default', [] );


/**
 * concatenate javascripts
 */
gulp.task(
    'combine',
    //'Concatenates all the javascripts from the arsenal, any plugin scripts, and the main js file',
    function(){
        return gulp.src( files.js.src )
            .pipe( concat('production.js') )
            .pipe( gulp.dest( files.js.build ) );
    }
);

gulp.task('minify', function () {
    return gulp.src(files.js.build + 'production.js')
        .pipe(uglify())
        .pipe(rename({ extname: '.min.js' }))
        .pipe(gulp.dest(files.js.build));
    }
);

/**
 * minify the concatenated javascript file
 */
gulp.task( 'opt-js', gulp.series('combine', 'minify'));


/**
 * svg store
 */
gulp.task(
    'svgstore',
    //'Creates the svg sprite that can be loaded into the page via javascript.',
    function () {
        return gulp.src( files.svg.src )
            .pipe( rename({prefix: 'icon--'}) )
            .pipe( svgmin( config.svgmin ) )
            .pipe( svgstore( config.svgstore ))
            .pipe(gulp.dest( files.svg.build ));
    }
);



/**
 * compile sass
 */
gulp.task(
   'styles',
   //'Compile that sass.',
   function(){
       return (
           gulp.src( files.sass.src )
               .pipe( sourcemaps.init() )
               .pipe( sass( config.sass ).on('error', sass.logError) )
               .pipe( autoprefixer(config.autoprefixer).on('error', function(err){ console.log( err); } ) )
               .pipe( sourcemaps.write('.', config.sourcemaps) )
               .pipe( gulp.dest( files.sass.build ))
       );
   }
);

/**
 * watch the sass directory
 */
gulp.task(
    'watch',
    //'Watch those sass files so we can compile it for you on the fly.',
    function(){
        gulp.watch(files.sass.src, gulp.parallel('styles'))
        gulp.watch(files.js.src, gulp.parallel('opt-js'));
    }
);
