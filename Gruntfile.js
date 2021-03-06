/* eslint-env node */
module.exports = function ( grunt ) {
	'use strict';

	grunt.loadNpmTasks( 'grunt-eslint' );
	grunt.loadNpmTasks( 'grunt-jsonlint' );
	grunt.loadNpmTasks( 'grunt-banana-checker' );

	grunt.initConfig( {
		eslint: {
			fix: {
				options: {
					fix: true
				},
				src: '<%= eslint.main %>'
			},
			main: [
				'**/*.js',
				'!node_modules/**',
				'!extensions/**',
				'!resources/js/jquery.autosize.js'
			]
		},
		jsonlint: {
			all: [
				'**/*.json',
				'!node_modules/**',
				'!extensions/**'
			]
		},
		banana: {
			all: [
				'i18n/api',
				'i18n/core',
				'i18n/pagetranslation',
				'i18n/sandbox',
				'i18n/search'
			]
		}
	} );

	grunt.registerTask( 'test', [ 'eslint:main', 'jsonlint', 'banana' ] );
	grunt.registerTask( 'default', 'test' );
};
