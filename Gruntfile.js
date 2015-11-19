/**
 * Grunt configuration.
 * Search Typeahead Addon
 *
 * @author Alejandro Mostajo
 * @version 1.0
 * @license MIT
 */
module.exports = function(grunt)
{
    /**
     * Grunt configuration.
     * @since 1.0
     */
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        uglify: {
            typeahead: {
                files: {
                    'assets/build/typeahead-all.min.js': [
                        'bower_components/handlebars/handlebars.min.js',
                        'bower_components/typeahead.js/dist/typeahead.bundle.min.js'
                    ]
                }
            },
            build: {
                files: {
                    'assets/build/wp-typeahead.min.js': ['assets/js/wp-typeahead.js']
                }
            }
        },
        cssmin: {
            options: {
                shorthandCompacting: false,
                roundingPrecision: -1
            },
            build: {
                files: {
                    'assets/build/wp-typeahead.min.css': ['assets/css/wp-typeahead.css']
                }
            }
        }
    });

    /**
     * Load UGLYFY
     * @since 1.0
     */
    grunt.loadNpmTasks('grunt-contrib-uglify');

    /**
     * Load CSSMIN
     * @since 1.0
     */
    grunt.loadNpmTasks('grunt-contrib-cssmin');

    /**
     * Default task(s)
     * @since 1.0
     */
    grunt.registerTask('default', ['uglify', 'cssmin']);
};
