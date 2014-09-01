module.exports = function(grunt) {
    // project configuration
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        phpunit: {
            classes: {
                dir: 'test/php/**/*.php'
            },
            options: {
                logTap: 'test/tests.log',
                colors: true
            }
        },
        watch: {
            test: {
                files: ['src/**/*.php'],
                tasks: ['phpunit']
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-phpunit');
};
