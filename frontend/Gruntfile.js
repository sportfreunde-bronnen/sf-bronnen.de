const sass = require('node-sass');

module.exports = function (grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    watch: {
      options: {
        livereload: true,
      },
      scss: {
        files: ['src/sass/**/*.scss'],
        tasks: ['sass', 'postcss'],
        options: {
          interrupt: true
        }
      }
    },
    sass: {
      dist: {
        options: {
          outputStyle: 'compressed',
          implementation: sass,
          sourceMap: false
        },
        files: [{
          expand: true,
          cwd: 'src/sass/',
          src: ['*.scss'],
          dest: '../public/assets/css/',
          ext: '.css'
        }]
      }
    },
    postcss: {
      options: {
        map: false,
        processors: [
          require('autoprefixer')({browsers: 'last 3 versions'})
        ]
      },
      dist: {
        src: ['../public/assets/css/*.css']
      }
    }
  });

  // Load the Grunt plugins.
  grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-postcss');
  grunt.loadNpmTasks('grunt-contrib-watch');

  // Set task aliases
  grunt.registerTask('default', ['watch']);
  grunt.registerTask('build', ['sass', 'postcss']);
};
