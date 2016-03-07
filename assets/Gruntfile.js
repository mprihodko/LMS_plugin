module.exports = function(grunt) {

   
    grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    concat: {
        dist: {
        // src: ['js/frontend.js','js/admin_interaction.js','js/admin_test.js', 'js/groups_edit.js', 'js/reports_functions.js'], 
        // dest: 'js/custom_script.js'   
        src: ['js/admin_interaction.js','js/admin_test.js', 'js/groups_edit.js', 'js/reports_functions.js', 'js/admin-settings.js'], 
        dest: 'js/admin.js'               
        }
    },
    uglify: {
        build: {
            src:'js/admin.js',  
            dest:'js_min/admin.min.js',  
            // src:'js/custom_script.js',  
            // dest:'js_min/custom_script.min.js',          
        }
    },
    // compass: {
    //         dist: {
    //             files: {
    //                 'css/style.css' : 'sass/style.scss'
    //             }
    //         }
    //     },  
    less: {
      development: {
        options: {
          paths: ["less/", "less_front/"]
        },
        files: {
            "css-template/admin.css": "less/admin.less",
            "css-template/stylesheet.css": "less_front/stylesheet.less",
                     
        }
      },
      production: {
        options: {
          paths:  ["less/", "less_front/"],
          
          modifyVars: {
            imgPath: '"http://mycdn.com/path/to/images"',
            bgColor: 'red'
          }
        },
        files: {
            "css-template/admin.css": "less/admin.less",  
            "css-template/stylesheet.css": "less_front/stylesheet.less",                   
        }
      }
    },
    // sprite:{
    //     all: {
    //         src: 'icons/header/*.png',
    //         dest: 'Avada-Child-Theme/sprites/header.png',
    //         destCss: 'css/header_sprite.css'
    //     },
    //     all: {
    //         src: 'icons/service/*.png',
    //         dest: 'Avada-Child-Theme/sprites/service.png',
    //         destCss: 'css/service_sprite.css'
    //     }
    // },
    // imagemin: {
    //     dynamic: {
    //         files: [{
    //             expand: true,
    //             cwd: 'images/',
    //             src: ['**/*.{png,jpg,gif}'],
    //             dest: 'images/build/'
    //         }]
    //     }
    // },
    cssmin: {
      	options: {
        	shorthandCompacting: false,
        	roundingPrecision: -1
        },
      	target: {
        		files: {
    			    'css/admin.min.css': [
                                    'css-template/admin.css',
                                    ],
                    'css/stylesheet.min.css': [
                                    'css-template/stylesheet.css'
                    ]
        		}
     	}
    },
    watch: { 
        scripts:{ 
            files: ['**/*.js'],
            tasks: ['concat', 'uglify']
            
        },
        css:{
            // files: [],
            // tasks: [], 
            files: ['**/*.less', 'css-template/*.css'],
            tasks: ['less', 'cssmin']
        },
        // image:{
        //     files: ['**/*.{png,jpg,gif}'],
        //     tasks: ['imagemin']
        // }

    }
    
});
    grunt.loadNpmTasks('grunt-contrib-concat');
    // grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    // grunt.loadNpmTasks('grunt-spritesmith');   
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    // grunt.loadNpmTasks('grunt-contrib-imagemin');
    grunt.loadNpmTasks('grunt-contrib-watch'); 
    grunt.registerTask('default', ['less', 'cssmin', 'imagemin']);   

};

    
