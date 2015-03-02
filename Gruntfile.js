'use strict';

module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    sass: {
      dist: {
        files: {
          'css/style.css' : 'scss/style.scss'
        }
      }
    },
    autoprefixer: {
      options: {
        browsers: ['last 2 versions', 'ie 8', 'ie 9']
      },
      target: {
        src: 'css/style.css'
      },
    },
    csscomb: {
      options: {
        config: '.csscomb.json'
      },
      css: {
        files: {
          'css/style.css': 'css/style.css'
        }
      }
    },
    watch: {
      css: {
        files: '**/*.scss',
        tasks: ['sass', 'autoprefixer', 'csscomb']
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-autoprefixer');
  grunt.loadNpmTasks('grunt-csscomb');
  grunt.loadNpmTasks('grunt-contrib-watch');

  grunt.registerTask('default',['watch']);
};