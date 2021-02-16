<?php

namespace WP_Shopify\Lib;

use WP_Shopify\Utils;

/*

get_last_index

*/
if (!\function_exists(__NAMESPACE__ . '\get_last_index')) {

   function get_last_index($namespace_chunks) {

      if ( empty($namespace_chunks) ) {
         return 0;
      }

      return \count($namespace_chunks) - 1;

   }

}


/*

get_last_value

*/
if (!\function_exists(__NAMESPACE__ . '\get_last_value')) {

   function get_last_value($namespace_chunks) {

      if ( empty($namespace_chunks) ) {
         return '';
      }

      return \strtolower( $namespace_chunks[ get_last_index($namespace_chunks) ] );

   }

}

/*

is_large_chunk

*/
if (!\function_exists(__NAMESPACE__ . '\is_large_chunk')) {

   function is_large_chunk($namespace_chunks) {
      return \count($namespace_chunks) > 1;
   }

}

/*

split_namespace_into_chunks

*/
if (!\function_exists(__NAMESPACE__ . '\split_filename')) {

   function split_filename($filename) {
      return \array_values( \array_filter( \explode('class-', $filename) ) );
   }

}


/*

split_file_extension

*/
if (!\function_exists(__NAMESPACE__ . '\split_file_extension')) {

   function split_file_extension($filename) {
      return \array_filter( \explode('.php', $filename) );
   }

}


/*

split_namespace_into_chunks

*/
if (!\function_exists(__NAMESPACE__ . '\split_namespace_into_chunks')) {

   function split_namespace_into_chunks($namespace) {
      return \array_filter( \explode('\\', $namespace) );
   }

}


/*

replace_underscores_with_dashes

*/
if (!\function_exists(__NAMESPACE__ . '\replace_underscores_with_dashes')) {

   function replace_underscores_with_dashes($chunk_name) {
      return \str_ireplace( '_', '-', $chunk_name);
   }

}


/*

replace_spaces_with_dashes

*/
if (!\function_exists(__NAMESPACE__ . '\replace_spaces_with_dashes')) {

   function replace_spaces_with_dashes($chunk_name) {
      return \str_ireplace( ' ', '-', $chunk_name);
   }

}


/*

get_plugin_classes_path

*/
if (!\function_exists(__NAMESPACE__ . '\get_plugin_classes_path')) {

   function get_plugin_classes_path() {
      return trailingslashit( \dirname( \dirname( __FILE__ ) ) ) . 'classes/';
   }

}


/*

add_folder_name

*/
if (!\function_exists(__NAMESPACE__ . '\add_folder_name')) {

   function add_folder_name($folder_name) {
      return \strtolower( trailingslashit( replace_spaces_with_dashes($folder_name) ) );
   }

}


/*

remove_packagename_from_chunks

*/
if (!\function_exists(__NAMESPACE__ . '\remove_packagename_from_chunks')) {

   function remove_packagename_from_chunks($namespace_chunks, $packagename) {

      return \array_values( \array_filter($namespace_chunks, function($namespace_chunk) use ($packagename) {

         if ($namespace_chunk === false) {
            return false;
         }

         return $packagename !== $namespace_chunk;

      }));

   }

}


/*

remove_filename_from_chunks

*/
if (!\function_exists(__NAMESPACE__ . '\remove_filename_from_chunks')) {

   function remove_filename_from_chunks($namespace_chunks) {

      \array_pop($namespace_chunks);

      return $namespace_chunks;

   }

}


/*

build_folder_structure

*/
if (!\function_exists(__NAMESPACE__ . '\build_folder_structure')) {

   function build_folder_structure($namespace_chunks) {

      // We know it's a top-level file
      if ( !\is_array($namespace_chunks) || empty($namespace_chunks) ) {
         return '';
      }

      $folder_path = '';

      foreach ($namespace_chunks as $namespace_chunk) {
         $folder_path .= add_folder_name($namespace_chunk);
      }

      return $folder_path;

   }

}


/*

build_filename

*/
if (!\function_exists(__NAMESPACE__ . '\build_filename')) {

   function build_filename($filename) {

      if ( !\is_string($filename) ) {
         return '';
      }

      return 'class-' . replace_spaces_with_dashes( replace_underscores_with_dashes( \strtolower($filename) ) ) . '.php';

   }

}


/*

combine_folder_and_filename

*/
if (!\function_exists(__NAMESPACE__ . '\combine_folder_and_filename')) {

   function combine_folder_and_filename($folder_structure, $file_name) {
      return $folder_structure . $file_name;
   }

}


if (!\function_exists(__NAMESPACE__ . '\build_top_level_path')) {

   function build_top_level_path($folder_structure, $class_name, $file_name) {
      return $folder_structure . $class_name . '/' . $file_name;
   }

}


/*

Removes any trailing dashes

*/
if (!\function_exists(__NAMESPACE__ . '\remove_trailing_dash')) {

   function remove_trailing_dash($string) {
      return \rtrim($string, '-');
   }

}


if (!\function_exists(__NAMESPACE__ . '\in_arrayi')) {

   function in_arrayi($needle, $haystack) {
   return \in_array( \strtolower($needle), \array_map('strtolower', $haystack));
   }

}

if (!\function_exists(__NAMESPACE__ . '\get_class_from_file_name')) {

   function get_class_from_file_name($file_name) {
      return get_last_value( split_file_extension( get_last_value( split_filename( $file_name) ) ) );
   }

}


// Will skip the top-level check
if (!\function_exists(__NAMESPACE__ . '\whitelisted_classnames')) {

   function whitelisted_classnames() {
      return ['bootstrap', 'config'];
   }

}


if (!\function_exists(__NAMESPACE__ . '\is_top_level')) {

   function is_top_level($namespace_chunks, $class_name, $whitelist) {

      if ( \in_array( $class_name, $whitelist ) ) {
         return false;
      }

      return in_arrayi($class_name, $namespace_chunks);

   }

}

if (!\function_exists(__NAMESPACE__ . '\load_class')) {

   function load_class($plugin_path, $plugin_path_orig, $namespace_chunks, $class_name, $folder_structure, $file_name, $whitelist) {

      if ( \stream_resolve_include_path($plugin_path)) {
         return $plugin_path;
      }

      if ( is_top_level($namespace_chunks, $class_name, $whitelist) ) {

         $plugin_path_orig .= build_top_level_path($folder_structure, $class_name, $file_name);
         
         if ( \stream_resolve_include_path( $plugin_path_orig ) ) {
            return $plugin_path_orig;
         }

         return false;

      }

      return false;

   }

}


/*

Find file to load

*/
if (!\function_exists(__NAMESPACE__ . '\find_file_to_autoload')) {

   function find_file_to_autoload($namespace, $plugin_path, $whitelist) {

      // Separates the components of the incoming namespace
      $plugin_path_orig					= $plugin_path;
      $namespace_chunks 				= split_namespace_into_chunks($namespace);
      $file_name 							= build_filename( get_last_value($namespace_chunks) );
      $class_name 						= get_class_from_file_name($file_name);
      $chunks_no_packagename 		   = remove_packagename_from_chunks($namespace_chunks, 'WP_Shopify');
      $chunks_no_filename 			   = remove_filename_from_chunks($chunks_no_packagename);
      $folder_structure 				= build_folder_structure($chunks_no_filename);

      $plugin_path 						.= combine_folder_and_filename($folder_structure, $file_name);

      // Now we include the file.
      return load_class($plugin_path, $plugin_path_orig, $namespace_chunks, $class_name, $folder_structure, $file_name, $whitelist);

   }

}

/*

Let's load this thing

*/
if (!\function_exists(__NAMESPACE__ . '\autoload')) {

   function autoload() {

      $plugin_path = get_plugin_classes_path();
      $whitelisted_classes = whitelisted_classnames();

      \spl_autoload_register( function($namespace) use ($plugin_path, $whitelisted_classes) {
         
         if (\strpos($namespace, 'WP_Shopify\\') === false) {
            return;
         }

         $file_path = find_file_to_autoload($namespace, $plugin_path, $whitelisted_classes);

         if ($file_path) {
            require_once($file_path);
         }

      });

   }

}

autoload();