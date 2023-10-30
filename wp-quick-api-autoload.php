<?php
/**
* @package Wp quick Api
* @author Bogdanov Andrey (swarzone2100@yandex.ru)
*/

spl_autoload_register(function($classname)
{
    $namespace = QAPI_NAMESPACE;
    $folder = DIRECTORY_SEPARATOR . QAPI_FOLDER . DIRECTORY_SEPARATOR;

    if ( strpos( $classname, $namespace ) !== false )
    {
        $use = explode( '\\', $classname );
        unset( $use[array_search( $namespace, $use )] );
        $file = __DIR__ . $folder . implode( DIRECTORY_SEPARATOR, $use ) . '.php';

        if ( !is_file( $file ) )
            throw new RuntimeException( "Class $classname not file of path: $file" );

        if( !is_readable( $file ) )
            throw new RuntimeException( "File of class $classname not readable: $file" );

        require_once $file;
    }
});

spl_autoload_extensions( '.php,.inc' );
