<?php

/**
* AnonymousFunctionsRPO
* Класс для управления операторами в обратной польской нотации
*/
class AnonymousFunctionsRPO implements IReversePolishOperator
{
	/**
	 * Добавляет оператор
	 * @param string $operator Строчное значение оператора
	 * @param Clossure $callback Анонимная функция выполняющая роль оператора
	 */
	public function addOperator( $operator, $callback )
	{
		// Проверяет существует ли такой оператор # AnonymousFunctionsRPO::hasOperator()
		if ( $this->hasOperator( $operator )) {
		// Если да
			// Выдает предупреждение # Log::report()
			Log::report( "Оператор '{$operator}' был перезаписан", Log::LVL_WARNING );
		}

		// Добавляет или заменяет указанный оператор # AnonymousFunctionsRPO::$operators
		$this->operators[ $operator ] = array(
				'callback' => $callback,
				'numOfArgs' => null,
			);
	}

	/**
	 * Удаляет оператор
	 * @param  string $operator Оператор, который необходимо удалить
	 */
	public function removeOperator( $operator )
	{
		// Проверить есть ли такой оператор # AnonymousFunctionsRPO::hasOperator()
		if ( $this->hasOperator( $operator )) {
		// Если да
			// Удалить его
			unset( $this->operators[ $operator ] );
		} else {
		// Если нет
			// Вывести предупреждение # Log::report()
			Log::report( 'Вы попытались удалить несуществующий оператор', Log::LVL_WARNING );
		}
	}

	/**
	 * Проверяет, установлен ли оператор
	 * @param  string  $operator Оператор, наличие которого необходимо проверить
	 * @return boolean           Возвращает true, если оператор доступен, или false, в ином случае
	 */
	public function hasOperator( $operator )
	{
		// Проверяет сохранен ли указанный оператор # AnonymousFunctionsRPO::$operators
		if ( isset( $this->operators[ $operator ] )) {
		// Если да
			// Возвращает истину
			return true;
		} else {
		// Если нет
			// Возвращает ложь
			return false;
		}
	}

	/**
	 * Выполняет оператор
	 * @param  string $operator Оператр, который нужно выполнить
	 * @param  array  $operands Операнды, необходимые для оператора
	 * @return numeric          Результат выполнения оператора
	 */
	public function execute( $operator, array $operands )
	{
		// Проверяем есть ли такой оператор # AnonymousFunctionsRPO::hasOperator()
		if ( $this->hasOperator( $operator )) {
		// Если да
			// Проверяем достаточно ли передано аргументов # AnonymousFunctions::getNumberOfArguments()
			if ( count( $operands ) === $this->getNumberOfArguments( $operator ) ) {
			// Если да
				// Запускаем вложенный callback передав ему аргументы # (call_user_func_array)
				$result = call_user_func_array( $this->operators[ $operator ]['callback'], $operands );
				// Проверяем возвратил ли метод число
				if ( is_numeric( $result )) {
				// Если да
					// Возвращаем результат
					return $result;
				} else {
				// Если нет
					// Сообщаем о фатальной ошибке # Log::report()
					Log::report( "Оператор '{$operator}' выполнился с ошибкой: <em>" . $result . "</em>" );
				}
			} else {
			// Если нет
				// Сообщаем о фатальной ошибке # Log::report()
				Log::report( 'В стеке недостаточно операндов для выполнения операции.' );
			}
		} else {
		// Если нет
			// Сообщаем о фатальной ошибке # Log::report()
			Log::report( 'Вы попытались выполнить не существующий оператор.' );
		}
	}

	/**
	 * Возвращает количество аргументов, необходимых для указанного оператора
	 * @param  string $operator Оператор
	 * @return int              Количество операндов
	 */
	public function getNumberOfArguments( $operator )
	{
		// Проверяем есть ли такой оператор # AnonymousFunctionsRPO::hasOperator()
		if ( $this->hasOperator( $operator )) {
		// Если да
			// Проверяем указано ли количество аргументов # AnonymousFunctionsRPO::$operators
			if ( ! is_numeric( $this->operators[ $operator ]['numOfArgs'] )) {
			// Если нет
				// Получаем количество аргументов метода для указанного оператора и сохраняем его # (ReflectionFunction::getNumberOfArguments()), AnonymousFunctionsRPO::$operators
				$reflection = new ReflectionFunction( $this->operators[ $operator ]['callback'] );
				$this->operators[ $operator ]['numOfArgs'] = $reflection->getNumberOfParameters();
			}
			// Возвращаем количество аргументов
			return $this->operators[ $operator ]['numOfArgs'];
		} else {
		// Если нет
			// Сообщаем о фатальной ошибке # Log::report()
			Log::report( 'Вы попытались узнать количество аргументов для не существующего оператора.' );
		}
	}

	/**
	 * Анонимные функции выполняющие роль операторов
	 * @var array Структура: [operator: [callback: Clossure, numOfArgs: null]]
	 */
	private $operators = array();
}
