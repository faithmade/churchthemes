module.exports = function(grunt) {

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		jshint: {
			all: ['js/**/*.js', 'Gruntfile.js']
		},

		pot: {
				options:{
					text_domain: 'churchthemes', //Your text domain. Produces my-text-domain.pot
					dest: 'languages/', //directory to place the pot file
					keywords: [ //WordPress localisation functions
						'__:1',
						'_e:1',
						'_x:1,2c',
						'esc_html__:1',
						'esc_html_e:1',
						'esc_html_x:1,2c',
						'esc_attr__:1',
						'esc_attr_e:1',
						'esc_attr_x:1,2c',
						'_ex:1,2c',
						'_n:1,2',
						'_nx:1,2,4c',
						'_n_noop:1,2',
						'_nx_noop:1,2,3c'
					],
				},
				files:{
					src:  [ '**/*.php' ], //Parse all php files
					expand: true,
				}
		},

		phplint: {
			options: {
				swapPath: '/.phplint'
			},
			all: ['**/*.php']
		},

		watch: {
			scripts: {
				files: ['js/**/*.js', 'Gruntfile.js' ],
				tasks: ['jshint'],
				options: {
					interrupt: true,
				}
			},
			pot: {
				files: [ '**/*.php' ],
				tasks: ['pot'],
			},
		}
	});

	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-cssjanus');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-pot');
	grunt.registerTask('default',['watch']);
	grunt.registerTask('lint',['jshint']);
	grunt.registerTask('translate',['pot']);

};