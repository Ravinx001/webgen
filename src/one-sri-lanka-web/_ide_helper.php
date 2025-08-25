<?php
/* @noinspection ALL */
// @formatter:off
// phpcs:ignoreFile

/**
 * A helper file for Laravel, to provide autocomplete information to your IDE
 * Generated for Laravel 12.21.0.
 *
 * This file should not be included in your code, only analyzed by your IDE!
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 * @see https://github.com/barryvdh/laravel-ide-helper
 */
namespace App\Facades {
    /**
     */
    class OSLAuthServiceFacade {
        /**
         * Authenticate user with external API
         *
         * @param array $credentials User credentials (email, password, etc.)
         * @return array Standardized response array
         * @static
         */
        public static function authenticate($credentials)
        {
            /** @var \App\Services\OSLAuthService $instance */
            return $instance->authenticate($credentials);
        }

        /**
         * Check if user is currently authenticated
         *
         * @return bool
         * @static
         */
        public static function isAuthenticated()
        {
            /** @var \App\Services\OSLAuthService $instance */
            return $instance->isAuthenticated();
        }

        /**
         * Get current authentication token
         *
         * @return string|null
         * @static
         */
        public static function getToken()
        {
            /** @var \App\Services\OSLAuthService $instance */
            return $instance->getToken();
        }

        /**
         * Get authenticated user data
         *
         * @return array|null
         * @static
         */
        public static function getUser()
        {
            /** @var \App\Services\OSLAuthService $instance */
            return $instance->getUser();
        }

        /**
         * Check if user has specific permission
         *
         * @param string $permission
         * @return bool
         * @static
         */
        public static function hasPermission($permission)
        {
            /** @var \App\Services\OSLAuthService $instance */
            return $instance->hasPermission($permission);
        }

        /**
         * Logout user and clear session data
         *
         * @return void
         * @static
         */
        public static function logout()
        {
            /** @var \App\Services\OSLAuthService $instance */
            $instance->logout();
        }

        /**
         * Refresh authentication token
         *
         * @return array
         * @static
         */
        public static function refreshToken()
        {
            /** @var \App\Services\OSLAuthService $instance */
            return $instance->refreshToken();
        }

            }
    }

namespace Illuminate\Http {
    /**
     */
    class Request extends \Symfony\Component\HttpFoundation\Request {
        /**
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestValidation()
         * @param array $rules
         * @param mixed $params
         * @static
         */
        public static function validate($rules, ...$params)
        {
            return \Illuminate\Http\Request::validate($rules, ...$params);
        }

        /**
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestValidation()
         * @param string $errorBag
         * @param array $rules
         * @param mixed $params
         * @static
         */
        public static function validateWithBag($errorBag, $rules, ...$params)
        {
            return \Illuminate\Http\Request::validateWithBag($errorBag, $rules, ...$params);
        }

        /**
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestSignatureValidation()
         * @param mixed $absolute
         * @static
         */
        public static function hasValidSignature($absolute = true)
        {
            return \Illuminate\Http\Request::hasValidSignature($absolute);
        }

        /**
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestSignatureValidation()
         * @static
         */
        public static function hasValidRelativeSignature()
        {
            return \Illuminate\Http\Request::hasValidRelativeSignature();
        }

        /**
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestSignatureValidation()
         * @param mixed $ignoreQuery
         * @param mixed $absolute
         * @static
         */
        public static function hasValidSignatureWhileIgnoring($ignoreQuery = [], $absolute = true)
        {
            return \Illuminate\Http\Request::hasValidSignatureWhileIgnoring($ignoreQuery, $absolute);
        }

        /**
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestSignatureValidation()
         * @param mixed $ignoreQuery
         * @static
         */
        public static function hasValidRelativeSignatureWhileIgnoring($ignoreQuery = [])
        {
            return \Illuminate\Http\Request::hasValidRelativeSignatureWhileIgnoring($ignoreQuery);
        }

            }
    }


namespace  {
    class oslauthservice extends \App\Facades\OSLAuthServiceFacade {}
}





