<?php
namespace Xshifty;

class AsyncClosure
{
    private static $shutdownFunctionSet = false;

    public static function create(callable $closure, $callback = null)
    {
        if (!self::$shutdownFunctionSet) {
            self::$shutdownFunctionSet = true;
            register_shutdown_function(function () {
                $status = null;
                while (pcntl_wait($status) != -1) {
                }
            });
        }

        if (!function_exists('pcntl_fork')) {
            throw new \Exception("You don't have pcntl enabled.");
        }

        if (!is_callable($callback)) {
            $callback = function () {
            };
        }

        $closureWrapper = function () use ($closure, $callback) {
            $pid = pcntl_fork();

            if ($pid === -1) {
                throw new \Exception('Could not fork');
            }

            if ($pid === 0) {
                call_user_func(
                    $callback,
                    call_user_func_array($closure, func_get_args())
                );
                
                posix_kill(posix_getpid(), SIGKILL);
                return;
            }
        };

        return $closureWrapper;
    }
}
