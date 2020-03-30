var compileJsFiles = [
    "www/js/compile/*.js"
];

var sledujCssFiles = [
    "www/css/less/*.less"
];

var compileCssFiles = [
    "www/css/less/main.less"
];

module.exports = function(grunt) {
    grunt.initConfig({
        watch: {
            uglifyjs: {
                files: compileJsFiles,
                tasks: ['uglify:js']
            },
            lesscss: {
                files: sledujCssFiles,
                tasks: ['less:release']
            }
//            , 
//            ftpushall: {
//                files: '**',
//                tasks: ['ftpush:build']
//            }
        },
        uglify: {
            js: {
                options: {
                    beautify: false,
                    report: "min"
                },
                files: {
                    "www/js/app.min.js": compileJsFiles
                }
            }
        },
        less: {
            release: {
                options: {
                    compress: true
                },
                files: {
                    "www/css/main.min.css": compileCssFiles
                }
            }
        }
    });
    
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.registerTask('default', ['uglify:js', 'less:release', 'watch'/*, 'ftpush:build'*/]);
}
