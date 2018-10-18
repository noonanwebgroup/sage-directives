<?php

namespace App\Directives;

use function App\sage;

/**
 * Directives
 */
if (!class_exists('Directives')) {
    class Directives
    {
        /**
         * Directives
         *
         * @var array
         */
        protected $directives = [
            'ACF',
            'Helpers',
            'WordPress'
        ];

        /**
         * Constructor
         */
        public function __construct()
        {
            $directives = collect($this->directives)->flatMap(function ($directive) {
                if ($directive === 'ACF' && !function_exists('acf')) {
                    return;
                }

                return $this->get($directive);
            });

            /**
             * Register Directives with Blade
             */
            add_action('after_setup_theme', function () use ($directives) {
                collect($directives)->each(function ($directive, $function) {
                    sage('blade')->compiler()->directive($function, $directive);
                });
            }, 20);
        }

        /**
         * Returns the specified directives as an array.
         *
         * @param  string $name
         * @return array
         */
        public function get($name)
        {
            if (file_exists($directives = __DIR__.'/Directives/'.$name.'.php')) {
                return require_once($directives);
            }
        }
    }

    new Directives();
}
