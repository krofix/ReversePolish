<?php

/**
* ReversePolish
* Реализация обратной польской нотации
*/
class ReversePolish
{
	/**
	 * Инициализация объекта контроллера операторов
	 * @param object $operatorController Интерфейс: IReversePolishOperator
	 */
	function __construct( $operatorController )
	{
		// Клонируем ОУО # ReversePolish::$operatorController
		$this->operatorController = clone $operatorController;
	}

	/**
	 * Сбрасывает настройки объекта для дальнейшего использования
	 */
	public function reset()
	{
		// Удаляем содержимое стека # ReversePolish::$operands
		$this->operands = array();
	}

	/**
	 * Выполняет команду (элемент) уравнения
	 * @param  string $command Может быть операндом или оператором
	 */
	public function pushCommand( $command )
	{
		// Проверяется является ли команда числом (операндом)
		if ( is_numeric( $command )) {
		// Если да
			// Элемент сохраняется в стек # ReversePolish::pushOperand()
			$this->pushOperand( $command );
		} else {
		// Если нет (значит это оператор)
			// Проверяем есть ли такой оператор # AnonymousFunctionsRPO::hasOperator()
			if ( $this->operatorController->hasOperator( $command )) {
			// Если да
				// Смотрим, сколько нужно параметров для данного оператора # AnonymousFunctionsRPO::getNumberOfArguments()
				$numOfArgs = $this->operatorController->getNumberOfArguments( $command );
				// Извлекаем необходимое число операндов # ReversePolish::popOperands()
				$operands = $this->popOperands( $numOfArgs );
				// Выполняется оператор # AnonymousFunctionsRPO::execute()
				$result = $this->operatorController->execute( $command, $operands );
				// Записываем возвращенное значение # ReversePolish::pushOperand()
				$this->pushOperand( $result );
			} else {
			// Если нет
				// Сообщаем о фатальной ошибке # Log::report()
				Log::report( 'В уравнении указан неизвестный оператор.' );
			}
		}
	}

	/**
	 * Возвращает значение последнего операнда в стеке не удаляя его
	 * @return numeric Результат выполнения уравнения
	 */
	public function getResult()
	{
		// Возвращает значение последнего операнда в стеке # ReversePolish::$operands
		$result = end( $this->operands );
		return $result;
	}

	/**
	 * Добавляет операнд в стек
	 * @param  numeric $operand Число для записи
	 */
	public function pushOperand( $operand )
	{
		// Добавить элемент в стек операндов # ReversePolish::$operands
		array_push( $this->operands, $operand );
	}

	/**
	 * Извлекает нужное количество операндов из стека
	 * @param  int $numOfOperands Количество операндов которое нужно извлечь
	 * @return array              Список операндов
	 */
	public function popOperands( $numOfOperands )
	{
		// Проверяет количество операндов в стеке # ReversePolish::getNumberOfOperands()
		if ( $numOfOperands <= $this->getNumberOfOperands() ) {
		// Если достаточно
			// Извлекаем их в массив # (array_pop)
			$operands = array();
			for ( ; $numOfOperands > 0; $numOfOperands--  ) {
				array_unshift( $operands, array_pop( $this->operands ));
			}
			// Возвращаем массив
			return $operands;
		} else {
		// Если нет
			// Выводим фатальную ошибку # Log::report()
			Log::report( 'В стеке не достаточно операндов для выполнения операции.' );
		}
	}

	/**
	 * Возвращает количество операндов в стеке
	 * @return integer Количество операндов в стеке
	 */
	public function getNumberOfOperands()
	{
		// Возвращает количество элементов в стеке # ReversePolish::$operands
		$numOfOperands = count( $this->operands );
		return $numOfOperands;
	}

	/**
	 * Маска для добавления операторов в объекте контроллера операторов. Передаваемые параметры должны соответстовать набору аргументов для аналогичного метода в инициализированном классе контроллера операторов
	 */
	public function addOperator()
	{
		// Получает все переданные аргументы # (func_get_args)
		$args = func_get_args();
		// Добавляет оператор в ОУО передав ему полученные аргументы # AnonymousFunctionsRPO::addOperator()
		call_user_func_array( array( $this->operatorController, 'addOperator' ), $args );
	}

	/**
	 * Маска для удаления операторов в объекте контроллера операторов.
	 * @param  string $operator Оператор который нужно удалить
	 */
	public function removeOperator( $operator )
	{
		// Удаляет оператор из ОУО # AnonymousFunctionsRPO::removeOperator()
		$this->operatorController->removeOperator( $operator );
	}

	/**
	 * Мост для контроллера операторов
	 * @var object Интерфейс: IReversePolishOperator
	 */
	private $operatorController = null;

	/**
	 * Стек операндов
	 * @var array
	 */
	private $operands = array();
}

/* =====================================
 * Интерфейсы
 * ===================================== */

interface IReversePolishOperator
{
	// public function addOperator();
	// public function removeOperator();
	// public function hasOperator();
	// public function execute();
	// public function getNumberOfArguments();
}
