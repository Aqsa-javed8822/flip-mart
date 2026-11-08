<?php

$root = realpath( __DIR__ . '/..' );
$dist = $root . '/dist';
$config_path = __DIR__ . '/build.config.php';
$build_config = file_exists( $config_path ) ? include $config_path : [];
$default_common_exclude = [
	'.git',
	'.github',
	'.gitignore',
	'.gitattributes',
	'.editorconfig',
	'.env',
	'.env.*',
	'node_modules',
	'dist',
	'src',
	'tests',
	'package.json',
	'package-lock.json',
	'composer.json',
	'composer.lock',
	'webpack.config.js',
	'Gruntfile.js',
	'phpunit.xml',
	'phpunit.xml.dist',
	'.phpunit.result.cache',
	'.phpcs.xml',
	'.phpcs.xml.dist',
	'phpcs.xml',
	'phpcs.xml.dist',
	'README.md',
	'readme.dev.md',
	'readme.dev.txt',
	'CONTRIBUTING.md',
	'SECURITY-AUDIT.md',
	'TESTING-RUNBOOK.md',
	'resources',
	'watch.sh',
	'watch.js',
	'CLAUDE.md',
	'AGENTS.md',
	'.claude',
];

function merge_excludes( $config, $variant ) {
	global $default_common_exclude;
	if ( ! is_array( $config ) ) {
		$config = [];
	}

	$common = $config['common']['exclude'] ?? [];
	$variant_exclude = $config[ $variant ]['exclude'] ?? [];

	if ( ! is_array( $common ) ) {
		$common = [];
	}
	if ( ! is_array( $variant_exclude ) ) {
		$variant_exclude = [];
	}

	$excludes = array_merge( $default_common_exclude, $common, $variant_exclude );
	$excludes = array_unique( $excludes );
	sort( $excludes );

	return $excludes;
}

function ensure_dir( $path ) {
	if ( ! is_dir( $path ) ) {
		mkdir( $path, 0755, true );
	}
}

function should_exclude( $name, $exclude_patterns ) {
	if ( ! is_array( $exclude_patterns ) ) {
		return false;
	}
	foreach ( $exclude_patterns as $pattern ) {
		if ( $name === $pattern ) {
			return true;
		}
		if ( strpos( $pattern, '*' ) !== false ) {
			$regex = '/^' . str_replace( '\\*', '.*', preg_quote( $pattern, '/' ) ) . '$/';
			if ( preg_match( $regex, $name ) ) {
				return true;
			}
		}
	}
	return false;
}

function copy_dir( $src, $dst, $exclude_patterns ) {
	$dir = opendir( $src );
	ensure_dir( $dst );
	while ( false !== ( $file = readdir( $dir ) ) ) {
		if ( $file === '.' || $file === '..' ) {
			continue;
		}
		if ( should_exclude( $file, $exclude_patterns ) ) {
			continue;
		}

		$src_path = $src . '/' . $file;
		$dst_path = $dst . '/' . $file;

		if ( is_dir( $src_path ) ) {
			copy_dir( $src_path, $dst_path, $exclude_patterns );
		} else {
			copy( $src_path, $dst_path );
		}
	}
	closedir( $dir );
}

function remove_dir( $path ) {
	if ( ! file_exists( $path ) ) {
		return;
	}
	if ( is_file( $path ) || is_link( $path ) ) {
		unlink( $path );
		return;
	}
	$items = scandir( $path );
	foreach ( $items as $item ) {
		if ( $item === '.' || $item === '..' ) {
			continue;
		}
		remove_dir( $path . '/' . $item );
	}
	rmdir( $path );
}

function strip_marker_block( $content, $marker ) {
	return preg_replace(
		'/\\R?\\/\\/\\s*@' . preg_quote( $marker, '/' ) . '-start\\R.*?\\R\\/\\/\\s*@' . preg_quote( $marker, '/' ) . '-end\\R?/s',
		"\n",
		$content
	);
}

function get_build_config_value( $config, $variant, $key ) {
	if ( isset( $config[ $variant ] ) && array_key_exists( $key, $config[ $variant ] ) ) {
		return $config[ $variant ][ $key ];
	}
	return null;
}

function process_plugin_file( $plugin_path, $variant, $config ) {
	if ( ! file_exists( $plugin_path ) ) {
		return;
	}

	$content = file_get_contents( $plugin_path );
	if ( $content === false ) {
		return;
	}

	if ( $variant === 'free' ) {
		$plugin_name = get_build_config_value( $config, 'free', 'plugin_name' );
		$version = get_build_config_value( $config, 'free', 'version' );
		$description = get_build_config_value( $config, 'free', 'description' );
		$legacy_name = get_build_config_value( $config, 'free', 'legacy_name' );

		if ( $plugin_name ) {
			$content = preg_replace( '/^\\s*\\*\\s*Plugin Name:\\s*.*$/m', ' * Plugin Name: ' . $plugin_name, $content );
		}
		if ( $version ) {
			$content = preg_replace( '/^\\s*\\*\\s*Version:\\s*.*$/m', ' * Version: ' . $version, $content );
			$content = preg_replace( "/define\\(\\s*'AE_PRO_VERSION'\\s*,\\s*'[^']*'\\s*\\);/m", "define( 'AE_PRO_VERSION', '" . $version . "' );", $content );
			$content = preg_replace( "/define\\(\\s*'AE_PLUGIN_VERSION'\\s*,\\s*'[^']*'\\s*\\);/m", "define( 'AE_PLUGIN_VERSION', '" . $version . "' );", $content );
		}
		if ( $description ) {
			$content = preg_replace( '/^\\s*\\*\\s*Description:\\s*.*$/m', ' * Description: ' . $description, $content );
		}
		if ( $plugin_name ) {
			$content = preg_replace( "/define\\(\\s*'AE_PLUGIN_NAME'\\s*,\\s*'[^']*'\\s*\\);/m", "define( 'AE_PLUGIN_NAME', '" . $plugin_name . "' );", $content );
		}
		if ( $legacy_name !== null ) {
			$content = preg_replace( "/define\\(\\s*'AE_PLUGIN_LEGACY_NAME'\\s*,\\s*'[^']*'\\s*\\);/m", "define( 'AE_PLUGIN_LEGACY_NAME', '" . $legacy_name . "' );", $content );
		}

		$content = strip_marker_block( $content, 'aep-pro-only' );
	} else {
		$plugin_name = get_build_config_value( $config, 'pro', 'plugin_name' );
		$version = get_build_config_value( $config, 'pro', 'version' );
		$description = get_build_config_value( $config, 'pro', 'description' );
		$legacy_name = get_build_config_value( $config, 'pro', 'legacy_name' );

		if ( $plugin_name ) {
			$content = preg_replace( '/^\\s*\\*\\s*Plugin Name:\\s*.*$/m', ' * Plugin Name: ' . $plugin_name, $content );
			$content = preg_replace( "/define\\(\\s*'AE_PLUGIN_NAME'\\s*,\\s*'[^']*'\\s*\\);/m", "define( 'AE_PLUGIN_NAME', '" . $plugin_name . "' );", $content );
		}
		if ( $version ) {
			$content = preg_replace( '/^\\s*\\*\\s*Version:\\s*.*$/m', ' * Version: ' . $version, $content );
			$content = preg_replace( "/define\\(\\s*'AE_PRO_VERSION'\\s*,\\s*'[^']*'\\s*\\);/m", "define( 'AE_PRO_VERSION', '" . $version . "' );", $content );
			$content = preg_replace( "/define\\(\\s*'AE_PLUGIN_VERSION'\\s*,\\s*'[^']*'\\s*\\);/m", "define( 'AE_PLUGIN_VERSION', '" . $version . "' );", $content );
		}
		if ( $description ) {
			$content = preg_replace( '/^\\s*\\*\\s*Description:\\s*.*$/m', ' * Description: ' . $description, $content );
		}
		if ( $legacy_name !== null ) {
			$content = preg_replace( "/define\\(\\s*'AE_PLUGIN_LEGACY_NAME'\\s*,\\s*'[^']*'\\s*\\);/m", "define( 'AE_PLUGIN_LEGACY_NAME', '" . $legacy_name . "' );", $content );
		}

		$content = strip_marker_block( $content, 'aep-free-only' );
	}

	file_put_contents( $plugin_path, $content );
}

function process_readme_file( $readme_path, $variant, $config ) {
	if ( ! file_exists( $readme_path ) ) {
		return;
	}

	$content = file_get_contents( $readme_path );
	if ( $content === false ) {
		return;
	}

	$version = get_build_config_value( $config, $variant, 'version' );
	if ( $version ) {
		$content = preg_replace( '/^Stable tag:\\s*.*$/m', 'Stable tag: ' . $version, $content );
	}

	file_put_contents( $readme_path, $content );
}

function build_variant( $root, $dist, $variant, $slug, $build_config ) {
	$variant_dir = $dist . '/' . $variant . '/' . $slug;
	ensure_dir( $variant_dir );

	$exclude = merge_excludes( $build_config, $variant );

	copy_dir( $root, $variant_dir, $exclude );

	if ( $variant === 'free' ) {
		$old_plugin = $variant_dir . '/anywhere-elementor-pro.php';
		$new_plugin = $variant_dir . '/anywhere-elementor.php';
		if ( file_exists( $old_plugin ) ) {
			rename( $old_plugin, $new_plugin );
			process_plugin_file( $new_plugin, 'free', $build_config );
		}
	} else {
		$plugin_file = $variant_dir . '/anywhere-elementor-pro.php';
		process_plugin_file( $plugin_file, 'pro', $build_config );
	}

	process_readme_file( $variant_dir . '/readme.txt', $variant, $build_config );
}

function parse_install_path( $argv ) {
	foreach ( $argv as $arg ) {
		if ( strpos( $arg, '--install=' ) === 0 ) {
			return substr( $arg, strlen( '--install=' ) );
		}
	}
	return null;
}

ensure_dir( $dist );
build_variant( $root, $dist, 'pro', 'anywhere-elementor-pro', $build_config );
build_variant( $root, $dist, 'free', 'anywhere-elementor', $build_config );

$install_path = parse_install_path( $argv );
if ( $install_path ) {
	$install_path = rtrim( $install_path, '/' );

	if ( ! is_dir( $install_path ) ) {
		echo "Install path does not exist: {$install_path}\n";
		exit( 1 );
	}

	$src_free = $dist . '/free/anywhere-elementor';
	$dst_free = $install_path . '/anywhere-elementor';
	remove_dir( $dst_free );
	copy_dir( $src_free, $dst_free, [] );
	echo "Installed free build to {$dst_free}\n";

	$src_pro = $dist . '/pro/anywhere-elementor-pro';
	$dst_pro = $install_path . '/anywhere-elementor-pro';
	remove_dir( $dst_pro );
	copy_dir( $src_pro, $dst_pro, [] );
	echo "Installed pro build to {$dst_pro}\n";
}

echo "Build complete.\n";
