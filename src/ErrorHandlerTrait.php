<?php

namespace accessd\yii2_rollbar;

trait ErrorHandlerTrait
{
    private static function initRollbar() {
        \Yii::$app->rollbar;
    }

    /**
     * Handles & reports uncaught PHP exceptions.
     */
    public function handleException($exception)
    {
        if (($exception instanceof \yii\web\HttpException and $exception->statusCode == 404) ||
            $exception instanceof \yii\web\BadRequestHttpException ||
            $exception instanceof \yii\web\ForbiddenHttpException ||
            $exception instanceof \yii\web\UnauthorizedHttpException ||
            $exception instanceof \yii\web\MethodNotAllowedHttpException) {
            // ignore errors
        } else {
            self::initRollbar();
            \Rollbar::report_exception($exception);
        }

        parent::handleException($exception);
    }

    /**
     * Handles & reports PHP execution errors such as warnings and notices.
     */
    public function handleError($code, $message, $file, $line)
    {
        self::initRollbar();
        \Rollbar::report_php_error($code, $message, $file, $line);

        parent::handleError($code, $message, $file, $line);
    }

    /**
     * Handles & reports fatal PHP errors that are causing the shutdown
     */
    public function handleFatalError() {
        self::initRollbar();
        \Rollbar::report_fatal_error();

        parent::handleFatalError();
    }
}
