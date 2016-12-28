var gulp = require('gulp');
var wiredep = require('wiredep').stream;


gulp.task('bower', function () {
	gulp.src('./templates/index.phtml')
		.pipe(wiredep({
			ignorePath: '../public/'
		}))
		.pipe(gulp.dest('./templates'));
});