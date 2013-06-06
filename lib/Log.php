<?php

/**
* Log
* Класса для логирования, уведомления об ошибках и прерываний
*/
class Log
{
	/**
	 * Обрабатывает ошибку
	 * @param  string  $message    Сообщение для вывода пользователю
	 * @param  integer $errorLevel Уровень ошибки
	 */
	static public function report( $message = 'Ничто не идеально. Никто не идеален.', $errorLevel = Log::LVL_FATAL )
	{
		// Вывести оформленное сообщение об ошибке # $message
		$backtraces = debug_backtrace();
		$backtrace = end( $backtraces );
		echo <<<ERROR
<fieldset>
	<legend>Ошибка в файле '<em>{$backtrace['file']}</em>' на {$backtrace['line']}-й строке</legend>
	<p>{$message}</p>
</fieldset>
ERROR;
		// Если $errorLevel назначен как фатальная ошибка # Log::LVL_FATAL
		if ( self::LVL_FATAL == $errorLevel ) {
			// Завершить выполнение приложения
			exit;
		}
	}

	/**
	 * Для ошибок которые были устранены программными средствами
	 */
	const LVL_WARNING = 1;

	/**
	 * Для ошибок которые не могут быть устранены без человеческого вмешательства
	 */
	const LVL_FATAL = 2;
}
