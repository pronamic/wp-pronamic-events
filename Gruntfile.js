module.exports = function( grunt ) {
	require( 'load-grunt-tasks' )( grunt );

	// Project configuration.
	grunt.initConfig( {
		pkg: grunt.file.readJSON( 'package.json' ),

		// PHPLint
		phplint: {
			all: [
				'**/*.php',
				'!deploy/**',
				'!node_modules/**',
				'!vendor/**'
			]
		},

		// PHP Code Sniffer
		phpcs: {
			application: {
				src: [
					'**/*.php',
					'!deploy/**',
					'!node_modules/**',
					'!vendor/**'
				],
			},
			options: {
				bin: 'vendor/bin/phpcs',
				standard: 'phpcs.ruleset.xml',
				showSniffCodes: true
			}
		},

		// PHP Mess Detector
		phpmd: {
			application: {
				dir: '.'
			},
			options: {
				exclude: 'node_modules,vendor',
				reportFormat: 'xml',
				rulesets: 'phpmd.ruleset.xml'
			}
		},

		// JSHint
		jshint: {
			files: ['Gruntfile.js' ],
			options: {
				// options here to override JSHint defaults
				globals: {
					jQuery: true,
					console: true,
					module: true,
					document: true
				}
			}
		},

		// Check WordPress version
		checkwpversion: {
			options: {
				readme: 'readme.txt',
				plugin: 'pronamic-events.php',
			},
			check: {
				version1: 'plugin',
				version2: 'readme',
				compare: '=='
			},
			check2: {
				version1: 'plugin',
				version2: '<%= pkg.version %>',
				compare: '=='
			}
		},

		// Check textdomain errors
		checktextdomain: {
			options:{
				text_domain: '<%= pkg.name %>',
				keywords: [
					'__:1,2d',
					'_e:1,2d',
					'_x:1,2c,3d',
					'esc_html__:1,2d',
					'esc_html_e:1,2d',
					'esc_html_x:1,2c,3d',
					'esc_attr__:1,2d',
					'esc_attr_e:1,2d',
					'esc_attr_x:1,2c,3d',
					'_ex:1,2c,3d',
					'_n:1,2,4d',
					'_nx:1,2,4c,5d',
					'_n_noop:1,2,3d',
					'_nx_noop:1,2,3c,4d'
				]
			},
			files: {
				src:  [
					'**/*.php',
					'!deploy/**',
					'!node_modules/**'
				],
				expand: true
			}
		},

		// Make POT
		makepot: {
			target: {
				options: {
					domainPath: 'languages',
					type: 'wp-plugin',
					updatePoFiles: true,
					exclude: [
						'deploy/.*',
						'node_modules/.*',
						'vendor/.*',
					],
				}
			}
		},

		// Copy
		copy: {
			assets: {
				files: [
					{ // jQuery UI - https://jqueryui.com/
						expand: true,
						cwd: 'bower_components/jquery-ui',
						src: [
							'themes/base/**'
						],
						dest: 'assets/jquery-ui'
					},
					{ // jQuery UI Datepicker Skins for WordPress - https://github.com/xwp/wp-jquery-ui-datepicker-skins
						expand: true,
						cwd: 'bower_components/wp-jquery-ui-datepicker-skins/css/',
						src: [
							'datepicker.css'
						],
						dest: 'assets/wp-jquery-ui-datepicker-skins'
					},
				]
			},
			deploy: {
				src: [
					'**',
					'!.*',
					'!.*/**',
					'!Gruntfile.js',
					'!package.json',
					'!phpcs.ruleset.xml',
					'!phpmd.ruleset.xml',
					'!readme.md',
					'!deploy/**',
					'!node_modules/**',
					'!bower_components/**',
					'!vendor/**'
				],
				dest: 'deploy/latest',
				expand: true,
				dot: true
			},
		},


		// CSS min
		cssmin: {
			styles: {
				files: {

				}
			},
			assets: {
				files: {
					// jQuery UI Datepicker Skins for WordPress - https://github.com/xwp/wp-jquery-ui-datepicker-skins
					'assets/wp-jquery-ui-datepicker-skins/datepicker.min.css': 'assets/wp-jquery-ui-datepicker-skins/datepicker.css'
				}
			}
		},

		// Clean
		clean: {
			deploy: {
				src: [ 'deploy/latest' ]
			},
		},

		// Composer
		composer: {
			options : {
				cwd: 'deploy/latest',
				flags: [
					'no-dev',
					'prefer-dist'
				]
			}
		},

		// Compress
		compress: {
			deploy: {
				options: {
					archive: 'deploy/archives/<%= pkg.name %>.<%= pkg.version %>.zip'
				},
				expand: true,
				cwd: 'deploy/latest',
				src: ['**/*'],
				dest: '<%= pkg.name %>/'
			}
		},

		// Git checkout
		gitcheckout: {
			tag: {
				options: {
					branch: 'tags/<%= pkg.version %>'
				}
			},
			develop: {
				options: {
					branch: 'develop'
				}
			}
		},

		// S3
		aws_s3: {
			options: {
				region: 'eu-central-1'
			},
			deploy: {
				options: {
					bucket: 'downloads.pronamic.eu',
					differential: true
				},
				files: [
					{
						expand: true,
						cwd: 'deploy/archives/',
						src: '<%= pkg.name %>.<%= pkg.version %>.zip',
						dest: 'plugins/<%= pkg.name %>/'
					}
				]
			}
		},
		
		// WordPress deploy
		rt_wp_deploy: {
			app: {
				options: {
					svnUrl: 'http://plugins.svn.wordpress.org/<%= pkg.name %>/',
					svnDir: 'deploy/wp-svn',
					svnUsername: 'pronamic',
					deployDir: 'deploy/latest',
					version: '<%= pkg.version %>',
				}
			}
		}
	} );

	// Default task(s).
	grunt.registerTask( 'default', [ 'jshint', 'phplint', 'phpcs', 'checkwpversion' ] );
	grunt.registerTask( 'assets', [ 'copy:assets' ] );
	grunt.registerTask( 'min', [ 'cssmin' ] );
	grunt.registerTask( 'pot', [ 'checktextdomain', 'makepot' ] );

	grunt.registerTask( 'deploy', [
		'default',
		'clean:deploy',
		'copy:deploy',
		'composer:install',
		'compress:deploy'
	] );

	grunt.registerTask( 'wp-deploy', [
		'gitcheckout:tag',
		'deploy',
		'rt_wp_deploy',
		'gitcheckout:develop'
	] );
	
	grunt.registerTask( 's3-deploy', [
		'gitcheckout:tag',
		'deploy',
		'aws_s3:deploy',
		'gitcheckout:develop'
	] );
};
