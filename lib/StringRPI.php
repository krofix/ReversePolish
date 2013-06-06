<?php

/**
* StringRPI
* Интерфейс для работы с обратной польской нотацией посредством строки
*/
class StringRPI extends ReversePolish
{
	/**
	 * Выполняет уравнение и возвращает значение
	 * @param  string $equation Уравнение
	 * @return numeric          Результат выполнения уравнения
	 */
	public function execute( $equation )
	{
		// Сбрасывает текущее состояние # StringRPI::reset()
		$this->reset();
		// Сохраняет строку в объекте # StringRPI::register()
		$this->register( $equation );
		// Запускает выполнение уравнения # StringRPI::run()
		$this->run();
		// Возвращает результат # ReversePolish::getResult()
		return $this->getResult();
	}

	/**
	 * Фильтрует и сохраняет уравнение
	 * @param  string $equation Уравнение
	 */
	private function register( $equation )
	{
		// Обрезает пробелы по краям
		$formattedEquation = trim( $equation );
		// Заменяет множественные пробелы на один
		$formattedEquation = preg_replace( '/\s+/', ' ', $formattedEquation );

		// Проверяем изменилась ли строка
		if ( $equation != $formattedEquation ) {
		// Если да
			// Выводим предупреждение # Log::report()
			Log::report( 'Уравнение имеет неправильное форматирование.', Log::LVL_WARNING );
		}

		// Сохраняет уравнение в объект # StringRPI::$equation
		$this->equation = $formattedEquation;
	}

	/**
	 * Компилирует уравнение
	 */
	private function run()
	{
		// Цикл последовательно возвращающий элементы из строки # StringRPI::$equation
		$command = strtok( $this->equation, ' ' );
		while ( $command !== false ) {
			// Выполняем полученный элемент # ReversePolish::pushCommand()
			$this->pushCommand( $command );

			// "Прокручиваем" цикл
			$command = strtok( ' ' );
		}
	}

	/**
	 * Уравнение
	 * @var string
	 */
	private $equation = null;

	/* Gag
	---------------------------------------- */
	public function reset()
	{
		// Удаляет текущую строку # StringRPI::$equation
		$this->equation = null;
		// Вызывает родительский ресет # ReversePolish::reset()
		parent::reset();
	}
}
